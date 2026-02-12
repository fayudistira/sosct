<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    .pending-header {
        background: linear-gradient(to right, #6c757d, #495057);
        color: white;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .pending-card {
        text-align: center;
        padding: 40px;
        border-radius: 10px;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .pending-icon {
        font-size: 4rem;
        color: #ffc107;
        margin-bottom: 20px;
    }
</style>

<div class="container-fluid">
    <div class="pending-header">
        <h3 class="mb-0">Financial Information</h3>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="pending-card">
                <div class="pending-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h4 class="mb-3">Pending Approval</h4>
                <p class="text-muted mb-4">
                    <?= esc($message) ?>
                </p>
                <hr class="my-4">
                <h5 class="mb-3">What happens next?</h5>
                <div class="text-start d-inline-block">
                    <p class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Our administration team will review your admission application
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Once approved, you will be assigned a registration number
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Your invoices and payment history will be available here
                    </p>
                    <p class="mb-4">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        You will receive a notification once your admission is approved
                    </p>
                </div>
                <hr class="my-4">
                <p class="text-muted small mb-3">
                    If you have any questions about your admission status, please contact our administration office.
                </p>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>