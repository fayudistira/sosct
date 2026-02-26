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
    .stat-card {
        border-left: 4px solid #8B0000;
    }
    .btn-export {
        background: linear-gradient(to right, #8B0000, #6B0000);
        color: white;
        border: none;
    }
</style>

<div class="container-fluid">
    <div class="report-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-0">Revenue Report</h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?= base_url('payment/reports/export?type=revenue&start_date=' . $start_date . '&end_date=' . $end_date) ?>" 
                   class="btn btn-light">
                    <i class="bi bi-download"></i> Export CSV
                </a>
            </div>
        </div>
    </div>
    
    <!-- Date Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="get" action="<?= base_url('payment/reports/revenue') ?>">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="<?= esc($start_date) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="<?= esc($end_date) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-export w-100">Apply Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="text-muted">Total Revenue</h6>
                    <h3 class="mb-0">Rp <?= number_format($stats['total_revenue'], 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="text-muted">Completed Payments</h6>
                    <h3 class="mb-0"><?= $stats['completed_count'] ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="text-muted">Pending Payments</h6>
                    <h3 class="mb-0"><?= $stats['pending_count'] ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="text-muted">Overdue Invoices</h6>
                    <h3 class="mb-0"><?= $stats['overdue_count'] ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Revenue Breakdown -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="background-color: #8B0000; color: white;">
                    <h5 class="mb-0">Revenue by Payment Method</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Method</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($revenueByMethod as $method => $amount): ?>
                                <tr>
                                    <td><?= ucwords(str_replace('_', ' ', $method)) ?></td>
                                    <td class="text-end">Rp <?= number_format($amount, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="background-color: #8B0000; color: white;">
                    <h5 class="mb-0">Revenue by Invoice Type</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($revenueByType as $type => $amount): ?>
                                <tr>
                                    <td><?= ucwords(str_replace('_', ' ', $type)) ?></td>
                                    <td class="text-end">Rp <?= number_format($amount, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Monthly Trend -->
    <div class="card mb-4">
        <div class="card-header" style="background-color: #8B0000; color: white;">
            <h5 class="mb-0">Monthly Revenue Trend (<?= date('Y') ?>)</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th class="text-end">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monthlyTrend as $trend): ?>
                        <tr>
                            <td><?= $trend['month'] ?></td>
                            <td class="text-end">Rp <?= number_format($trend['revenue'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Detailed Payments -->
    <div class="card">
        <div class="card-header" style="background-color: #8B0000; color: white;">
            <h5 class="mb-0">Payment Details</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Method</th>
                            <th>Keterangan</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                                <td><?= esc($payment['registration_number']) ?></td>
                                <td><?= ucwords(str_replace('_', ' ', $payment['payment_method'])) ?></td>
                                <td><?= esc($payment['notes'] ?? '-') ?></td>
                                <td class="text-end">Rp <?= number_format($payment['amount'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
