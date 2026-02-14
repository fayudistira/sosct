<?php

namespace Modules\Settings\Controllers;

use App\Controllers\BaseController;
use Modules\Account\Models\ProfileModel;
use Modules\Admission\Models\AdmissionModel;
use Modules\Program\Models\ProgramModel;
use Modules\Payment\Models\InvoiceModel;
use Modules\Payment\Models\InstallmentModel;
use Modules\Payment\Models\PaymentModel;

class SettingsController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => 'Settings',
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user(),
            'tables' => $this->getTableStats(),
        ];

        return view('Modules\Settings\Views\index', $data);
    }

    public function cleanup()
    {
        $data = [
            'title' => 'Cleanup Test Data',
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user(),
            'tables' => $this->getTableStats(),
        ];

        return view('Modules\Settings\Views\cleanup', $data);
    }

    public function doCleanup()
    {
        $confirm = $this->request->getPost('confirm');

        if ($confirm !== 'DELETE') {
            return redirect()->to('settings/cleanup')
                ->with('error', 'You must type "DELETE" to confirm.');
        }

        // Disable foreign key checks, delete in order, then re-enable
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        $tables = [
            'notifications',
            'messages',
            'conversation_participants',
            'payments',
            'invoices',
            'installments',
            'students',
            'admissions',
            'profiles',
            'conversations',
        ];

        $results = [];
        foreach ($tables as $table) {
            try {
                $count = $this->db->table($table)->countAllResults();
                $this->db->table($table)->truncate();
                $results[$table] = ['success' => true, 'count' => $count];
            } catch (\Exception $e) {
                // Try delete as fallback if truncate fails
                try {
                    $count = $this->db->table($table)->countAllResults();
                    $this->db->table($table)->emptyTable();
                    $results[$table] = ['success' => true, 'count' => $count];
                } catch (\Exception $e2) {
                    $results[$table] = ['success' => false, 'error' => $e2->getMessage()];
                }
            }
        }

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        // Clear upload directories
        $uploadDirs = [
            FCPATH . 'uploads/profiles/photos',
            FCPATH . 'uploads/profiles/documents',
        ];

        foreach ($uploadDirs as $dir) {
            if (is_dir($dir)) {
                $files = glob($dir . '/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }
        }

        return redirect()->to('settings/cleanup')
            ->with('success', 'Test data has been cleared successfully.')
            ->with('results', $results);
    }

    public function testData()
    {
        $data = [
            'title' => 'Generate Test Data',
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user(),
            'stats' => $this->getTestDataStats(),
        ];

        return view('Modules\Settings\Views\test_data', $data);
    }

    public function generateTestData()
    {
        // Prevent running in production
        if (ENVIRONMENT === 'production') {
            return redirect()->to('settings/test-data')
                ->with('error', 'Test data generation is disabled in production.');
        }

        $type = $this->request->getPost('type');
        $count = (int)$this->request->getPost('count') ?: 10;

        // Validate count
        if ($count < 1 || $count > 100) {
            return redirect()->to('settings/test-data')
                ->with('error', 'Count must be between 1 and 100.');
        }

        try {
            $result = [];

            switch ($type) {
                case 'admissions':
                    $result = $this->generateTestAdmissions($count);
                    $message = "Generated: {$result['profiles']} profiles, {$result['admissions']} admissions, {$result['installments']} installments, {$result['invoices']} invoices";
                    break;

                case 'payments':
                    $result = $this->generateTestPayments();
                    $message = "Generated: {$result['payments']} payments, updated {$result['invoices_updated']} invoices, approved {$result['admissions_approved']} admissions";
                    break;

                case 'invoices':
                    throw new \Exception('Standalone invoice generation is deprecated. Use admissions to generate invoices.');

                default:
                    throw new \Exception('Unknown test data type: ' . $type);
            }

            return redirect()->to('settings/test-data')
                ->with('success', $message)
                ->with('result', $result);

        } catch (\Exception $e) {
            log_message('error', 'Test data generation failed: ' . $e->getMessage());
            return redirect()->to('settings/test-data')
                ->with('error', 'Failed to generate test data: ' . $e->getMessage());
        }
    }

    /**
     * Generate test admissions with full flow
     * 
     * @param int $count Number of admissions to generate
     * @return array Summary of created records
     */
    private function generateTestAdmissions(int $count = 10): array
    {
        // 1. Get active programs
        $programModel = new ProgramModel();
        $programs = $programModel->where('status', 'active')->findAll();

        if (empty($programs)) {
            throw new \Exception('No active programs found. Please seed programs first.');
        }

        // 2. Dummy data pools
        $firstNames = ['Ahmad', 'Siti', 'Budi', 'Dewi', 'Eko', 'Fitri', 'Gunawan', 'Hesti', 'Irfan', 'Julia', 'Krisna', 'Linda', 'Muhammad', 'Nadia', 'Oscar', 'Putu', 'Rina', 'Slamet', 'Tuti', 'Umar', 'Vina', 'Wawan', 'Yuni', 'Zaki'];
        $lastNames = ['Rizki', 'Nurhaliza', 'Santoso', 'Lestari', 'Prasetyo', 'Handayani', 'Wibowo', 'Rahayu', 'Hakim', 'Permata', 'Murti', 'Sari', 'Fadli', 'Putri', 'Wijaya', 'Kusuma', 'Hidayat', 'Saputra', 'Dewi', 'Rahman'];
        $cities = ['Jakarta', 'Surabaya', 'Bandung', 'Semarang', 'Yogyakarta', 'Malang', 'Medan', 'Makassar', 'Pare', 'Kediri', 'Blitar', 'Jember', 'Banyuwangi', 'Probolinggo', 'Pasuruan', 'Madiun', 'Solo', 'Cirebon', 'Bogor', 'Bekasi'];
        $religions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
        $provinces = ['Jawa Timur', 'Jawa Barat', 'Jawa Tengah', 'DKI Jakarta', 'DI Yogyakarta', 'Bali', 'Sumatera Utara', 'Sulawesi Selatan', 'Kalimantan Timur', 'Nusa Tenggara Barat'];

        $created = [
            'profiles' => 0,
            'admissions' => 0,
            'installments' => 0,
            'invoices' => 0,
        ];

        $this->db->transStart();

        try {
            foreach (range(1, $count) as $i) {
                // Randomly select program
                $program = $programs[array_rand($programs)];

                // Generate unique data
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $fullName = "$firstName $lastName";
                $timestamp = time() . $i;
                $email = "test{$timestamp}@example.com";
                $phone = '08' . rand(1000000000, 9999999999);
                $city = $cities[array_rand($cities)];
                $province = $provinces[array_rand($provinces)];

                // 1. Create Profile
                $profileModel = new ProfileModel();
                $profileData = [
                    'profile_number' => $profileModel->generateProfileNumber(),
                    'full_name' => $fullName,
                    'nickname' => $firstName,
                    'gender' => rand(0, 1) ? 'Male' : 'Female',
                    'email' => $email,
                    'phone' => $phone,
                    'place_of_birth' => $city,
                    'date_of_birth' => date('Y-m-d', strtotime('-' . rand(18, 35) . ' years')),
                    'religion' => $religions[array_rand($religions)],
                    'street_address' => "Jl. " . $firstName . " No. " . rand(1, 100),
                    'district' => 'Kecamatan ' . $city,
                    'regency' => 'Kota ' . $city,
                    'province' => $province,
                    'postal_code' => (string)rand(10000, 99999)
                ];

                if (!$profileModel->insert($profileData)) {
                    throw new \Exception('Failed to create profile: ' . json_encode($profileModel->errors()));
                }
                $profileId = $profileModel->insertID();
                $created['profiles']++;

                // 2. Generate registration number
                $year = date('Y');
                $lastReg = $this->db->table('admissions')
                    ->like('registration_number', "REG-{$year}-")
                    ->orderBy('id', 'DESC')
                    ->get()
                    ->getRowArray();
                $regNum = $lastReg ? (int)substr($lastReg['registration_number'], -5) + 1 : 1;
                $registrationNumber = sprintf("REG-%s-%05d", $year, $regNum);

                // 3. Create Admission
                $admissionModel = new AdmissionModel();
                $admissionData = [
                    'profile_id' => $profileId,
                    'program_id' => $program['id'],
                    'registration_number' => $registrationNumber,
                    'status' => 'pending',
                    'application_date' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')),
                    'source' => 'test_data'
                ];

                if (!$admissionModel->insert($admissionData)) {
                    throw new \Exception('Failed to create admission: ' . json_encode($admissionModel->errors()));
                }
                $created['admissions']++;

                // 4. Create Installment
                $regFee = (float)($program['registration_fee'] ?? 0);
                $tuitionFee = (float)($program['tuition_fee'] ?? 0);
                $totalAmount = $regFee + $tuitionFee;
                $dueDate = date('Y-m-d', strtotime('+2 weeks'));

                $installmentModel = new InstallmentModel();
                $installmentData = [
                    'registration_number' => $registrationNumber,
                    'total_contract_amount' => $totalAmount,
                    'total_paid' => 0,
                    'remaining_balance' => $totalAmount,
                    'status' => 'unpaid',
                    'due_date' => $dueDate
                ];

                $installmentId = $installmentModel->createInstallment($installmentData);
                if (!$installmentId) {
                    throw new \Exception('Failed to create installment: ' . json_encode($installmentModel->errors()));
                }
                $created['installments']++;

                // 5. Create Invoice
                if ($totalAmount > 0) {
                    $invoiceModel = new InvoiceModel();
                    $items = [
                        [
                            'description' => 'Biaya Pendaftaran Program ' . $program['title'],
                            'amount' => $regFee,
                            'type' => 'registration_fee'
                        ],
                        [
                            'description' => 'Biaya Kursus ' . $program['title'],
                            'amount' => $tuitionFee,
                            'type' => 'tuition_fee'
                        ]
                    ];

                    $invoiceData = [
                        'registration_number' => $registrationNumber,
                        'contract_number' => $registrationNumber,
                        'installment_id' => $installmentId,
                        'description' => 'Payment for ' . $program['title'] . ' Program',
                        'amount' => $totalAmount,
                        'due_date' => $dueDate,
                        'invoice_type' => 'tuition_fee',
                        'status' => 'unpaid',
                        'items' => json_encode($items, JSON_UNESCAPED_UNICODE)
                    ];

                    if (!$invoiceModel->createInvoice($invoiceData)) {
                        throw new \Exception('Failed to create invoice: ' . json_encode($invoiceModel->errors()));
                    }
                    $created['invoices']++;
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

            return $created;

        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Generate test payments for unpaid invoices
     * 
     * @return array Summary of created records
     */
    private function generateTestPayments(): array
    {
        $invoiceModel = new InvoiceModel();
        $paymentModel = new PaymentModel();
        $installmentModel = new InstallmentModel();
        $admissionModel = new AdmissionModel();

        // Get all unpaid/partially_paid invoices
        $invoices = $invoiceModel->whereIn('status', ['unpaid', 'partially_paid'])->findAll();

        if (empty($invoices)) {
            throw new \Exception('No unpaid invoices found. Generate test admissions first.');
        }

        $paymentMethods = ['cash', 'bank_transfer', 'e_wallet', 'credit_card'];

        $created = [
            'payments' => 0,
            'invoices_updated' => 0,
            'admissions_approved' => 0
        ];

        foreach ($invoices as $invoice) {
            $this->db->transStart();

            try {
                $installment = $installmentModel->find($invoice['installment_id']);
                $remainingBalance = $installment ? (float)$installment['remaining_balance'] : (float)$invoice['amount'];

                if ($remainingBalance <= 0) {
                    $this->db->transComplete();
                    continue;
                }

                // Random: full payment (70%) or partial payment (30%)
                $isFullPayment = rand(1, 10) <= 7;

                if ($isFullPayment) {
                    $paymentAmount = $remainingBalance;
                } else {
                    // Partial: 30-70% of remaining
                    $paymentAmount = $remainingBalance * (rand(30, 70) / 100);
                }

                // Create payment
                $paymentData = [
                    'registration_number' => $invoice['registration_number'],
                    'invoice_id' => $invoice['id'],
                    'installment_id' => $invoice['installment_id'],
                    'amount' => $paymentAmount,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'document_number' => 'DOC-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'payment_date' => date('Y-m-d H:i:s'),
                    'status' => 'paid', // Auto-approve test payments
                    'notes' => 'Test payment - ' . ($isFullPayment ? 'Full' : 'Partial') . ' payment'
                ];

                if (!$paymentModel->insert($paymentData)) {
                    throw new \Exception('Failed to create payment: ' . json_encode($paymentModel->errors()));
                }
                $created['payments']++;

                // Update installment totals
                if ($invoice['installment_id']) {
                    $installmentModel->updatePaymentTotal($invoice['installment_id']);
                }

                // Recalculate invoice status
                $invoiceModel->recalculateInvoiceStatus($invoice['id']);
                $created['invoices_updated']++;

                // Auto-approve admission if pending
                $admission = $admissionModel->where('registration_number', $invoice['registration_number'])->first();
                if ($admission && $admission['status'] === 'pending') {
                    $admissionModel->update($admission['id'], [
                        'status' => 'approved',
                        'reviewed_date' => date('Y-m-d H:i:s'),
                        'notes' => ($admission['notes'] ?? '') . "\n[" . date('Y-m-d H:i:s') . "] Auto-approved via test payment."
                    ]);
                    $created['admissions_approved']++;
                }

                $this->db->transComplete();

            } catch (\Exception $e) {
                $this->db->transRollback();
                log_message('error', 'Test payment generation failed for invoice ' . $invoice['id'] . ': ' . $e->getMessage());
            }
        }

        return $created;
    }

    /**
     * Get table statistics
     * 
     * @return array
     */
    private function getTableStats()
    {
        $tables = [
            'profiles',
            'admissions',
            'invoices',
            'payments',
            'students',
            'conversations',
            'messages',
        ];

        $stats = [];
        foreach ($tables as $table) {
            try {
                $stats[$table] = $this->db->table($table)->countAllResults();
            } catch (\Exception $e) {
                $stats[$table] = 0;
            }
        }

        return $stats;
    }

    /**
     * Get test data statistics for the view
     * 
     * @return array
     */
    private function getTestDataStats(): array
    {
        $programModel = new ProgramModel();
        $invoiceModel = new InvoiceModel();

        return [
            'active_programs' => $programModel->where('status', 'active')->countAllResults(),
            'unpaid_invoices' => $invoiceModel->whereIn('status', ['unpaid', 'partially_paid'])->countAllResults(),
        ];
    }
}
