<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Profil Siswa</h4>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('student') ?>" class="btn btn-outline-dark-red">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<div class="row">
    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Profile Card -->
        <div class="card shadow-sm mb-4 text-center">
            <div class="card-body">
                <?php if (!empty($student['photo'])): ?>
                    <img src="<?= base_url('uploads/' . $student['photo']) ?>" alt="Photo" class="rounded-circle mb-3 border border-3 border-light shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                <?php else: ?>
                    <div class="rounded-circle bg-secondary text-white mx-auto mb-3 d-flex align-items-center justify-content-center h1" style="width: 120px; height: 120px;">
                        <?= substr($student['full_name'], 0, 1) ?>
                    </div>
                <?php endif; ?>

                <h5 class="fw-bold mb-1"><?= esc($student['full_name']) ?></h5>
                <p class="text-muted mb-2"><?= esc($student['student_number']) ?></p>

                <div class="mb-3">
                    <?php
                    $statusClass = match ($student['status']) {
                        'active' => 'success',
                        'inactive' => 'secondary',
                        'graduated' => 'primary',
                        'dropped' => 'danger',
                        'suspended' => 'warning',
                        default => 'secondary'
                    };
                    ?>
                    <span class="badge bg-<?= $statusClass ?> px-3 py-2 rounded-pill"><?= ucfirst($student['status']) ?></span>
                </div>
            </div>
        </div>

        <!-- Academic Info -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-mortarboard me-2"></i>Informasi Akademik</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Program</small>
                    <div class="fw-medium"><?= esc($student['program_title']) ?></div>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Angkatan</small>
                    <div class="fw-medium"><?= esc($student['batch']) ?></div>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Tanggal Pendaftaran</small>
                    <div class="fw-medium"><?= date('d F Y', strtotime($student['enrollment_date'])) ?></div>
                </div>
                <!-- Future: GPA/Credits -->
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-md-8">
        <!-- Personal Info -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-person me-2"></i>Data Pribadi</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nama Lengkap</small>
                        <div><?= esc($student['full_name']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nama Panggilan</small>
                        <div><?= esc($student['nickname'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Jenis Kelamin</small>
                        <div><?= esc($student['gender'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">KTP</small>
                        <div><?= esc($student['citizen_id'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Tempat Lahir</small>
                        <div><?= esc($student['place_of_birth'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Tanggal Lahir</small>
                        <div><?= esc($student['date_of_birth'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Agama</small>
                        <div><?= esc($student['religion'] ?? '-') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact & Address -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-geo-alt me-2"></i>Kontak & Alamat</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Telepon</small>
                        <div><?= esc($student['phone'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Email</small>
                        <div><?= esc($student['profile_email'] ?? '-') ?></div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Alamat</small>
                        <div>
                            <?= esc($student['street_address'] ?? '') ?><br>
                            <?= esc($student['district'] ?? '') ?>, <?= esc($student['regency'] ?? '') ?><br>
                            <?= esc($student['province'] ?? '') ?> <?= esc($student['postal_code'] ?? '') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Family -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-people me-2"></i>Keluarga & Darurat</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nama Ayah</small>
                        <div><?= esc($student['father_name'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nama Ibu</small>
                        <div><?= esc($student['mother_name'] ?? '-') ?></div>
                    </div>
                    <div class="col-12">
                        <hr class="my-2">
                    </div>
                    <div class="col-md-12">
                        <strong class="text-primary d-block mb-2">Kontak Darurat</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Nama</small>
                        <div><?= esc($student['emergency_contact_name'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Hubungan</small>
                        <div><?= esc($student['emergency_contact_relation'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Telepon</small>
                        <div><?= esc($student['emergency_contact_phone'] ?? '-') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>