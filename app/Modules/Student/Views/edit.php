<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Edit Student Status</h4>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('student') ?>" class="btn btn-outline-dark-red">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="m-0 text-dark">
                    <i class="bi bi-person-check me-2"></i>Student Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Full Name</label>
                        <div class="form-control-plaintext fw-bold"><?= esc($profile['full_name'] ?? 'N/A') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Student Number</label>
                        <div class="form-control-plaintext fw-bold"><?= esc($student['student_number']) ?></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Program</label>
                        <div class="form-control-plaintext"><?= esc($student['program_id'] ?? 'Not assigned') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Batch</label>
                        <div class="form-control-plaintext fw-bold"><?= esc($student['batch']) ?></div>
                    </div>
                </div>

                <hr>

                <form action="<?= base_url('student/update/' . $student['id']) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="">-- Select Status --</option>
                                <option value="active" <?= $student['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $student['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                <option value="graduated" <?= $student['status'] === 'graduated' ? 'selected' : '' ?>>Graduated</option>
                                <option value="dropped" <?= $student['status'] === 'dropped' ? 'selected' : '' ?>>Dropped</option>
                                <option value="suspended" <?= $student['status'] === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">GPA</label>
                            <input type="number" name="gpa" class="form-control" step="0.01" min="0" max="4" value="<?= $student['gpa'] ?? '' ?>" placeholder="e.g., 3.75">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Credits</label>
                            <input type="number" name="total_credits" class="form-control" min="0" value="<?= $student['total_credits'] ?? '' ?>" placeholder="e.g., 120">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Graduation GPA</label>
                            <input type="number" name="graduation_gpa" class="form-control" step="0.01" min="0" max="4" value="<?= $student['graduation_gpa'] ?? '' ?>" placeholder="e.g., 3.50">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Graduation Date</label>
                        <input type="date" name="graduation_date" class="form-control" value="<?= $student['graduation_date'] ?? '' ?>">
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= base_url('student') ?>" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="bi bi-pencil-square me-2"></i>Update Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>