<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    .payment-header {
        background: linear-gradient(to right, #198754, #146c43);
        color: white;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .btn-payment {
        background: linear-gradient(to right, #198754, #146c43);
        color: white;
        border: none;
    }

    .btn-payment:hover {
        background: linear-gradient(to right, #146c43, #198754);
        color: white;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.85em;
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
        font-weight: 600;
        font-size: 1.1em;
    }

    .payment-card {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.2s;
    }

    .payment-card:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container-fluid">
    <div class="payment-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-0">My Payments</h3>
                <small>Registration #: <?= esc($registration_number) ?></small>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?= base_url('my/invoices') ?>" class="btn btn-light me-2">
                    <i class="bi bi-file-text"></i> My Invoices
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

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <!-- Search -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="get" action="<?= base_url('my/payments') ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search receipt number or payment method..."
                            value="<?= esc($keyword ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-payment w-100">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                    <?php if ($keyword): ?>
                        <div class="col-md-2">
                            <a href="<?= base_url('my/payments') ?>" class="btn btn-outline-secondary w-100">
                                Clear
                            </a>
                        </div>
                    <?php endif ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments List -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Receipt #</th>
                            <th>Invoice #</th>
                            <th>Payment Date</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($payments)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-credit-card fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">No payments found</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($payment['document_number']) ?></strong>
                                        <br>
                                        <small class="text-muted"><?= date('d M Y H:i', strtotime($payment['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <?php if ($payment['invoice_number'] && $payment['invoice_number'] != 'N/A'): ?>
                                            <a href="<?= base_url('my/invoices/' . $payment['invoice_id']) ?>">
                                                <?= esc($payment['invoice_number']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif ?>
                                    </td>
                                    <td><?= date('d F Y', strtotime($payment['payment_date'])) ?></td>
                                    <td>
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
                                    </td>
                                    <td class="amount-display text-success">
                                        Rp <?= number_format($payment['amount'], 0, ',', '.') ?>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= $payment['status'] ?>">
                                            <?= ucfirst($payment['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('my/payments/' . $payment['id']) ?>"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <?php if (!empty($pager)): ?>
        <div class="mt-3">
            <?= $pager->links('default', 'default_full') ?>
        </div>
    <?php endif ?>
</div>
<?= $this->endSection() ?>