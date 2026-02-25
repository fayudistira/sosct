<?php

namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use Modules\Payment\Models\PaymentModel;
use Modules\Payment\Models\InvoiceModel;
use Modules\Payment\Models\InstallmentModel;
use Modules\Admission\Models\AdmissionModel;

class PaymentController extends BaseController
{
    protected $paymentModel;
    protected $invoiceModel;
    protected $installmentModel;
    protected $admissionModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->invoiceModel = new InvoiceModel();
        $this->installmentModel = new InstallmentModel();
        $this->admissionModel = new AdmissionModel();
    }

    /**
     * Display list of payments
     */
    public function index()
    {
        $perPage = 10;
        $keyword = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $method = $this->request->getGet('method');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // Build filters
        $filters = [];
        if ($status) {
            $filters['status'] = $status;
        }
        if ($method) {
            $filters['method'] = $method;
        }
        if ($startDate) {
            $filters['start_date'] = $startDate;
        }
        if ($endDate) {
            $filters['end_date'] = $endDate;
        }

        // Get payments
        if ($keyword) {
            $payments = $this->paymentModel->searchPayments($keyword);
        } elseif (!empty($filters)) {
            $payments = $this->paymentModel->filterPayments($filters);
        } else {
            $payments = $this->paymentModel->paginate($perPage);
        }

        // Enrich with student and invoice details (only for paginated results)
        foreach ($payments as &$payment) {
            // For paginated results, fetch student and invoice details
            if (!isset($payment['student_name'])) {
                $student = $this->admissionModel->getByRegistrationNumber((string)$payment['registration_number']);
                $payment['student'] = $student;
                $payment['student_name'] = $student['full_name'] ?? 'N/A';

                // Get invoice details if linked
                if ($payment['invoice_id']) {
                    $invoice = $this->invoiceModel->find($payment['invoice_id']);
                    $payment['invoice_number'] = $invoice['invoice_number'] ?? 'N/A';
                } else {
                    $payment['invoice_number'] = 'N/A';
                }
            } else {
                // For search/filter results, student_name and invoice_number are already set
                // Just set student array for compatibility
                $payment['student'] = [
                    'full_name' => $payment['student_name'] ?? 'N/A'
                ];
            }
        }

        $pager = $this->paymentModel->pager;

        return view('Modules\Payment\Views\payments\index', [
            'title' => 'Payments',
            'payments' => $payments,
            'pager' => $pager,
            'keyword' => $keyword,
            'status' => $status,
            'method' => $method,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Display payment details
     */
    public function view($id)
    {
        $payment = $this->paymentModel->find($id);

        if (!$payment) {
            return redirect()->to('/payment')->with('error', 'Payment not found.');
        }

        // Get student details
        $student = $this->admissionModel->getByRegistrationNumber((string)$payment['registration_number']);
        $payment['student'] = $student;

        // Get invoice if linked
        if ($payment['invoice_id']) {
            $invoice = $this->invoiceModel->find($payment['invoice_id']);
            $payment['invoice'] = $invoice;
        }

        return view('Modules\Payment\Views\payments\view', [
            'title' => 'Payment Details',
            'payment' => $payment,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('Modules\Payment\Views\payments\create', [
            'title' => 'Create Payment',
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Store new payment
     */
    public function store()
    {
        // Get the invoice first to link to installment
        $invoiceId = $this->request->getPost('invoice_id');
        $registrationNumber = $this->request->getPost('registration_number');
        $installmentId = null;

        // If invoice is selected, get its installment
        if ($invoiceId) {
            $invoice = $this->invoiceModel->find($invoiceId);
            // Only link to installment if invoice is NOT a miscellaneous_fee
            // Miscellaneous fees should not affect contract balance
            if ($invoice && !empty($invoice['installment_id']) && $invoice['invoice_type'] !== 'miscellaneous_fee') {
                $installmentId = $invoice['installment_id'];
            }
        }

        // If no installment from invoice, try to find by registration_number
        // This allows direct payments without invoice to still affect contract balance
        // Note: Direct payments without invoice are assumed to be for contract/tuition
        if (!$installmentId && $registrationNumber) {
            $installment = $this->installmentModel->getByRegistrationNumber($registrationNumber);
            if ($installment) {
                $installmentId = $installment['id'];
            }
        }

        // Build notes with direct payment indicator
        $notes = $this->request->getPost('notes');
        if (!$invoiceId) {
            $notes = '[Direct Payment - No Invoice]' . ($notes ? ' ' . $notes : '');
        }

        $data = [
            'registration_number' => $registrationNumber,
            'invoice_id' => $invoiceId ?: null,
            'installment_id' => $installmentId,
            'amount' => $this->request->getPost('amount'),
            'payment_method' => $this->request->getPost('payment_method'),
            'document_number' => $this->request->getPost('document_number'),
            'payment_date' => $this->request->getPost('payment_date'),
            'status' => $this->request->getPost('status') ?: 'pending',
            'notes' => $notes
        ];

        // Handle receipt file upload
        $receiptFile = $this->request->getFile('receipt_file');
        if ($receiptFile && $receiptFile->isValid() && !$receiptFile->hasMoved()) {
            $filePath = $this->paymentModel->uploadReceiptFile($receiptFile);
            if ($filePath) {
                $data['receipt_file'] = $filePath;
            }
        }

        if ($this->paymentModel->insert($data)) {
            $paymentId = $this->paymentModel->insertID();

            // If linked to installment, update its totals (works for both with and without invoice)
            if ($installmentId) {
                $this->installmentModel->updatePaymentTotal($installmentId);
            }

            // If linked to invoice and status is paid, recalculate invoice status
            if ($invoiceId && $data['status'] === 'paid') {
                $newInvoiceStatus = $this->invoiceModel->recalculateInvoiceStatus($invoiceId);

                // Auto-approve admission when ANY payment is recorded for the initial invoice
                $admission = $this->admissionModel->where('registration_number', $data['registration_number'])->first();
                if ($admission && $admission['status'] === 'pending') {
                    // Update admission to approved
                    $this->admissionModel->update($admission['id'], [
                        'status' => 'approved',
                        'reviewed_date' => date('Y-m-d H:i:s'),
                        'notes' => ($admission['notes'] ? $admission['notes'] . "\n" : "") . "[" . date('Y-m-d H:i:s') . "] Automatically approved upon payment of invoice #" . $this->invoiceModel->find($invoiceId)['invoice_number'] . ". Amount: Rp " . number_format($data['amount'], 0, ',', '.') . ". Invoice status: " . $newInvoiceStatus . "."
                    ]);

                    // Send approval notification email to applicant
                    $profile = (new \Modules\Account\Models\ProfileModel())->find($admission['profile_id']);
                    if ($profile && !empty($profile['email'])) {
                        $emailService = new \App\Services\EmailService();
                        $paymentData = [
                            'amount' => $data['amount'],
                            'created_at' => date('Y-m-d H:i:s')
                        ];

                        $emailService->sendPaymentReceivedNotification(
                            $profile['email'],
                            $profile['full_name'],
                            $paymentData
                        );
                    }
                }
            }

            // Success message differs based on whether invoice was linked
            $successMsg = $invoiceId 
                ? 'Payment created and linked to invoice successfully.' 
                : 'Direct payment created successfully. Contract balance has been updated.';
            return redirect()->to('/payment')->with('success', $successMsg);
        }

        return redirect()->back()->withInput()->with('errors', $this->paymentModel->errors());
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $payment = $this->paymentModel->find($id);

        if (!$payment) {
            return redirect()->to('/payment')->with('error', 'Payment not found.');
        }

        // Get all approved admissions with student details for dropdown
        $students = $this->admissionModel->getAllWithDetails();

        // Filter only approved admissions
        $students = array_filter($students, function ($student) {
            return $student['status'] === 'approved';
        });

        // Get unpaid and partially paid invoices for dropdown
        $invoices = $this->invoiceModel->where('status', 'unpaid')
            ->orWhere('status', 'partially_paid')
            ->findAll();

        return view('Modules\Payment\Views\payments\edit', [
            'title' => 'Edit Payment',
            'payment' => $payment,
            'students' => $students,
            'invoices' => $invoices,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Update payment
     */
    public function update($id)
    {
        $payment = $this->paymentModel->find($id);

        if (!$payment) {
            return redirect()->to('/payment')->with('error', 'Payment not found.');
        }

        $data = [
            'id' => $id,
            'registration_number' => $this->request->getPost('registration_number'),
            'invoice_id' => $this->request->getPost('invoice_id') ?: $payment['invoice_id'],
            'amount' => $this->request->getPost('amount'),
            'payment_method' => $this->request->getPost('payment_method'),
            'document_number' => $this->request->getPost('document_number'),
            'payment_date' => $this->request->getPost('payment_date'),
            'status' => $this->request->getPost('status'),
            'notes' => $this->request->getPost('notes')
        ];

        // Handle receipt file upload
        $receiptFile = $this->request->getFile('receipt_file');
        if ($receiptFile && $receiptFile->isValid() && !$receiptFile->hasMoved()) {
            $filePath = $this->paymentModel->uploadReceiptFile($receiptFile);
            if ($filePath) {
                $data['receipt_file'] = $filePath;
            }
        }

        if ($this->paymentModel->save($data)) {
            // If linked to invoice and status is paid, recalculate invoice status
            if ($data['invoice_id'] && $data['status'] === 'paid') {
                $newInvoiceStatus = $this->invoiceModel->recalculateInvoiceStatus($data['invoice_id']);

                // Auto-approve admission when ANY payment is recorded for the initial invoice
                $admission = $this->admissionModel->where('registration_number', $data['registration_number'])->first();
                if ($admission && $admission['status'] === 'pending') {
                    $this->admissionModel->update($admission['id'], [
                        'status' => 'approved',
                        'reviewed_date' => date('Y-m-d H:i:s'),
                        'notes' => ($admission['notes'] ? $admission['notes'] . "\n" : "") . "[" . date('Y-m-d H:i:s') . "] Automatically approved upon payment update of invoice #" . $this->invoiceModel->find($data['invoice_id'])['invoice_number'] . ". Amount: Rp " . number_format($data['amount'], 0, ',', '.') . ". Invoice status: " . $newInvoiceStatus . "."
                    ]);
                }
            }

            return redirect()->to('/payment')->with('success', 'Payment updated successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->paymentModel->errors());
    }

    /**
     * Revenue report
     */
    public function revenueReport()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-01-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        // Get statistics
        $stats = $this->paymentModel->getDashboardStatistics($startDate, $endDate);
        $revenueByMethod = $this->paymentModel->getRevenueByMethod();
        $revenueByType = $this->paymentModel->getRevenueByType();
        $monthlyTrend = $this->paymentModel->getMonthlyRevenueTrend(date('Y'));

        // Get detailed payments for export
        $payments = $this->paymentModel->filterPayments([
            'status' => 'paid',
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        return view('Modules\Payment\Views\reports\revenue', [
            'title' => 'Revenue Report',
            'stats' => $stats,
            'revenueByMethod' => $revenueByMethod,
            'revenueByType' => $revenueByType,
            'monthlyTrend' => $monthlyTrend,
            'payments' => $payments,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Overdue invoices report
     */
    public function overdueReport()
    {
        $overdueInvoices = $this->paymentModel->getOverduePayments();

        return view('Modules\Payment\Views\reports\overdue', [
            'title' => 'Overdue Invoices Report',
            'invoices' => $overdueInvoices,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Export report to CSV
     */
    public function exportCsv()
    {
        $type = $this->request->getGet('type');
        $startDate = $this->request->getGet('start_date') ?? date('Y-01-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        if ($type === 'revenue') {
            $data = $this->paymentModel->filterPayments([
                'status' => 'paid',
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
            $filename = 'revenue_report_' . date('Y-m-d') . '.csv';
        } elseif ($type === 'overdue') {
            $data = $this->paymentModel->getOverduePayments();
            $filename = 'overdue_report_' . date('Y-m-d') . '.csv';
        } else {
            return redirect()->back()->with('error', 'Invalid report type');
        }

        $csv = $this->paymentModel->exportToCSV($data);

        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    /**
     * Display printable payment receipt
     */
    public function receipt($id)
    {
        $payment = $this->paymentModel->find($id);

        if (!$payment) {
            return redirect()->to('/payment')->with('error', 'Payment not found.');
        }

        // Get student details
        $student = $this->admissionModel->select('
                admissions.registration_number,
                profiles.full_name,
                profiles.email,
                profiles.phone,
                programs.title as program_title
            ')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('programs', 'programs.id = admissions.program_id')
            ->where('admissions.registration_number', (string)$payment['registration_number'])
            ->first();

        // Get invoice details if linked
        $invoice = null;
        if ($payment['invoice_id']) {
            $invoice = $this->invoiceModel->getInvoiceWithItems($payment['invoice_id']);
        }

        // Company information
        $company = [
            'address' => 'Perum GPR 1 Blok C No.4, Jl. Veteran Tulungrejo, Pare, Kediri 64212',
            'email' => 'admin@kursusbahasa.org',
            'phone' => '+62 858 1031 0950'
        ];

        // Generate payment number if not exists
        if (!isset($payment['payment_number'])) {
            $payment['payment_number'] = 'PAY-' . date('Y') . '-' . str_pad((string)$payment['id'], 4, '0', STR_PAD_LEFT);
        }

        return view('Modules\Payment\Views\payments\receipt', [
            'payment' => $payment,
            'student' => $student,
            'invoice' => $invoice,
            'company' => $company
        ]);
    }

    /**
     * Public receipt view (no authentication required)
     */
    public function publicReceipt($id)
    {
        $payment = $this->paymentModel->find($id);

        if (!$payment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Payment not found');
        }

        // Get student details
        $student = $this->admissionModel->select('
                admissions.registration_number,
                profiles.full_name,
                profiles.email,
                profiles.phone,
                programs.title as program_title
            ')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('programs', 'programs.id = admissions.program_id')
            ->where('admissions.registration_number', (string)$payment['registration_number'])
            ->first();

        // Get invoice details if linked
        $invoice = null;
        if ($payment['invoice_id']) {
            $invoice = $this->invoiceModel->getInvoiceWithItems($payment['invoice_id']);
        }

        // Company information
        $company = [
            'address' => 'Perum GPR 1 Blok C No.4, Jl. Veteran Tulungrejo, Pare, Kediri 64212',
            'email' => 'admin@kursusbahasa.org',
            'phone' => '+62 858 1031 0950'
        ];

        // Generate payment number if not exists
        if (!isset($payment['payment_number'])) {
            $payment['payment_number'] = 'PAY-' . date('Y') . '-' . str_pad((string)$payment['id'], 4, '0', STR_PAD_LEFT);
        }

        return view('Modules\Payment\Views\payments\receipt', [
            'payment' => $payment,
            'student' => $student,
            'invoice' => $invoice,
            'company' => $company
        ]);
    }

    /**
     * Generate QR code for payment receipt
     */
    public function generateQr($id)
    {
        $payment = $this->paymentModel->find($id);

        if (!$payment) {
            return $this->response->setStatusCode(404)->setBody('Payment not found');
        }

        // Get student email for token generation
        $student = $this->admissionModel->select('profiles.email')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->where('admissions.registration_number', (string)$payment['registration_number'])
            ->first();

        if (!$student || empty($student['email'])) {
            // Fallback to public URL if no email available
            $publicUrl = base_url('payment/public/' . $id);
        } else {
            // Generate secure token for payment receipt
            $emailService = new \App\Services\EmailService();
            $token = $emailService->generatePaymentToken($id, $student['email']);
            $publicUrl = base_url('payment/secure/' . $token);
        }

        // Create QR code using Builder (v6.0 API)
        $builder = new \Endroid\QrCode\Builder\Builder(
            data: $publicUrl,
            size: 300,
            margin: 10
        );
        $result = $builder->build();

        // Return QR code image
        return $this->response
            ->setHeader('Content-Type', 'image/png')
            ->setBody($result->getString());
    }

    /**
     * Secure receipt view using encrypted token (no authentication required)
     */
    public function secureReceipt($token)
    {
        // Load EmailService for token verification
        $emailService = new \App\Services\EmailService();
        $tokenData = $emailService->verifyPaymentToken($token);

        if (!$tokenData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid or expired receipt link');
        }

        // Get payment using the decrypted payment ID
        $payment = $this->paymentModel->find($tokenData['payment_id']);

        if (!$payment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Payment not found');
        }

        // Get student details
        $student = $this->admissionModel->select('
                admissions.registration_number,
                profiles.full_name,
                profiles.email,
                profiles.phone,
                programs.title as program_title
            ')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('programs', 'programs.id = admissions.program_id')
            ->where('admissions.registration_number', (string)$payment['registration_number'])
            ->first();

        // Get invoice details if linked
        $invoice = null;
        if ($payment['invoice_id']) {
            $invoice = $this->invoiceModel->getInvoiceWithItems($payment['invoice_id']);
        }

        // Company information
        $company = [
            'address' => 'Perum GPR 1 Blok C No.4, Jl. Veteran Tulungrejo, Pare, Kediri 64212',
            'email' => 'admin@kursusbahasa.org',
            'phone' => '+62 858 1031 0950'
        ];

        // Generate payment number if not exists
        if (!isset($payment['payment_number'])) {
            $payment['payment_number'] = 'PAY-' . date('Y') . '-' . str_pad((string)$payment['id'], 4, '0', STR_PAD_LEFT);
        }

        return view('Modules\Payment\Views\payments\receipt', [
            'payment' => $payment,
            'student' => $student,
            'invoice' => $invoice,
            'company' => $company
        ]);
    }
}
