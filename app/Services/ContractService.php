<?php

namespace App\Services;

use Modules\Payment\Models\InstallmentModel;
use Modules\Payment\Models\InvoiceModel;
use Modules\Program\Models\ProgramModel;

/**
 * Contract Service
 * 
 * Handles contract-related operations including updates when program changes
 */
class ContractService
{
    protected InstallmentModel $installmentModel;
    protected InvoiceModel $invoiceModel;
    protected ProgramModel $programModel;

    public function __construct()
    {
        $this->installmentModel = new InstallmentModel();
        $this->invoiceModel = new InvoiceModel();
        $this->programModel = new ProgramModel();
    }

    /**
     * Update contract when program is changed
     * 
     * This method handles:
     * 1. Recalculating total contract amount based on new program fees
     * 2. Updating installment record
     * 3. Handling existing invoices (cancel unpaid, adjust if needed)
     * 4. Creating new invoices if necessary
     * 
     * @param string $registrationNumber The admission's registration number
     * @param string $oldProgramId The previous program ID
     * @param string $newProgramId The new program ID
     * @return array Result with success status and messages
     */
    public function updateContractForProgramChange(
        string $registrationNumber,
        string $oldProgramId,
        string $newProgramId
    ): array {
        $result = [
            'success' => true,
            'messages' => [],
            'installment_updated' => false,
            'invoices_cancelled' => 0,
            'invoices_created' => 0,
        ];

        // If same program, no changes needed
        if ($oldProgramId === $newProgramId) {
            $result['messages'][] = 'Program unchanged, no contract updates needed.';
            return $result;
        }

        // Get old and new program details
        $oldProgram = $this->programModel->find($oldProgramId);
        $newProgram = $this->programModel->find($newProgramId);

        if (!$newProgram) {
            return [
                'success' => false,
                'messages' => ['New program not found.'],
            ];
        }

        // Get existing installment
        $installment = $this->installmentModel->getByRegistrationNumber($registrationNumber);
        
        if (!$installment) {
            // No installment exists, create one
            $this->createNewContract($registrationNumber, $newProgram);
            $result['messages'][] = 'Created new contract for ' . $newProgram['title'];
            $result['installment_updated'] = true;
            return $result;
        }

        // Calculate new contract amount
        $newContractAmount = $this->calculateContractAmount($newProgram);
        $oldContractAmount = (float) $installment['total_contract_amount'];
        $totalPaid = (float) $installment['total_paid'];

        // Check if there are payments already made
        if ($totalPaid > 0) {
            // Complex scenario: payments exist
            // We need to handle this carefully
            $result['messages'][] = "Warning: Payments already made ({$totalPaid}). Contract adjustment may be needed.";
            
            // If new amount is less than what's already paid, we have an overpayment
            if ($newContractAmount < $totalPaid) {
                $result['messages'][] = "Warning: New contract amount ({$newContractAmount}) is less than amount already paid ({$totalPaid}). Refund may be required.";
                // Still proceed with update, but flag it
            }
        }

        // Cancel unpaid invoices for old program
        $cancelledCount = $this->cancelUnpaidInvoices($registrationNumber);
        $result['invoices_cancelled'] = $cancelledCount;

        // Update installment with new contract amount
        $newRemainingBalance = $newContractAmount - $totalPaid;
        $newStatus = $this->determineStatus($newRemainingBalance, $totalPaid);

        $updateData = [
            'total_contract_amount' => $newContractAmount,
            'remaining_balance' => $newRemainingBalance,
            'status' => $newStatus,
        ];

        $this->installmentModel->update($installment['id'], $updateData);
        $result['installment_updated'] = true;
        $result['messages'][] = "Contract amount updated from {$oldContractAmount} to {$newContractAmount}";

        // Create new invoices for the new program (only for remaining balance)
        if ($newRemainingBalance > 0) {
            $newInvoices = $this->createInvoicesForProgram(
                $registrationNumber,
                $newProgram,
                $installment['id'],
                $totalPaid // amount already paid, so we create invoices for the difference
            );
            $result['invoices_created'] = $newInvoices ? 1 : 0;
            $result['messages'][] = "Created new invoice for remaining balance: {$newRemainingBalance}";
        }

        return $result;
    }

    /**
     * Calculate total contract amount from program
     * 
     * @param array $program Program data
     * @return float Total contract amount
     */
    public function calculateContractAmount(array $program): float
    {
        $regFee = (float) ($program['registration_fee'] ?? 0);
        $tuitionFee = (float) ($program['tuition_fee'] ?? 0);
        $discount = (float) ($program['discount'] ?? 0);

        $finalTuition = $tuitionFee * (1 - $discount / 100);

        return $regFee + $finalTuition;
    }

