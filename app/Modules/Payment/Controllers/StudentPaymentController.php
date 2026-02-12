<?php

namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use Modules\Payment\Models\InvoiceModel;
use Modules\Payment\Models\PaymentModel;
use Modules\Admission\Models\AdmissionModel;

/**
 * Student Payment Controller
 * Allows students to view their own invoices and payments
 */
class StudentPaymentController extends BaseController
{
    protected $invoiceModel;
    protected $paymentModel;
    protected $admissionModel;

    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->paymentModel = new PaymentModel();
        $this->admissionModel = new AdmissionModel();
    }

    /**
     * Get registration number for the logged-in user
     * Links user -> profile -> admission -> registration_number
     *
     * @return string|null
     */
    protected function getStudentRegistrationNumber(): ?string
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        $db = \Config\Database::connect();
        $result = $db->table('profiles')
            ->select('admissions.registration_number')
            ->join('admissions', 'admissions.profile_id = profiles.id', 'left')
            ->where('profiles.user_id', $user->id)
            ->where('admissions.status', 'approved') // Only approved students
            ->get()
            ->getRowArray();

        return $result['registration_number'] ?? null;
    }

    /**
     * Check if user is a student with approved admission
     *
     * @return bool
     */
    protected function isStudent(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        // Check if user is in student group
        if (!$user->inGroup('student')) {
            return false;
        }

        // Check if user has approved admission
        $registrationNumber = $this->getStudentRegistrationNumber();
        return $registrationNumber !== null;
    }

    /**
     * Display student's invoices list
     */
    public function myInvoices()
    {
        // Check authentication
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Please login to view your invoices.');
        }

        $registrationNumber = $this->getStudentRegistrationNumber();

        if (!$registrationNumber) {
            // Check if user is a student but not yet approved
            if (auth()->user()->inGroup('student')) {
                return view('Modules\Payment\Views\student\pending_approval', [
                    'title' => 'Pending Approval',
                    'message' => 'Your admission is still pending approval. Please contact the administration for more information.'
                ]);
            }

            return redirect()->to('/dashboard')->with('error', 'You do not have an approved admission.');
        }

        // Get student's invoices
        $perPage = 10;
        $keyword = $this->request->getGet('search');

        if ($keyword) {
            $invoices = $this->invoiceModel->searchStudentInvoices($registrationNumber, $keyword);
        } else {
            $invoices = $this->invoiceModel->getStudentInvoices($registrationNumber, $perPage);
        }

        // Process expired invoices
        $this->invoiceModel->processExpiredInvoices();

        // Enrich with payment info
        foreach ($invoices as &$invoice) {
            $payments = $this->paymentModel->where('invoice_id', $invoice['id'])
                ->where('status', 'paid')
                ->findAll();
            $invoice['total_paid'] = array_sum(array_column($payments, 'amount'));
            $invoice['payment_count'] = count($payments);
        }

        $pager = $this->invoiceModel->pager;

        return view('Modules\Payment\Views\student\invoices', [
            'title' => 'My Invoices',
            'invoices' => $invoices,
            'pager' => $pager,
            'keyword' => $keyword,
            'registration_number' => $registrationNumber,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Display invoice details for student
     */
    public function myInvoiceDetail($id)
    {
        // Check authentication
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Please login to view invoice details.');
        }

        $registrationNumber = $this->getStudentRegistrationNumber();

        if (!$registrationNumber) {
            return redirect()->to('/dashboard')->with('error', 'You do not have an approved admission.');
        }

        // Get invoice and verify ownership
        $invoice = $this->invoiceModel->getInvoiceWithPayments($id);

        if (!$invoice) {
            return redirect()->to('/my/invoices')->with('error', 'Invoice not found.');
        }

        // Verify this invoice belongs to the student
        if ($invoice['registration_number'] !== $registrationNumber) {
            return redirect()->to('/my/invoices')->with('error', 'You do not have permission to view this invoice.');
        }

        // Get student details
        $student = $this->admissionModel->select('
                admissions.registration_number,
                profiles.full_name,
                profiles.email,
                profiles.phone,
                programs.title as program_title,
                programs.category as program_category
            ')
            ->join('profiles', 'profiles.id = admissions.profile_id', 'left')
            ->join('programs', 'programs.id = admissions.program_id', 'left')
            ->where('admissions.registration_number', $registrationNumber)
            ->first();

        return view('Modules\Payment\Views\student\invoice_detail', [
            'title' => 'Invoice #' . $invoice['invoice_number'],
            'invoice' => $invoice,
            'student' => $student,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Display student's payments list
     */
    public function myPayments()
    {
        // Check authentication
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Please login to view your payments.');
        }

        $registrationNumber = $this->getStudentRegistrationNumber();

        if (!$registrationNumber) {
            if (auth()->user()->inGroup('student')) {
                return view('Modules\Payment\Views\student\pending_approval', [
                    'title' => 'Pending Approval',
                    'message' => 'Your admission is still pending approval. Please contact the administration for more information.'
                ]);
            }

            return redirect()->to('/dashboard')->with('error', 'You do not have an approved admission.');
        }

        // Get student's payments
        $perPage = 10;
        $keyword = $this->request->getGet('search');

        if ($keyword) {
            $payments = $this->paymentModel->searchStudentPayments($registrationNumber, $keyword);
        } else {
            $payments = $this->paymentModel->getStudentPayments($registrationNumber, $perPage);
        }

        // Enrich with invoice details
        foreach ($payments as &$payment) {
            if ($payment['invoice_id']) {
                $invoice = $this->invoiceModel->find($payment['invoice_id']);
                $payment['invoice_number'] = $invoice['invoice_number'] ?? 'N/A';
            } else {
                $payment['invoice_number'] = 'N/A';
            }
        }

        $pager = $this->paymentModel->pager;

        return view('Modules\Payment\Views\student\payments', [
            'title' => 'My Payments',
            'payments' => $payments,
            'pager' => $pager,
            'keyword' => $keyword,
            'registration_number' => $registrationNumber,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Display payment details/receipt for student
     */
    public function myPaymentDetail($id)
    {
        // Check authentication
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Please login to view payment details.');
        }

        $registrationNumber = $this->getStudentRegistrationNumber();

        if (!$registrationNumber) {
            return redirect()->to('/dashboard')->with('error', 'You do not have an approved admission.');
        }

        // Get payment with invoice info
        $db = \Config\Database::connect();
        $payment = $db->table('payments')
            ->select('payments.*, invoices.invoice_number, invoices.description as invoice_description')
            ->join('invoices', 'invoices.id = payments.invoice_id', 'left')
            ->where('payments.id', $id)
            ->get()
            ->getRowArray();

        if (!$payment) {
            return redirect()->to('/my/payments')->with('error', 'Payment not found.');
        }

        // Verify this payment belongs to the student
        if ($payment['registration_number'] !== $registrationNumber) {
            return redirect()->to('/my/payments')->with('error', 'You do not have permission to view this payment.');
        }

        // Get student details
        $student = $this->admissionModel->select('
                admissions.registration_number,
                profiles.full_name,
                profiles.email,
                profiles.phone,
                programs.title as program_title
            ')
            ->join('profiles', 'profiles.id = admissions.profile_id', 'left')
            ->join('programs', 'programs.id = admissions.program_id', 'left')
            ->where('admissions.registration_number', $registrationNumber)
            ->first();

        return view('Modules\Payment\Views\student\payment_detail', [
            'title' => 'Payment Receipt #' . $payment['document_number'],
            'payment' => $payment,
            'student' => $student,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Get invoice summary for student dashboard widget
     */
    public function myInvoiceSummary()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not authenticated'
            ]);
        }

        $registrationNumber = $this->getStudentRegistrationNumber();

        if (!$registrationNumber) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not a student'
            ]);
        }

        $db = \Config\Database::connect();

        // Get invoice counts by status
        $summary = $db->table('invoices')
            ->select("
                COUNT(*) as total_invoices,
                SUM(CASE WHEN status = 'unpaid' THEN amount ELSE 0 END) as unpaid_amount,
                SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) as paid_amount,
                SUM(CASE WHEN status = 'partially_paid' THEN amount ELSE 0 END) as partially_paid_amount,
                SUM(CASE WHEN status = 'unpaid' OR status = 'partially_paid' THEN 1 ELSE 0 END) as outstanding_count
            ")
            ->where('registration_number', $registrationNumber)
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $summary
        ]);
    }
}
