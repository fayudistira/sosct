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

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-people text-primary me-2"></i>
                    Test Admissions
                </h5>
                <p class="card-text text-muted">
                    Generate sample admission applications with profiles, invoices, and documents.
                </p>
                <form method="post" action="<?= base_url('settings/generate-test-data') ?>">
                    <input type="hidden" name="type" value="admissions">
                    <div class="mb-3">
                        <label for="count" class="form-label">Number of records:</label>
                        <select class="form-select" name="count" id="count">
                            <option value="5">5 records</option>
                            <option value="10" selected>10 records</option>
                            <option value="20">20 records</option>
                            <option value="50">50 records</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle me-1"></i>Generate
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-receipt text-success me-2"></i>
                    Test Invoices
                </h5>
                <p class="card-text text-muted">
                    Generate sample invoices with various statuses (unpaid, paid, partially_paid).
                </p>
                <form method="post" action="<?= base_url('settings/generate-test-data') ?>">
                    <input type="hidden" name="type" value="invoices">
                    <div class="mb-3">
                        <label for="count" class="form-label">Number of records:</label>
                        <select class="form-select" name="count" id="count">
                            <option value="5">5 records</option>
                            <option value="10" selected>10 records</option>
                            <option value="20">20 records</option>
                            <option value="50">50 records</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-plus-circle me-1"></i>Generate
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-currency-dollar text-warning me-2"></i>
                    Test Payments
                </h5>
                <p class="card-text text-muted">
                    Generate sample payments for existing invoices.
                </p>
                <form method="post" action="<?= base_url('settings/generate-test-data') ?>">
                    <input type="hidden" name="type" value="payments">
                    <div class="mb-3">
                        <label for="count" class="form-label">Number of records:</label>
                        <select class="form-select" name="count" id="count">
                            <option value="5">5 records</option>
                            <option value="10" selected>10 records</option>
                            <option value="20">20 records</option>
                            <option value="50">50 records</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-plus-circle me-1"></i>Generate
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <i class="bi bi-info-circle me-2"></i>About Test Data Generation
    </div>
    <div class="card-body">
        <h6>How it works:</h6>
        <ul>
            <li><strong>Admissions:</strong> Creates profiles, admissions, and invoices for existing programs</li>
            <li><strong>Invoices:</strong> Creates standalone invoices with various statuses</li>
            <li><strong>Payments:</strong> Creates payments linked to existing invoices</li>
        </ul>
        <div class="alert alert-info mb-0">
            <i class="bi bi-lightbulb me-2"></i>
            <strong>Tip:</strong> Use the Cleanup feature first to clear existing data before generating test data.
        </div>
    </div>
</div>
<?= $this->endSection() ?>