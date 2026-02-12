<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-award me-2"></i>Promote to Student</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    You are activating the student account for <strong><?= esc($admission['full_name']) ?></strong>.
                    This will create a user login and an active student record.
                </div>

                <form action="<?= base_url('admission/process_promotion/' . $admission['admission_id']) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Admission Info</label>
                        <div class="list-group">
                            <div class="list-group-item">
                                <small class="text-muted">Applicant Name</small>
                                <div class="fw-bold"><?= esc($admission['full_name']) ?></div>
                            </div>
                            <div class="list-group-item">
                                <small class="text-muted">Program</small>
                                <div class="fw-bold"><?= esc($admission['program_title']) ?></div>
                            </div>
                            <div class="list-group-item">
                                <small class="text-muted">Email</small>
                                <div><?= esc($admission['email']) ?></div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Student Account Credentials</h6>

                    <div class="alert alert-warning">
                        <i class="bi bi-lightbulb me-2"></i>
                        <strong>Auto-generated credentials:</strong> The username and password will be automatically generated from the student's profile data.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Username (Auto-generated)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" value="<?= esc($admission['citizen_id'] ?? 'N/A') ?>" readonly>
                        </div>
                        <small class="text-muted">Username will be set to the Citizen ID</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password (Auto-generated)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-key"></i></span>
                            <input type="text" class="form-control" value="<?= esc($admission['phone'] ?? 'N/A') ?>" readonly>
                        </div>
                        <small class="text-muted">Password will be set to the Phone Number. Student can change it after login.</small>
                    </div>

                    <?php if (empty($admission['citizen_id']) || empty($admission['phone'])): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Missing Information:</strong> Please ensure the student's profile has both Citizen ID and Phone Number before promoting.
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= base_url('admission/view/' . $admission['admission_id']) ?>" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4" <?= (empty($admission['citizen_id']) || empty($admission['phone'])) ? 'disabled' : '' ?>>
                            <i class="bi bi-check-circle me-2"></i>Promote Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>