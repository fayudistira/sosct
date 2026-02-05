<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Employee Details</h4>
        <p class="text-muted mb-0"><?= esc($staff['full_name']) ?> (<?= esc($staff['staff_number']) ?>)</p>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('admin/employee') ?>" class="btn btn-outline-dark-red me-2">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
        <a href="<?= base_url('admin/employee/edit/' . $staff['id']) ?>" class="btn btn-dark-red">
            <i class="bi bi-pencil me-1"></i> Edit Profile
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="dashboard-card mb-4 text-center py-4">
            <img src="<?= $staff['photo'] ? base_url('uploads/' . $staff['photo']) : 'https://ui-avatars.com/api/?name=' . urlencode($staff['full_name']) . '&background=8B0000&color=fff&size=120' ?>" 
                 class="rounded-circle mb-3 shadow-sm" alt="Photo" style="width: 120px; height: 120px; object-fit: cover;">
            <h5 class="fw-bold mb-1"><?= esc($staff['full_name']) ?></h5>
            <p class="text-muted mb-3"><?= esc($staff['position']) ?></p>
            <div class="d-flex justify-content-center gap-2 mb-3">
                <span class="badge bg-success"><?= ucfirst($staff['status']) ?></span>
                <span class="badge bg-info text-dark"><?= ucfirst($staff['employment_type']) ?></span>
            </div>
            <hr class="mx-4">
            <div class="px-4 text-start">
                <div class="mb-2">
                    <small class="text-muted d-block">Department</small>
                    <span class="fw-medium"><?= esc($staff['department'] ?? '-') ?></span>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Staff Number</small>
                    <span class="fw-medium"><?= esc($staff['staff_number']) ?></span>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Account Status</small>
                    <span class="badge <?= $staff['account_status'] ? 'bg-success' : 'bg-danger' ?>">
                        <?= $staff['account_status'] ? 'Active' : 'Inactive' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Employment Info -->
        <div class="dashboard-card mb-4">
            <div class="card-header"><i class="bi bi-briefcase me-2"></i>Employment Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Hire Date</small>
                        <span class="fw-medium"><?= date('d F Y', strtotime($staff['hire_date'])) ?></span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Salary</small>
                        <span class="fw-medium"><?= $staff['salary'] ? 'IDR ' . number_format($staff['salary'], 0, ',', '.') : '-' ?></span>
                    </div>
                    <?php if ($staff['status'] === 'resigned' || $staff['status'] === 'terminated'): ?>
                        <div class="col-md-6">
                            <small class="text-muted d-block"><?= ucfirst($staff['status']) ?> Date</small>
                            <span class="fw-medium"><?= $staff['termination_date'] ?></span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Reason</small>
                            <p class="mb-0"><?= esc($staff['termination_reason'] ?? 'N/A') ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Personal Info -->
        <div class="dashboard-card mb-4">
            <div class="card-header"><i class="bi bi-person me-2"></i>Personal Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Email</small>
                        <span class="fw-medium"><?= esc($staff['email']) ?></span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Phone</small>
                        <span class="fw-medium"><?= esc($staff['phone']) ?></span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Gender</small>
                        <span class="fw-medium"><?= esc($staff['gender']) ?></span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Birth</small>
                        <span class="fw-medium"><?= esc($staff['place_of_birth']) ?>, <?= date('d M Y', strtotime($staff['date_of_birth'])) ?></span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Address</small>
                        <span class="fw-medium"><?= esc($staff['street_address']) ?>, <?= esc($staff['district']) ?>, <?= esc($staff['regency']) ?>, <?= esc($staff['province']) ?> <?= esc($staff['postal_code']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents -->
        <?php if (!empty($staff['documents'])): ?>
            <div class="dashboard-card mb-4">
                <div class="card-header"><i class="bi bi-file-earmark-text me-2"></i>Documents</div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($staff['documents'] as $doc): ?>
                            <a href="<?= base_url('uploads/' . $doc) ?>" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-file-pdf me-2"></i><?= basename($doc) ?></span>
                                <i class="bi bi-download"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
