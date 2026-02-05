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

                    <h6 class="fw-bold mb-3">Create Student Account</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" required placeholder="Used for login">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Initial Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required minlength="8" placeholder="Min. 8 characters">
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= base_url('admission/view/' . $admission['admission_id']) ?>" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Promote Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
