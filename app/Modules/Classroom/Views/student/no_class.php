<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    .class-header {
        background: linear-gradient(to right, #6c757d, #495057);
        color: white;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .no-class-card {
        text-align: center;
        padding: 40px;
        border-radius: 10px;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .no-class-icon {
        font-size: 4rem;
        color: #6c757d;
        margin-bottom: 20px;
    }
</style>

<div class="container-fluid">
    <div class="class-header">
        <h3 class="mb-0">My Class</h3>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="no-class-card">
                <div class="no-class-icon">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <h4 class="mb-3">No Class Assigned</h4>
                <p class="text-muted mb-4">
                    You have not been assigned to a class yet. Please contact the administration office for more information.
                </p>
                <hr class="my-4">
                <h5 class="mb-3">What happens next?</h5>
                <div class="text-start d-inline-block">
                    <p class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        The administration will assign you to a class
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        You'll be notified once your class is assigned
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Your class schedule will be available here
                    </p>
                    <p class="mb-4">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        You can view your class details and schedule
                    </p>
                </div>
                <hr class="my-4">
                <p class="text-muted small mb-3">
                    Your Registration Number: <strong><?= esc($registration_number) ?></strong>
                </p>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>