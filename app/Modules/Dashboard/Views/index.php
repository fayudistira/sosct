<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Dashboard Overview</h4>
        <p class="text-muted mb-0">Welcome back, <?= esc($user->username) ?>. Here's what's happening with your system today.</p>
    </div>
</div>

<!-- Admission & Program Details -->
<div class="row g-3 mb-4">
    <!-- Admission Statistics Card -->
    <?php if ($admissionStats): ?>
    <div class="col-lg-6">
        <div class="dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-earmark-text me-2"></i>Admission Statistics</span>
                <a href="<?= base_url('admission') ?>" class="btn btn-outline-dark-red btn-sm">View All</a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="stat-number text-primary"><?= $admissionStats['total'] ?></div>
                            <div class="stat-label">Total Applications</div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar" style="width: 100%; background-color: var(--dark-red);"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="stat-number" style="color: #FFA500;"><?= $admissionStats['pending'] ?></div>
                            <div class="stat-label">Pending</div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-warning" style="width: <?= $admissionStats['total'] > 0 ? ($admissionStats['pending'] / $admissionStats['total'] * 100) : 0 ?>%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="stat-number text-success"><?= $admissionStats['approved'] ?></div>
                            <div class="stat-label">Approved</div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: <?= $admissionStats['total'] > 0 ? ($admissionStats['approved'] / $admissionStats['total'] * 100) : 0 ?>%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="stat-number text-danger"><?= $admissionStats['rejected'] ?></div>
                            <div class="stat-label">Rejected</div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-danger" style="width: <?= $admissionStats['total'] > 0 ? ($admissionStats['rejected'] / $admissionStats['total'] * 100) : 0 ?>%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif ?>
    
    <!-- Program Statistics Card -->
    <?php if ($programStats && !empty($programStats)): ?>
    <div class="col-lg-6">
        <div class="dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-mortarboard me-2"></i>Programs by Category</span>
                <a href="<?= base_url('program') ?>" class="btn btn-outline-dark-red btn-sm">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover compact-table mb-0">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th class="text-center">Count</th>
                                <th width="40%">Distribution</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalPrograms = array_sum(array_column($programStats, 'total'));
                            foreach ($programStats as $stat): 
                                $percentage = $totalPrograms > 0 ? ($stat['total'] / $totalPrograms) * 100 : 0;
                            ?>
                            <tr>
                                <td class="fw-medium"><?= esc($stat['category']) ?></td>
                                <td class="text-center">
                                    <span class="badge" style="background-color: var(--dark-red);"><?= $stat['total'] ?></span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" style="width: <?= $percentage ?>%; background-color: var(--dark-red);" 
                                             title="<?= number_format($percentage, 1) ?>%"></div>
                                    </div>
                                    <small class="text-muted"><?= number_format($percentage, 1) ?>%</small>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                        <tfoot>
                            <tr style="background-color: var(--light-red);">
                                <th>Total Programs</th>
                                <th class="text-center">
                                    <span class="badge" style="background-color: var(--dark-red);"><?= $totalPrograms ?></span>
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif ?>
</div>

<!-- Payment Statistics Section -->
<?php if ($paymentStats): ?>
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-cash-coin me-2"></i>Payment Statistics (Year to Date)</span>
                <a href="<?= base_url('payment/reports/revenue') ?>" class="btn btn-outline-dark-red btn-sm">View Reports</a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="p-3 border rounded text-center">
                            <div class="stat-number" style="color: var(--dark-red);">Rp <?= number_format($paymentStats['total_revenue'], 0, ',', '.') ?></div>
                            <div class="stat-label">Total Revenue</div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar" style="width: 100%; background-color: var(--dark-red);"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded text-center">
                            <div class="stat-number text-success"><?= $paymentStats['completed_count'] ?></div>
                            <div class="stat-label">Completed Payments</div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded text-center">
                            <div class="stat-number" style="color: #FFA500;"><?= $paymentStats['pending_count'] ?></div>
                            <div class="stat-label">Pending Payments</div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-warning" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded text-center">
                            <div class="stat-number text-danger"><?= $paymentStats['overdue_count'] ?></div>
                            <div class="stat-label">Overdue Invoices</div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-danger" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($paymentStats['revenue_by_method'])): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="mb-3">Revenue by Payment Method</h6>
                        <div class="row g-3">
                            <?php foreach ($paymentStats['revenue_by_method'] as $method => $amount): ?>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-medium"><?= ucwords(str_replace('_', ' ', $method)) ?></span>
                                            <span class="badge" style="background-color: var(--dark-red);">Rp <?= number_format($amount, 0, ',', '.') ?></span>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar" style="width: <?= $paymentStats['total_revenue'] > 0 ? ($amount / $paymentStats['total_revenue'] * 100) : 0 ?>%; background-color: var(--dark-red);"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<?= $this->endSection() ?>
