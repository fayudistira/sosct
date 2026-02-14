<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Generate Test Data</h4>
        <p class="text-muted mb-0">Create sample data for testing purposes</p>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('settings') ?>" class="btn btn-outline-dark-red">
            <i class="bi bi-arrow-left me-1"></i> Back to Settings
        </a>
    </div>
</div>

<?php if (session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>
        <?= session('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>

<?php if (session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-x-circle me-2"></i>
        <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>

<?php if (session('result')): ?>
<div class="card mb-4 border-success">
    <div class="card-header bg-success text-white">
        <i class="bi bi-check-circle me-2"></i>Generation Summary
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <?php foreach (session('result') as $key => $value): ?>
            <li class="list-group-item d-flex justify-content-between">
                <span><?= ucfirst(str_replace('_', ' ', $key)) ?></span>
                <strong class="text-success"><?= $value ?></strong>
            </li>
            <?php endforeach ?>
        </ul>
        <div class="mt-3">
            <a href="<?= base_url('admission') ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-eye me-1"></i>View Admissions
            </a>
            <a href="<?= base_url('payment') ?>" class="btn btn-outline-success btn-sm">
                <i class="bi bi-eye me-1"></i>View Payments
            </a>
            <a href="<?= base_url('invoice') ?>" class="btn btn-outline-info btn-sm">
                <i class="bi bi-eye me-1"></i>View Invoices
            </a>
        </div>
    </div>
</div>
<?php endif ?>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary bg-opacity-10 border-primary">
            <div class="card-body text-center">
                <h3 class="text-primary mb-0"><?= $stats['active_programs'] ?? 0 ?></h3>
                <small class="text-muted">Active Programs</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning bg-opacity-10 border-warning">
            <div class="card-body text-center">
                <h3 class="text-warning mb-0"><?= $stats['unpaid_invoices'] ?? 0 ?></h3>
                <small class="text-muted">Unpaid Invoices</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-people me-2"></i>
                Test Admissions
            </div>
            <div class="card-body">
                <p class="card-text text-muted">
                    Generate sample admission applications with complete flow:
                    <ul class="mt-2">
                        <li>Profile with Indonesian dummy data</li>
                        <li>Admission with registration number</li>
                        <li>Installment record for tracking</li>
                        <li>Invoice with fee breakdown</li>
                    </ul>
                </p>
                <form method="post" action="<?= base_url('settings/generate-test-data') ?>">
                    <input type="hidden" name="type" value="admissions">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="count" class="form-label">Number of records:</label>
                        <select class="form-select" name="count" id="count">
                            <option value="5">5 records</option>
                            <option value="10" selected>10 records</option>
                            <option value="20">20 records</option>
                            <option value="50">50 records</option>
                        </select>
                    </div>
                    <?php if (($stats['active_programs'] ?? 0) == 0): ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            No active programs found. Please seed programs first.
                        </div>
                    <?php else: ?>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-1"></i>Generate Admissions
                        </button>
                    <?php endif ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <i class="bi bi-currency-dollar me-2"></i>
                Test Payments
            </div>
            <div class="card-body">
                <p class="card-text text-muted">
                    Generate sample payments for existing unpaid invoices:
                    <ul class="mt-2">
                        <li>Full payment (70% chance) or partial (30%)</li>
                        <li>Updates installment totals</li>
                        <li>Recalculates invoice status</li>
                        <li>Auto-approves pending admissions</li>
                    </ul>
                </p>
                <form method="post" action="<?= base_url('settings/generate-test-data') ?>">
                    <input type="hidden" name="type" value="payments">
                    <?= csrf_field() ?>
                    <?php if (($stats['unpaid_invoices'] ?? 0) == 0): ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            No unpaid invoices found. Generate test admissions first.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong><?= $stats['unpaid_invoices'] ?></strong> unpaid invoices ready for payment.
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-cash me-1"></i>Generate Payments
                        </button>
                    <?php endif ?>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Card -->
<div class="card mt-2">
    <div class="card-header">
        <i class="bi bi-lightning me-2"></i>Quick Actions
    </div>
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2">
            <form method="post" action="<?= base_url('settings/generate-test-data') ?>" class="d-inline">
                <input type="hidden" name="type" value="admissions">
                <input type="hidden" name="count" value="10">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline-primary btn-sm" <?= ($stats['active_programs'] ?? 0) == 0 ? 'disabled' : '' ?>>
                    <i class="bi bi-plus me-1"></i>Quick: 10 Admissions
                </button>
            </form>
            <form method="post" action="<?= base_url('settings/generate-test-data') ?>" class="d-inline">
                <input type="hidden" name="type" value="payments">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline-success btn-sm" <?= ($stats['unpaid_invoices'] ?? 0) == 0 ? 'disabled' : '' ?>>
                    <i class="bi bi-cash me-1"></i>Pay All Invoices
                </button>
            </form>
            <a href="<?= base_url('settings/cleanup') ?>" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-trash me-1"></i>Cleanup All Data
            </a>
        </div>
    </div>
</div>

<!-- Info Card -->
<div class="card mt-4">
    <div class="card-header">
        <i class="bi bi-info-circle me-2"></i>About Test Data Generation
    </div>
    <div class="card-body">
        <h6>How it works:</h6>
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary"><i class="bi bi-people me-2"></i>Admissions</h6>
                <ul>
                    <li>Creates profiles with Indonesian names and addresses</li>
                    <li>Generates unique registration numbers (REG-YYYY-XXXXX)</li>
                    <li>Creates installment records for payment tracking</li>
                    <li>Creates invoices with registration + tuition fee items</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6 class="text-success"><i class="bi bi-cash me-2"></i>Payments</h6>
                <ul>
                    <li>Creates payments for all unpaid invoices</li>
                    <li>70% full payment, 30% partial payment</li>
                    <li>Auto-approves pending admissions</li>
                    <li>Updates installment and invoice status</li>
                </ul>
            </div>
        </div>
        <div class="alert alert-warning mb-0 mt-3">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Warning:</strong> This feature is disabled in production environment. Test data should only be generated in development.
        </div>
    </div>
</div>
<?= $this->endSection() ?>
