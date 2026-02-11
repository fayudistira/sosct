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
</style>

<div class="container-fluid">
    <div class="invoice-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-0">Invoices</h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?= base_url('invoice/create') ?>" class="btn btn-light">
                    <i class="bi bi-plus-circle"></i> Create Invoice
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

    <!-- Search and Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="get" action="<?= base_url('invoice') ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search..." value="<?= esc($keyword ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="unpaid" <?= ($status ?? '') === 'unpaid' ? 'selected' : '' ?>>Belum Dibayar</option>
                            <option value="paid" <?= ($status ?? '') === 'paid' ? 'selected' : '' ?>>Lunas</option>
                            <option value="partially_paid" <?= ($status ?? '') === 'partially_paid' ? 'selected' : '' ?>>Dibayar Sebagian</option>
                            <option value="cancelled" <?= ($status ?? '') === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                            <option value="expired" <?= ($status ?? '') === 'expired' ? 'selected' : '' ?>>Expired</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="registration_fee" <?= ($type ?? '') === 'registration_fee' ? 'selected' : '' ?>>Biaya Registrasi</option>
                            <option value="tuition_fee" <?= ($type ?? '') === 'tuition_fee' ? 'selected' : '' ?>>Biaya Program</option>
                            <option value="miscellaneous_fee" <?= ($type ?? '') === 'miscellaneous_fee' ? 'selected' : '' ?>>Biaya Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="start_date" class="form-control" value="<?= esc($start_date ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="end_date" class="form-control" value="<?= esc($end_date ?? '') ?>">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-invoice w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Student</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($invoices)): ?>
                            <?php foreach ($invoices as $invoice): ?>
                                <tr>
                                    <td><?= esc($invoice['invoice_number']) ?></td>
                                    <td>
                                        <?= esc($invoice['student']['full_name'] ?? 'N/A') ?><br>
                                        <small class="text-muted"><?= esc($invoice['registration_number']) ?></small>
                                    </td>
                                    <td><?= ucwords(str_replace('_', ' ', $invoice['invoice_type'])) ?></td>
                                    <td>Rp <?= number_format($invoice['amount'], 0, ',', '.') ?></td>
                                    <td><?= date('M d, Y', strtotime($invoice['due_date'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?php
                                                                if ($invoice['status'] === 'paid') echo 'success';
                                                                elseif ($invoice['status'] === 'partially_paid') echo 'info';
                                                                elseif ($invoice['status'] === 'unpaid') echo 'warning';
                                                                elseif ($invoice['status'] === 'expired') echo 'danger';
                                                                else echo 'secondary';
                                                                ?>">
                                            <?= str_replace('_', ' ', ucfirst($invoice['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('invoice/view/' . $invoice['id']) ?>"
                                            class="btn btn-sm btn-info" title="View Details"><i class="bi bi-eye"></i></a>
                                        <a href="<?= base_url('invoice/pdf/' . $invoice['id']) ?>"
                                            class="btn btn-sm btn-danger" target="_blank" title="Download PDF"><i class="bi bi-file-pdf"></i></a>

                                        <?php if ($invoice['status'] === 'unpaid' || $invoice['status'] === 'expired'): ?>
                                            <a href="<?= base_url('invoice/cancel/' . $invoice['id']) ?>"
                                                class="btn btn-sm btn-secondary" title="Cancel Invoice"
                                                onclick="return confirm('Are you sure you want to cancel this invoice? This action cannot be undone.')">
                                                <i class="bi bi-x-circle"></i>
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-light disabled" title="Locked: Payment in Progress/Completed">
                                                <i class="bi bi-lock-fill"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No invoices found</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

            <?php if (isset($pager)): ?>
                <div class="mt-3">
                    <?= $pager->links() ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>