<?php

namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use Modules\Payment\Models\InstallmentModel;
use Modules\Payment\Models\PaymentModel;
use Modules\Payment\Models\InvoiceModel;
use Modules\Admission\Models\AdmissionModel;

class ContractController extends BaseController
{
    protected $installmentModel;
    protected $paymentModel;
    protected $invoiceModel;
    protected $admissionModel;

    public function __construct()
    {
        $this->installmentModel = new InstallmentModel();
        $this->paymentModel = new PaymentModel();
        $this->invoiceModel = new InvoiceModel();
        $this->admissionModel = new AdmissionModel();
    }

    /**
     * Display contract view by registration number
     *
     * @param string $registrationNumber
     * @return string
     */
    public function view(string $registrationNumber)
    {
        // Get admission details
        $admission = $this->admissionModel->getByRegistrationNumber($registrationNumber);

        if (!$admission) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Admission not found');
        }

        // Get installment
        $installment = $this->installmentModel->getWithDetails($registrationNumber);

        if (!$installment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Installment record not found');
        }

        // Get payments for this installment
        $payments = $this->paymentModel->getPaymentsByStudent($registrationNumber);

        // Get latest invoice
        $invoices = $this->invoiceModel->getInvoicesByStudent($registrationNumber);
        $latestInvoice = !empty($invoices) ? $invoices[0] : null;

        // Calculate payment breakdown
        $totalPaid = 0;
        $paidPayments = [];
        $pendingPayments = [];

        foreach ($payments as $payment) {
            if ($payment['status'] === 'paid') {
                $totalPaid += (float) $payment['amount'];
                $paidPayments[] = $payment;
            } else {
                $pendingPayments[] = $payment;
            }
        }

        $data = [
            'title' => 'Contract - ' . $registrationNumber,
            'admission' => $admission,
            'installment' => $installment,
            'payments' => $payments,
            'paidPayments' => $paidPayments,
            'pendingPayments' => $pendingPayments,
            'latestInvoice' => $latestInvoice,
            'totalPaid' => $totalPaid,
            'remainingBalance' => (float) $installment['total_contract_amount'] - $totalPaid,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ];

        return view('Modules\Payment\Views\contracts\view', $data);
    }

    /**
     * Display contract print view
     *
     * @param string $registrationNumber
     * @return string
     */
    public function print(string $registrationNumber)
    {
        // Get admission details
        $admission = $this->admissionModel->getByRegistrationNumber($registrationNumber);

        if (!$admission) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Admission not found');
        }

        // Get installment
        $installment = $this->installmentModel->getWithDetails($registrationNumber);

        if (!$installment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Installment record not found');
        }

        // Get payments for this installment
        $payments = $this->paymentModel->getPaymentsByStudent($registrationNumber);

        // Filter only paid payments
        $paidPayments = array_filter($payments, function ($payment) {
            return $payment['status'] === 'paid';
        });

        $totalPaid = 0;
        foreach ($paidPayments as $payment) {
            $totalPaid += (float) $payment['amount'];
        }

        $data = [
            'title' => 'Contract Print - ' . $registrationNumber,
            'admission' => $admission,
            'installment' => $installment,
            'payments' => array_values($paidPayments),
            'totalPaid' => $totalPaid,
            'remainingBalance' => (float) $installment['total_contract_amount'] - $totalPaid,
            'printDate' => date('Y-m-d H:i:s')
        ];

        return view('Modules\Payment\Views\contracts\print', $data);
    }

    /**
     * List all contracts
     *
     * @return string
     */
    public function index()
    {
        $perPage = 10;
        $page = $this->request->getGet('page') ?? 1;
        $status = $this->request->getGet('status');
        $keyword = $this->request->getGet('search');

        $db = \Config\Database::connect();
        
        // Get latest installment ID per registration_number using subquery
        $subQuery = '(SELECT MAX(id) as max_id FROM installments GROUP BY registration_number)';
        
        $builder = $db->table('installments')
            ->select('installments.*, profiles.full_name, profiles.email, programs.title as program_title')
            ->join('admissions', 'admissions.registration_number = installments.registration_number')
            ->join('profiles', 'profiles.id = admissions.profile_id')
            ->join('programs', 'programs.id = admissions.program_id')
            ->where('installments.id IN ' . $subQuery, null, false);

        // Apply filters
        if ($status) {
            $builder->where('installments.status', $status);
        }

        if ($keyword) {
            $builder->groupStart()
                ->like('installments.registration_number', $keyword)
                ->orLike('profiles.full_name', $keyword)
                ->orLike('profiles.email', $keyword)
                ->groupEnd();
        }

        $total = $builder->countAllResults(false);
        $offset = ($page - 1) * $perPage;

        $contracts = $builder->orderBy('installments.id', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Contracts',
            'contracts' => $contracts,
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
            'totalPages' => ceil($total / $perPage),
            'status' => $status,
            'keyword' => $keyword,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ];

        return view('Modules\Payment\Views\contracts\index', $data);
    }
}
