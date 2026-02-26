<?php

namespace Modules\Admission\Controllers;

use App\Controllers\BaseController;
use Modules\Admission\Models\AdmissionModel;
use Modules\Program\Models\ProgramModel;
use Modules\Payment\Models\InvoiceModel;
use Modules\Payment\Models\InstallmentModel;
use Modules\Payment\Models\PaymentModel;

class SwitchProgramController extends BaseController
{
    protected $admissionModel;
    protected $programModel;
    protected $invoiceModel;
    protected $installmentModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->admissionModel = new AdmissionModel();
        $this->programModel = new ProgramModel();
        $this->invoiceModel = new InvoiceModel();
        $this->installmentModel = new InstallmentModel();
        $this->paymentModel = new PaymentModel();
    }

    /**
     * Show switch program form
     * 
     * @param int $id Admission ID
     * @return string
     */
    public function index(int $id): string
    {
        // Get admission details
        $admission = $this->admissionModel->getWithDetails($id);
        
        if (!$admission) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Admission not found');
        }

        // Get current installment/contract
        $installment = $this->installmentModel->getByRegistrationNumber($admission['registration_number']);
        
        // Get payments made so far
        $payments = [];
        if ($installment) {
            $payments = $this->paymentModel->where('installment_id', $installment['id'])
                ->where('status', 'paid')
                ->where('deleted_at', null)
                ->findAll();
        }

        // Get total paid
        $totalPaid = 0;
        foreach ($payments as $payment) {
            $totalPaid += (float) $payment['amount'];
        }

        // Get all available programs (excluding current program)
        $allPrograms = $this->programModel->where('status', 'active')->where('category', 'Paket')->findAll();
        
        // Filter out current program
        $availablePrograms = array_filter($allPrograms, function($prog) use ($admission) {
            return $prog['id'] != $admission['program_id'];
        });

        // Calculate financial summary
        $currentRegFee = (float) ($admission['registration_fee'] ?? 0);
        $currentTuitionFee = (float) ($admission['tuition_fee'] ?? 0);
        $currentTotal = $currentRegFee + $currentTuitionFee;

        $data = [
            'admission' => $admission,
            'installment' => $installment,
            'payments' => $payments,
            'totalPaid' => $totalPaid,
            'currentRegFee' => $currentRegFee,
            'currentTuitionFee' => $currentTuitionFee,
            'currentTotal' => $currentTotal,
            'availablePrograms' => array_values($availablePrograms),
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ];

        return view('Modules\Admission\Views\switch_program', $data);
    }

    /**
     * Process the program switch
     * 
     * @param int $id Admission ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function switch(int $id)
    {
        // Get admission
        $admission = $this->admissionModel->getWithDetails($id);
        
        if (!$admission) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Admission not found');
        }

        // Validate new program
        $newProgramId = $this->request->getPost('new_program_id');
        $switchReason = $this->request->getPost('switch_reason') ?? '';

        if (!$newProgramId) {
            return redirect()->back()->with('error', 'Please select a new program');
        }

        // Get new program details
        $newProgram = $this->programModel->find($newProgramId);
        
        if (!$newProgram) {
            return redirect()->back()->with('error', 'Selected program not found');
        }

        // Prevent switching to the same program
        if ($newProgramId == $admission['program_id']) {
            return redirect()->back()->with('error', 'You cannot switch to the same program');
        }

        // Get current installment
        $oldInstallment = $this->installmentModel->getByRegistrationNumber($admission['registration_number']);

        // Get total paid from old installment
        $totalPaid = $oldInstallment ? (float) $oldInstallment['total_paid'] : 0;

        // Get new program fees
        $newRegFee = (float) ($newProgram['registration_fee'] ?? 0);
        $newTuitionFee = (float) ($newProgram['tuition_fee'] ?? 0);
        $newTotal = $newRegFee + $newTuitionFee;

        // Calculate new remaining balance
        $newRemainingBalance = $newTotal - $totalPaid;

        // Determine new status
        $newStatus = 'unpaid';
        if ($newRemainingBalance <= 0) {
            $newStatus = 'paid';
        } elseif ($totalPaid > 0) {
            $newStatus = 'partial';
        }

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // 1. Cancel all unpaid invoices from old installment
            if ($oldInstallment) {
                $this->invoiceModel->where('installment_id', $oldInstallment['id'])
                    ->where('status', 'unpaid')
                    ->set(['status' => 'cancelled'])
                    ->update();

                // Update old installment to mark it as switched
                $this->installmentModel->update($oldInstallment['id'], [
                    'status' => 'switched',
                    'switch_date' => date('Y-m-d H:i:s'),
                    'switch_reason' => $switchReason
                ]);
            }

            // 2. Create new installment record
            $dueDate = date('Y-m-d', strtotime('+2 weeks'));
            
            $newInstallmentData = [
                'registration_number' => $admission['registration_number'],
                'total_contract_amount' => $newTotal,
                'total_paid' => $totalPaid,
                'remaining_balance' => $newRemainingBalance,
                'status' => $newStatus,
                'due_date' => $dueDate,
                'parent_installment_id' => $oldInstallment ? $oldInstallment['id'] : null,
                'switch_date' => date('Y-m-d H:i:s'),
                'switch_reason' => $switchReason
            ];

            $this->installmentModel->insert($newInstallmentData);
            $newInstallmentId = $this->installmentModel->insertID();

            // 3. Create new invoices for the new program
            $invoiceItems = [
                [
                    'description' => 'Biaya Pendaftaran Program ' . $newProgram['title'],
                    'amount' => $newRegFee,
                    'type' => 'registration_fee'
                ],
                [
                    'description' => 'Biaya Kursus ' . $newProgram['title'],
                    'amount' => $newTuitionFee,
                    'type' => 'tuition_fee'
                ]
            ];

            $invoiceData = [
                'registration_number' => $admission['registration_number'],
                'contract_number' => $admission['registration_number'],
                'installment_id' => $newInstallmentId,
                'description' => 'Payment for ' . $newProgram['title'] . ' Program (Program Switch)',
                'amount' => $newTotal,
                'due_date' => $dueDate,
                'invoice_type' => 'tuition_fee',
                'status' => 'unpaid',
                'items' => json_encode($invoiceItems, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ];

            $this->invoiceModel->createInvoice($invoiceData);

            // 4. Update admission record
            $this->admissionModel->update($id, [
                'program_id' => $newProgramId,
                'previous_program_id' => $admission['program_id'],
                'program_switch_count' => ($admission['program_switch_count'] ?? 0) + 1,
                'notes' => ($admission['notes'] ?? '') . "\n\n[" . date('Y-m-d H:i:s') . "] Program switched to: " . $newProgram['title'] . ". Reason: " . $switchReason
            ]);

            // 5. Complete transaction
            $db->transCommit();

            // 6. Create notification
            $this->notifyProgramSwitch($admission, $newProgram, $totalPaid, $newTotal);

            return redirect()->to('/admission/view/' . $id)->with('success', 
                'Program successfully switched to ' . $newProgram['title'] . '. ' .
                'Previous payments of Rp ' . number_format($totalPaid, 0, ',', '.') . ' have been credited. ' .
                'New remaining balance: Rp ' . number_format(max(0, $newRemainingBalance), 0, ',', '.')
            );

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', '[SwitchProgram] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to switch program: ' . $e->getMessage());
        }
    }

    /**
     * Get switch history for an admission
     * 
     * @param int $id Admission ID
     * @return array
     */
    public function getHistory(int $id): array
    {
        $admission = $this->admissionModel->getWithDetails($id);
        
        if (!$admission) {
            return [];
        }

        // Get all installments that are related to this registration number
        $db = \Config\Database::connect();
        
        $installments = $db->table('installments')
            ->select('installments.*, programs.title as program_title')
            ->join('admissions', 'admissions.registration_number = installments.registration_number')
            ->join('programs', 'programs.id = admissions.program_id', 'left')
            ->where('installments.registration_number', $admission['registration_number'])
            ->where('installments.switch_date IS NOT NULL')
            ->orderBy('installments.switch_date', 'DESC')
            ->get()
            ->getResultArray();

        return $installments;
    }

    /**
     * Send notification about program switch
     * 
     * @param array $admission
     * @param array $newProgram
     * @param float $credits
     * @param float $newTotal
     */
    private function notifyProgramSwitch(array $admission, array $newProgram, float $credits, float $newTotal): void
    {
        $notificationService = new \App\Services\NotificationService();
        
        $message = sprintf(
            'Student %s has switched from program %s to %s. Credits: Rp %s, New Total: Rp %s',
            $admission['full_name'],
            $admission['program_title'],
            $newProgram['title'],
            number_format($credits, 0, ',', '.'),
            number_format($newTotal, 0, ',', '.')
        );
        
        $notificationService->notifyAdmins(
            'program_switch',
            'Program Switched',
            $message,
            [
                'admission_id' => $admission['admission_id'],
                'registration_number' => $admission['registration_number'],
                'old_program' => $admission['program_title'],
                'new_program' => $newProgram['title']
            ]
        );
    }
}
