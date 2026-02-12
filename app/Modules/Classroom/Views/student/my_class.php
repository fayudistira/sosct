<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    .class-header {
        background: linear-gradient(to right, #0d6efd, #0a58ca);
        color: white;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .class-box {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 20px;
        background: white;
    }

    .schedule-card {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 10px;
        background: #f8f9fa;
    }

    .schedule-time {
        font-weight: 600;
        color: #0d6efd;
    }

    .schedule-subject {
        font-size: 1.1em;
        font-weight: 500;
    }

    .schedule-instructor {
        color: #6c757d;
    }

    .member-count {
        background: #e9ecef;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.9em;
    }
</style>

<div class="container-fluid">
    <div class="class-header">
        <div class="row">
            <div class="col-md-8">
                <h3 class="mb-0">My Class</h3>
                <small><?= esc($classroom['title']) ?></small>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="<?= base_url('dashboard') ?>" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
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

    <div class="row">
        <div class="col-md-8">
            <!-- Class Information -->
            <div class="class-box mb-4">
                <h5 class="mb-3">Class Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Class Name</strong></p>
                        <p class="fs-5"><?= esc($classroom['title']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Batch</strong></p>
                        <p><?= esc($classroom['batch'] ?? '-') ?></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Grade Level</strong></p>
                        <p><?= esc($classroom['grade'] ?? '-') ?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Program</strong></p>
                        <p><?= esc($classroom['program'] ?? '-') ?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Status</strong></p>
                        <p>
                            <span class="badge bg-success">
                                <?= ucfirst($classroom['status']) ?>
                            </span>
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Start Date</strong></p>
                        <p><?= $classroom['start_date'] ? date('d F Y', strtotime($classroom['start_date'])) : '-' ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>End Date</strong></p>
                        <p><?= $classroom['end_date'] ? date('d F Y', strtotime($classroom['end_date'])) : '-' ?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <span class="member-count">
                            <i class="bi bi-people"></i>
                            <?= count(json_decode($classroom['members'] ?? '[]', true)) ?> Students Enrolled
                        </span>
                    </div>
                </div>
            </div>

            <!-- Schedule -->
            <div class="class-box">
                <h5 class="mb-3">Class Schedule</h5>
                <?php if (!empty($classroom['schedule'])): ?>
                    <?php foreach ($classroom['schedule'] as $subject => $details): ?>
                        <div class="schedule-card">
                            <div class="row">
                                <div class="col-md-3">
                                    <p class="schedule-time mb-0">
                                        <i class="bi bi-clock"></i>
                                        <?= esc($details['time'] ?? 'TBD') ?>
                                    </p>
                                </div>
                                <div class="col-md-5">
                                    <p class="schedule-subject mb-0">
                                        <i class="bi bi-book"></i>
                                        <?= esc($subject) ?>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p class="schedule-instructor mb-0">
                                        <i class="bi bi-person"></i>
                                        <?= esc($details['instructor'] ?? 'TBA') ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        No schedule has been defined for this class yet.
                    </div>
                <?php endif ?>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Student Info -->
            <div class="class-box mb-4">
                <h5 class="mb-3">My Information</h5>
                <?php if ($student): ?>
                    <p class="mb-1"><strong>Name</strong></p>
                    <p><?= esc($student['full_name']) ?></p>

                    <p class="mb-1"><strong>Registration #</strong></p>
                    <p><?= esc($student['registration_number']) ?></p>

                    <p class="mb-1"><strong>Email</strong></p>
                    <p><?= esc($student['email']) ?></p>

                    <p class="mb-1"><strong>Phone</strong></p>
                    <p><?= esc($student['phone']) ?></p>

                    <hr>

                    <p class="mb-1"><strong>Program</strong></p>
                    <p><?= esc($student['program_title']) ?></p>
                <?php else: ?>
                    <p class="text-muted">Student information not available</p>
                <?php endif ?>
            </div>

            <!-- Quick Links -->
            <div class="class-box">
                <h5 class="mb-3">Quick Links</h5>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('my/invoices') ?>" class="btn btn-outline-primary">
                        <i class="bi bi-receipt"></i> My Invoices
                    </a>
                    <a href="<?= base_url('my/payments') ?>" class="btn btn-outline-success">
                        <i class="bi bi-credit-card"></i> My Payments
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>