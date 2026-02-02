<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    .payment-header {
        background: linear-gradient(to right, #8B0000, #6B0000);
        color: white;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .btn-payment {
        background: linear-gradient(to right, #8B0000, #6B0000);
        color: white;
        border: none;
    }
    .btn-payment:hover {
        background: linear-gradient(to right, #6B0000, #8B0000);
        color: white;
    }
    .badge-paid { background-color: #28a745; }
    .badge-pending { background-color: #ffc107; }
    .badge-failed { background-color: #dc3545; }
    .badge-refunded { background-color: #6c757d; }
</style>

<div class="container-fluid">
    <div class="payment-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-0">Payments</h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?= base_url('payment/create') ?>" class="btn btn-light">
                    <i class="bi bi-plus-circle"></i> Add New Payment
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
    
    <!-- Search and Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="get" action="<?= base_url('payment') ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search..." value="<?= esc($keyword ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" <?= ($status ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="paid" <?= ($status ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="failed" <?= ($status ?? '') === 'failed' ? 'selected' : '' ?>>Failed</option>
                            <option value="refunded" <?= ($status ?? '') === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="method" class="form-select">
                            <option value="">All Methods</option>
                            <option value="cash" <?= ($method ?? '') === 'cash' ? 'selected' : '' ?>>Cash</option>
                            <option value="bank_transfer" <?= ($method ?? '') === 'bank_transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="start_date" class="form-control" value="<?= esc($start_date ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="end_date" class="form-control" value="<?= esc($end_date ?? '') ?>">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-payment w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Payments Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Document #</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($payments)): ?>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?= esc($payment['id']) ?></td>
                                    <td>
                                        <?= esc($payment['student']['full_name'] ?? 'N/A') ?><br>
                                        <small class="text-muted"><?= esc($payment['registration_number']) ?></small>
                                    </td>
                                    <td>$<?= number_format($payment['amount'], 2) ?></td>
                                    <td><?= ucwords(str_replace('_', ' ', $payment['payment_method'])) ?></td>
                                    <td><?= esc($payment['document_number']) ?></td>
                                    <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $payment['status'] ?>">
                                            <?= ucfirst($payment['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('payment/view/' . $payment['id']) ?>" 
                                           class="btn btn-sm btn-info">View</a>
                                        <a href="<?= base_url('payment/edit/' . $payment['id']) ?>" 
                                           class="btn btn-sm btn-warning">Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No payments found</td>
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
