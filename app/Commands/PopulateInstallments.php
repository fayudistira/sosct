<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Modules\Payment\Models\InstallmentModel;
use Modules\Payment\Models\InvoiceModel;
use Modules\Payment\Models\PaymentModel;
use Modules\Admission\Models\AdmissionModel;

class PopulateInstallments extends BaseCommand
{
    protected $group = 'Database';
    protected $name = 'installments:populate';
    protected $description = 'Create installment records for existing admissions';
    protected $usage = 'php spark installments:populate';

    public function run(array $params)
    {
        CLI::write('Starting installment population...', 'yellow');

        $admissionModel = new AdmissionModel();
        $invoiceModel = new InvoiceModel();
        $paymentModel = new PaymentModel();
        $installmentModel = new InstallmentModel();

        // Get all admissions
        $admissions = $admissionModel->findAll();

        $created = 0;
        $skipped = 0;

        foreach ($admissions as $admission) {
            $regNumber = $admission['registration_number'];

            // Check if installment already exists
            $existingInstallment = $installmentModel->getByRegistrationNumber($regNumber);
            if ($existingInstallment) {
                CLI::write("Skipping {$regNumber} - installment already exists", 'yellow');
                $skipped++;
                continue;
            }

            // Get all invoices for this admission
            $invoices = $invoiceModel->where('registration_number', $regNumber)
                ->where('deleted_at', null)
                ->findAll();

            if (empty($invoices)) {
                CLI::write("Skipping {$regNumber} - no invoices found", 'yellow');
                $skipped++;
                continue;
            }

            // Calculate total contract amount from invoices
            $totalContract = 0;
            foreach ($invoices as $invoice) {
                // Only count non-cancelled invoices for contract total
                if ($invoice['status'] !== 'cancelled') {
                    $totalContract += (float) $invoice['amount'];
                }
            }

            // Calculate total paid from payments
            $payments = $paymentModel->where('registration_number', $regNumber)
                ->where('status', 'paid')
                ->where('deleted_at', null)
                ->findAll();

            $totalPaid = 0;
            foreach ($payments as $payment) {
                $totalPaid += (float) $payment['amount'];
            }

            // Determine due date (use earliest invoice due date)
            $dueDate = null;
            foreach ($invoices as $invoice) {
                if ($invoice['status'] !== 'cancelled') {
                    if ($dueDate === null || strtotime($invoice['due_date']) < strtotime($dueDate)) {
                        $dueDate = $invoice['due_date'];
                    }
                }
            }

            // Determine status
            $remainingBalance = $totalContract - $totalPaid;
            if ($remainingBalance <= 0) {
                $status = 'paid';
            } elseif ($totalPaid > 0) {
                $status = 'partial';
            } else {
                $status = 'unpaid';
            }

            // Create installment record
            $installmentData = [
                'registration_number' => $regNumber,
                'total_contract_amount' => $totalContract,
                'total_paid' => $totalPaid,
                'remaining_balance' => $remainingBalance,
                'status' => $status,
                'due_date' => $dueDate
            ];

            if ($installmentModel->createInstallment($installmentData)) {
                $installmentId = $installmentModel->insertID();
                CLI::write("Created installment {$regNumber} - Contract: {$totalContract}, Paid: {$totalPaid}, Balance: {$remainingBalance}", 'green');

                // Update invoices with installment_id
                foreach ($invoices as $invoice) {
                    if ($invoice['status'] !== 'cancelled') {
                        $invoiceModel->update($invoice['id'], [
                            'installment_id' => $installmentId,
                            'contract_number' => $regNumber
                        ]);
                    }
                }

                // Update payments with installment_id
                foreach ($payments as $payment) {
                    $paymentModel->update($payment['id'], [
                        'installment_id' => $installmentId
                    ]);
                }

                $created++;
            } else {
                CLI::write("Failed to create installment for {$regNumber}: " . json_encode($installmentModel->errors()), 'red');
                $skipped++;
            }
        }

        CLI::write("Done! Created: {$created}, Skipped: {$skipped}", 'green');
    }
}
