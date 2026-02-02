<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    .report-header {
        background: linear-gradient(to right, #8B0000, #6B0000);
        color: white;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .overdue-badge {
        background-color: #dc3545;
        color: white;
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 0.85em;
    }
</style>

<div class="container-fluid">
    <div class="report-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-0">Overdue Invoices Report</h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?= base_url('payment/reports/export?type=overdue') ?>" class="btn btn-light">
                    <i class="bi bi-download"></i> Export CSV
                </a>
            </div>
        </div>
    </div>
    
    <!-- Summary Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h6 class="text-muted">Total Overdue Invoices</h6>
                    <h3><?= count($invoices) ?></h3>
                </div>
                <div class="col-md-4">
                    <h6 class="text-muted">Total Amount Overdue</h6>
                    <h3>$<?= number_format(array_sum(array_column($invoices, 'amount')), 2) ?></h3>
                </div>
                <div class="col-md-4">
                    <h6 class="text-muted">Average Days Overdue</h6>
                    <h3>
                        <?php 
                        $totalDays = 0;
                        foreach ($invoices as $invoice) {
                            $dueDate = new DateTime($invoice['due_date']);
                            $today = new DateTime();
                            $totalDays += $today->diff($dueDate)->days;
                        }
                        echo count($invoices) > 0 ? round($totalDays / count($invoices)) : 0;
                        ?> days
                    </h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Overdue Invoices Table -->
    <div class="card">
        <div class="card-header" style="background-color: #8B0000; color: white;">
            <h5 class="mb-0">Overdue Invoice Details</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($invoices)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Student</th>
                                <th>Email</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Days Overdue</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoices as $invoice): ?>
                                <?php
                                $dueDate = new DateTime($invoice['due_date']);
                                $today = new DateTime();
                                $daysOverdue = $today->diff($dueDate)->days;
                                ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('invoice/view/' . $invoice['id']) ?>">
                                            <?= esc($invoice['invoice_number']) ?>
                                        </a>
                                    </td>
                                    <td><?= esc($invoice['full_name']) ?></td>
                                    <td><?= esc($invoice['email']) ?></td>
                                    <td>$<?= number_format($invoice['amount'], 2) ?></td>
                                    <td><?= date('M d, Y', strtotime($invoice['due_date'])) ?></td>
                                    <td>
                                        <span class="overdue-badge"><?= $daysOverdue ?> days</span>
                                    </td>
                                    <td><?= ucwords(str_replace('_', ' ', $invoice['invoice_type'])) ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> No overdue invoices found. All payments are up to date!
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
