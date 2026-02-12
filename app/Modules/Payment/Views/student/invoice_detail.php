<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    .invoice-header {
        background: linear-gradient(to right, #8B0000, #6B0000);
        color: white;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .invoice-box {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 20px;
        background: white;
    }

    .status-badge {
        padding: 5px 15px;
        border-radius: 4px;
        font-size: 0.9em;
        font-weight: 500;
    }

    .status-unpaid {
        background-color: #dc3545;
        color: white;
    }

    .status-paid {
        background-color: #198754;
        color: white;
    }

    .status-partially_paid {
        background-color: #ffc107;
        color: #212529;
    }

    .status-cancelled {
        background-color: #6c757d;
        color: white;
    }

    .status-expired {
        background-color: #fd7e14;
        color: white;
    }

    .payment-history {
        background: #f8f9fa;
        border-radius: 5px;
        padding: 15px;
    }

    .amount-display {
        font-size: 1.2em;
        font-weight: 600;
    }

    .table-payment {
        background: white;
        border-radius: 5px;
        overflow: hidden;
    }

    .table-payment th {
        background: #8B0000;
        color: white;
        font-weight: 500;
    }
</style>

<div class="container-fluid">
    <div class="invoice-header">
        <div class="row">
            <div class="col-md-8">
                <h3 class="mb-0">Invoice Details</h3>
                <small><?= esc($invoice['invoice_number']) ?></small>
            </div>
            <div class="col-md-4 text-end">
                <a href="<?= base_url('my/invoices') ?>" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Back to Invoices
                </a>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <div class="row">
        <div class="col-md-8">
            <!-- Invoice Details -->
            <div class="invoice-box mb-4">
                <h5 class="mb-3">Invoice Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Invoice Number</strong></p>
                        <p class="fs-5"><?= esc($invoice['invoice_number']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Status</strong></p>
                        <p>
                            <span class="status-badge status-<?= $invoice['status'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $invoice['status'])) ?>
                            </span>
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Description</strong></p>
                        <p><?= esc($invoice['description']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Invoice Type</strong></p>
                        <p>
                            <?php
                            $typeLabels = [
                                'registration_fee' => 'Registration Fee',
                                'tuition_fee' => 'Tuition Fee',
                                'miscellaneous_fee' => 'Miscellaneous Fee'
                            ];
                            echo $typeLabels[$invoice['invoice_type']] ?? esc($invoice['invoice_type']);
                            ?>
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Issue Date</strong></p>
                        <p><?= date('d F Y', strtotime($invoice['created_at'])) ?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Due Date</strong></p>
                        <p class="<?= $invoice['status'] == 'unpaid' && strtotime($invoice['due_date']) < time() ? 'text-danger' : '' ?>">
                            <?= date('d F Y', strtotime($invoice['due_date'])) ?>
                            <?php if ($invoice['status'] == 'unpaid' && strtotime($invoice['due_date']) < time()): ?>
                                <br><small>(Overdue)</small>
                            <?php endif ?>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Registration Number</strong></p>
                        <p><?= esc($invoice['registration_number']) ?></p>
                    </div>
                </div>

                <!-- Amount Summary -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr class="table-light">
                                <th>Invoice Amount</th>
                                <th>Total Paid</th>
                                <th>Remaining Balance</th>
                            </tr>
                            <tr>
                                <td class="amount-display">
                                    Rp <?= number_format($invoice['amount'], 0, ',', '.') ?>
                                </td>
                                <td class="amount-display text-success">
                                    Rp <?= number_format($invoice['total_paid'], 0, ',', '.') ?>
                                </td>
                                <td class="amount-display <?= ($invoice['amount'] - $invoice['total_paid']) > 0 ? 'text-danger' : 'text-success' ?>">
                                    Rp <?= number_format($invoice['amount'] - $invoice['total_paid'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Student Info -->
            <div class="invoice-box mb-4">
                <h5 class="mb-3">Student Information</h5>
                <?php if ($student): ?>
                    <p class="mb-1"><strong>Name</strong></p>
                    <p><?= esc($student['full_name']) ?></p>

                    <p class="mb-1"><strong>Email</strong></p>
                    <p><?= esc($student['email']) ?></p>

                    <p class="mb-1"><strong>Phone</strong></p>
                    <p><?= esc($student['phone']) ?></p>

                    <p class="mb-1"><strong>Program</strong></p>
                    <p><?= esc($student['program_title']) ?></p>
                <?php else: ?>
                    <p class="text-muted">Student information not available</p>
                <?php endif ?>
            </div>

            <!-- Progress Bar -->
            <div class="invoice-box mb-4">
                <h5 class="mb-3">Payment Progress</h5>
                <?php
                $percentPaid = ($invoice['amount'] > 0) ? round(($invoice['total_paid'] / $invoice['amount']) * 100, 1) : 0;
                ?>
                <div class="progress mb-2" style="height: 25px;">
                    <div class="progress-bar bg-success" role="progressbar"
                        style="width: <?= $percentPaid ?>%"
                        aria-valuenow="<?= $percentPaid ?>"
                        aria-valuemin="0"
                        aria-valuemax="100">
                        <?= $percentPaid ?>%
                    </div>
                </div>
                <p class="text-muted small text-center">
                    <?= $percentPaid >= 100 ? 'Fully Paid' : ($percentPaid > 0 ? 'Partially Paid' : 'Not Yet Paid') ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    <?php if (!empty($invoice['payments'])): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="payment-history">
                    <h5 class="mb-3">Payment History</h5>
                    <div class="table-responsive">
                        <table class="table table-payment mb-0">
                            <thead>
                                <tr>
                                    <th>Receipt #</th>
                                    <th>Payment Date</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($invoice['payments'] as $payment): ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($payment['document_number']) ?></strong>
                                        </td>
                                        <td><?= date('d F Y', strtotime($payment['payment_date'])) ?></td>
                                        <td>
                                            <?php
                                            $methodLabels = [
                                                'cash' => 'Cash',
                                                'bank_transfer' => 'Bank Transfer',
                                                'mobile_banking' => 'Mobile Banking',
                                                'credit_card' => 'Credit Card'
                                            ];
                                            echo $methodLabels[$payment['payment_method']] ?? esc($payment['payment_method']);
                                            ?>
                                        </td>
                                        <td class="amount-display text-success">
                                            Rp <?= number_format($payment['amount'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <span class="badge <?= $payment['status'] == 'paid' ? 'bg-success' : ($payment['status'] == 'pending' ? 'bg-warning' : 'bg-danger') ?>">
                                                <?= ucfirst($payment['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-12">
                <div class="payment-history text-center py-4">
                    <i class="bi bi-cash-stack fs-1 text-muted"></i>
                    <p class="text-muted mt-2 mb-0">No payments made yet</p>
                    <?php if ($invoice['status'] != 'paid'): ?>
                        <p class="text-muted">Please make a payment to settle this invoice</p>
                    <?php endif ?>
                </div>
            </div>
        </div>
    <?php endif ?>

    <!-- Back Button -->
    <div class="row mt-4">
        <div class="col-md-12">
            <a href="<?= base_url('my/invoices') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to My Invoices
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>