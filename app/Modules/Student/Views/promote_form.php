<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-bold">Tambahkan SIswa Baru dari Data Pendaftar</h4>
            <a href="<?= base_url('student') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="m-0 text-dark">
                    <i class="bi bi-person-badge me-2"></i>Data Pendaftar yang tersedia (approved)
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($admissions)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Tidak ada pendaftaran yang disetujui(approved) untuk ditambahkan. (Pendaftaran harus disetujui terlebih dahulu)
                    </div>
                <?php else: ?>
                    <form action="<?= base_url('student/do-promote') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">Pilih</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Program</th>
                                        <th>Status</th>
                                        <th>Tanggal Pendaftaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($admissions as $admission): ?>
                                        <tr>
                                            <td>
                                                <input type="radio" name="admission_id" value="<?= $admission['id'] ?>" class="form-check-input" required>
                                            </td>
                                            <td class="fw-bold"><?= esc($admission['full_name']) ?></td>
                                            <td><?= esc($admission['email']) ?></td>
                                            <td><?= esc($admission['program_title'] ?? 'N/A') ?></td>
                                            <td>
                                                <?php
                                                $statusClass = $admission['status'] === 'approved' ? 'success' : 'warning';
                                                ?>
                                                <span class="badge bg-<?= $statusClass ?>"><?= ucfirst($admission['status']) ?></span>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?= date('M d, Y', strtotime($admission['created_at'])) ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= base_url('student') ?>" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-2"></i>Lanjutkan Naik Tingkat
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>