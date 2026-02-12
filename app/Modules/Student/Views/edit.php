<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Edit Status Siswa</h4>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('student') ?>" class="btn btn-outline-dark-red">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="m-0 text-dark">
                    <i class="bi bi-person-check me-2"></i>Informasi Siswa
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Nama Lengkap</label>
                        <div class="form-control-plaintext fw-bold"><?= esc($profile['full_name'] ?? 'N/A') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">No. Siswa</label>
                        <div class="form-control-plaintext fw-bold"><?= esc($student['student_number']) ?></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Program</label>
                        <div class="form-control-plaintext"><?= esc($student['program_id'] ?? 'Belum ditugaskan') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Angkatan</label>
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
                                <option value="">-- Pilih Status --</option>
                                <option value="active" <?= $student['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
                                <option value="inactive" <?= $student['status'] === 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                                <option value="graduated" <?= $student['status'] === 'graduated' ? 'selected' : '' ?>>Lulus</option>
                                <option value="dropped" <?= $student['status'] === 'dropped' ? 'selected' : '' ?>>Keluar</option>
                                <option value="suspended" <?= $student['status'] === 'suspended' ? 'selected' : '' ?>>Ditangguhkan</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">IPK</label>
                            <input type="number" name="gpa" class="form-control" step="0.01" min="0" max="4" value="<?= $student['gpa'] ?? '' ?>" placeholder="contoh: 3.75">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total SKS</label>
                            <input type="number" name="total_credits" class="form-control" min="0" value="<?= $student['total_credits'] ?? '' ?>" placeholder="contoh: 120">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">IPK Kelulusan</label>
                            <input type="number" name="graduation_gpa" class="form-control" step="0.01" min="0" max="4" value="<?= $student['graduation_gpa'] ?? '' ?>" placeholder="contoh: 3.50">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Kelulusan</label>
                        <input type="date" name="graduation_date" class="form-control" value="<?= $student['graduation_date'] ?? '' ?>">
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= base_url('student') ?>" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="bi bi-pencil-square me-2"></i>Update Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>