    /**
     * Create new contract (installment) for a program
     * 
     * @param string $registrationNumber
     * @param array $program
     * @return int|false Installment ID or false on failure
     */
    public function createNewContract(string $registrationNumber, array $program)
    {
        $totalAmount = $this->calculateContractAmount($program);
        $dueDate = date('Y-m-d', strtotime('+2 weeks'));

        return $this->installmentModel->createInstallment([
            'registration_number' => $registrationNumber,
            'total_contract_amount' => $totalAmount,
            'total_paid' => 0,
            'remaining_balance' => $totalAmount,
            'status' => 'unpaid',
            'due_date' => $dueDate,
        ]);
    }

    /**
     * Cancel all unpaid invoices for a registration
     * 
     * @param string $registrationNumber
     * @return int Number of invoices cancelled
     */
    protected function cancelUnpaidInvoices(string $registrationNumber): int
    {
        $unpaidInvoices = $this->invoiceModel
            ->where('registration_number', $registrationNumber)
            ->where('status', 'unpaid')
            ->findAll();

        $count = 0;
        foreach ($unpaidInvoices as $invoice) {
            $this->invoiceModel->update($invoice['id'], ['status' => 'cancelled']);
            $count++;
        }

        return $count;
    }

    /**
     * Create invoices for a program
     * 
     * @param string $registrationNumber
     * @param array $program
     * @param int $installmentId
     * @param float $amountAlreadyPaid Amount already paid (to adjust invoice)
     * @return int|false Invoice ID or false on failure
     */
    protected function createInvoicesForProgram(
        string $registrationNumber,
        array $program,
        int $installmentId,
        float $amountAlreadyPaid = 0
    ) {
        $regFee = (float) ($program['registration_fee'] ?? 0);
        $tuitionFee = (float) ($program['tuition_fee'] ?? 0);
        $discount = (float) ($program['discount'] ?? 0);
        $finalTuition = $tuitionFee * (1 - $discount / 100);
        $totalAmount = $regFee + $finalTuition;

        // Calculate remaining amount to invoice
        $remainingToInvoice = $totalAmount - $amountAlreadyPaid;

        if ($remainingToInvoice <= 0) {
            return null; // No invoice needed
        }

        $dueDate = date('Y-m-d', strtotime('+2 weeks'));

        // Create items array - adjust amounts based on what's already paid
        // If amount already paid covers registration fee, only charge tuition
        $items = [];

        if ($amountAlreadyPaid < $regFee) {
            // Registration fee not fully paid
            $remainingRegFee = $regFee - $amountAlreadyPaid;
            $items[] = [
                'description' => 'Registration Fee for ' . $program['title'],
                'amount' => $remainingRegFee,
                'type' => 'registration_fee'
            ];
            // Full tuition still needed
            $items[] = [
                'description' => 'Course Fee for ' . $program['title'],
                'amount' => $finalTuition,
                'type' => 'tuition_fee'
            ];
        } else {
            // Registration fee already paid, only charge remaining tuition
            $paidTowardsTuition = $amountAlreadyPaid - $regFee;
            $remainingTuition = $finalTuition - $paidTowardsTuition;
            
            if ($remainingTuition > 0) {
                $items[] = [
                    'description' => 'Course Fee for ' . $program['title'] . ' (Program Change)',
                    'amount' => $remainingTuition,
                    'type' => 'tuition_fee'
                ];
            }
        }

        // Recalculate total from items
        $invoiceAmount = array_sum(array_column($items, 'amount'));

        if ($invoiceAmount <= 0) {
            return null;
        }

        $invoiceData = [
            'registration_number' => $registrationNumber,
            'contract_number' => $registrationNumber,
            'installment_id' => $installmentId,
            'description' => 'Fees for ' . $program['title'] . ' (Program Change)',
            'amount' => $invoiceAmount,
            'due_date' => $dueDate,
            'invoice_type' => 'tuition_fee',
            'status' => 'unpaid',
            'items' => json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        ];

        return $this->invoiceModel->createInvoice($invoiceData);
    }

    /**
     * Determine installment status based on balance and payments
     * 
     * @param float $remainingBalance
     * @param float $totalPaid
     * @return string Status (unpaid, partial, paid)
     */
    protected function determineStatus(float $remainingBalance, float $totalPaid): string
    {
        if ($remainingBalance <= 0) {
            return 'paid';
        }
        if ($totalPaid > 0) {
            return 'partial';
        }
        return 'unpaid';
    }

    /**
     * Get contract summary for an admission
     * 
     * @param string $registrationNumber
     * @return array|null Contract summary or null if not found
     */
    public function getContractSummary(string $registrationNumber): ?array
    {
        $installment = $this->installmentModel->getWithDetails($registrationNumber);
        
        if (!$installment) {
            return null;
        }

        // Get related invoices
        $invoices = $this->invoiceModel->getInvoicesByStudent($registrationNumber);

        return [
            'installment' => $installment,
            'invoices' => $invoices,
            'total_invoices' => count($invoices),
            'unpaid_invoices' => count(array_filter($invoices, fn($i) => $i['status'] === 'unpaid')),
        ];
    }
}
