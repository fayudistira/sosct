<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    .invoice-header {
        background: linear-gradient(to right, #8B0000, #6B0000);
        color: white;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .btn-invoice {
        background: linear-gradient(to right, #8B0000, #6B0000);
        color: white;
        border: none;
    }

    .btn-invoice:hover {
        background: linear-gradient(to right, #6B0000, #8B0000);
        color: white;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.85em;
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

    .amount-display {
        font-weight: 600;
        font-size: 1.1em;
    }
</style>

<div class="container-fluid">
    <div class="invoice-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-0">My Invoices</h3>
                <small>Registration #: <?= esc($registration_number) ?></small>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?= base_url('my/payments') ?>" class="btn btn-light me-2">
                    <i class="bi bi-credit-card"></i> My Payments
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
            <form method="get" action="<?= base_url('my/invoices') ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search invoice number or description..."
                            value="<?= esc($keyword ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-invoice w-100">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                    <?php if ($keyword): ?>
                        <div class="col-md-2">
                            <a href="<?= base_url('my/invoices') ?>" class="btn btn-outline-secondary w-100">
                                Clear
                            </a>
                        </div>
                    <?php endif ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice #</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($invoices)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">No invoices found</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($invoices as $invoice): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($invoice['invoice_number']) ?></strong>
                                        <br>
                                        <small class="text-muted"><?= date('d M Y', strtotime($invoice['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <?= esc($invoice['description']) ?>
                                    </td>
                                    <td>
                                        <?php
                                        $typeLabels = [
                                            'registration_fee' => 'Registration',
                                            'tuition_fee' => 'Tuition',
                                            'miscellaneous_fee' => 'Misc'
                                        ];
                                        echo $typeLabels[$invoice['invoice_type']] ?? esc($invoice['invoice_type']);
                                        ?>
                                    </td>
                                    <td class="amount-display">
                                        Rp <?= number_format($invoice['amount'], 0, ',', '.') ?>
                                    </td>
                                    <td>
                                        <?php if ($invoice['total_paid'] > 0): ?>
                                            <span class="text-success">
                                                Rp <?= number_format($invoice['total_paid'], 0, ',', '.') ?>
                                            </span>
                                            <?php if ($invoice['payment_count'] > 1): ?>
                                                <br><small class="text-muted">(<?= $invoice['payment_count'] ?> payments)</small>
                                            <?php endif ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <?= date('d M Y', strtotime($invoice['due_date'])) ?>
                                        <?php if ($invoice['status'] == 'unpaid' && strtotime($invoice['due_date']) < time()): ?>
                                            <br><small class="text-danger">Overdue</small>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= $invoice['status'] ?>">
                                            <?= ucfirst(str_replace('_', ' ', $invoice['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('my/invoices/' . $invoice['id']) ?>"
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