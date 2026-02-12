<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    .receipt-header {
        background: linear-gradient(to right, #198754, #146c43);
        color: white;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .receipt-box {
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

    .status-paid {
        background-color: #198754;
        color: white;
    }

    .status-pending {
        background-color: #ffc107;
        color: #212529;
    }

    .status-failed {
        background-color: #dc3545;
        color: white;
    }

    .status-refunded {
        background-color: #6c757d;
        color: white;
    }

    .amount-display {
        font-size: 1.3em;
        font-weight: 600;
    }

    .receipt-border {
        border: 2px dashed #dee2e6;
        border-radius: 5px;
        padding: 20px;
    }

    .company-info {
        text-align: center;
        border-bottom: 2px solid #8B0000;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .receipt-total {
        background: #f8f9fa;
        border-radius: 5px;
        padding: 15px;
        margin-top: 20px;
    }
</style>

<div class="container-fluid">
    <div class="receipt-header">
        <div class="row">
            <div class="col-md-8">
                <h3 class="mb-0">Payment Receipt</h3>
                <small><?= esc($payment['document_number']) ?></small>
            </div>
            <div class="col-md-4 text-end">
                <a href="<?= base_url('my/payments') ?>" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Back to Payments
                </a>
                <button onclick="window.print()" class="btn btn-outline-light ms-2">
                    <i class="bi bi-printer"></i> Print
                </button>
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
        <div class="col-md-8 offset-md-2">
            <!-- Receipt -->
            <div class="receipt-box">
                <div class="receipt-border">
                    <div class="company-info">
                        <h4 class="mb-1" style="color: #8B0000;">FEECS</h4>
                        <p class="mb-0 text-muted">Foreign Language Education & Cultural School</p>
                        <p class="small text-muted">Payment Receipt</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Receipt Number</strong></p>
                            <p class="fs-5"><?= esc($payment['document_number']) ?></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1"><strong>Status</strong></p>
                            <p>
                                <span class="status-badge status-<?= $payment['status'] ?>">
                                    <?= ucfirst($payment['status']) ?>
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Payment Date</strong></p>
                            <p><?= date('d F Y', strtotime($payment['payment_date'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Payment Method</strong></p>
                            <p>
                                <?php
                                $methodIcons = [
                                    'cash' => 'bi-cash',
                                    'bank_transfer' => 'bi-bank',
                                    'mobile_banking' => 'bi-phone',
                                    'credit_card' => 'bi-credit-card'
                                ];
                                $methodLabels = [
                                    'cash' => 'Cash',
                                    'bank_transfer' => 'Bank Transfer',
                                    'mobile_banking' => 'Mobile Banking',
                                    'credit_card' => 'Credit Card'
                                ];
                                ?>
                                <i class="bi <?= $methodIcons[$payment['payment_method']] ?? 'bi-credit-card' ?>"></i>
                                <?= $methodLabels[$payment['payment_method']] ?? esc($payment['payment_method']) ?>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Registration Number</strong></p>
                            <p><?= esc($payment['registration_number']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Invoice Reference</strong></p>
                            <?php if ($payment['invoice_number'] && $payment['invoice_number'] != 'N/A'): ?>
                                <p>
                                    <a href="<?= base_url('my/invoices/' . $payment['invoice_id']) ?>">
                                        <?= esc($payment['invoice_number']) ?>
                                    </a>
                                </p>
                            <?php else: ?>
                                <p class="text-muted">-</p>
                            <?php endif ?>
                        </div>
                    </div>

                    <?php if (!empty($payment['invoice_description'])): ?>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <p class="mb-1"><strong>Description</strong></p>
                                <p><?= esc($payment['invoice_description']) ?></p>
                            </div>
                        </div>
                    <?php endif ?>

                    <div class="receipt-total">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-0"><strong>Amount Paid</strong></p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p class="mb-0 amount-display text-success">
                                    Rp <?= number_format($payment['amount'], 0, ',', '.') ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php if ($payment['status'] == 'refunded' && !empty($payment['refund_reason'])): ?>
                        <div class="alert alert-warning mt-3">
                            <strong>Refund Reason:</strong> <?= esc($payment['refund_reason']) ?>
                            <?php if (!empty($payment['refund_date'])): ?>
                                <br><small>Refunded on: <?= date('d F Y', strtotime($payment['refund_date'])) ?></small>
                            <?php endif ?>
                        </div>
                    <?php endif ?>

                    <?php if ($payment['status'] == 'failed' && !empty($payment['failure_reason'])): ?>
                        <div class="alert alert-danger mt-3">
                            <strong>Failure Reason:</strong> <?= esc($payment['failure_reason']) ?>
                        </div>
                    <?php endif ?>

                    <div class="text-center mt-4">
                        <p class="text-muted small mb-0">Generated on <?= date('d F Y H:i', strtotime($payment['created_at'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Info Sidebar -->
    <div class="row mt-4">
        <div class="col-md-8 offset-md-2">
            <div class="receipt-box">
                <h5 class="mb-3">Student Information</h5>
                <?php if ($student): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Name</strong></p>
                            <p><?= esc($student['full_name']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Email</strong></p>
                            <p><?= esc($student['email']) ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Phone</strong></p>
                            <p><?= esc($student['phone']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Program</strong></p>
                            <p><?= esc($student['program_title']) ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Student information not available</p>
                <?php endif ?>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="row mt-4">
        <div class="col-md-8 offset-md-2 text-center">
            <a href="<?= base_url('my/payments') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to My Payments
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>