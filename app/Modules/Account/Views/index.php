<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h1 class="h3 mb-0"><?= esc($title) ?></h1>
        </div>
    </div>

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!$profile): ?>
        <!-- No Profile - Show Create Button -->
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card text-center">
                    <div class="card-body py-5">
                        <i class="bi bi-person-plus-fill text-muted" style="font-size: 4rem;"></i>
                        <h3 class="mt-3">Complete Your Profile</h3>
                        <p class="text-muted">You haven't created your profile yet. Please complete your profile information to continue.</p>
                        <a href="<?= base_url('account/create') ?>" class="btn btn-primary btn-lg mt-3">
                            <i class="bi bi-plus-circle me-2"></i>Create Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Profile Exists - Show Profile Details -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <?php if ($profile['photo']): ?>
                            <img src="<?= base_url('writable/uploads/' . $profile['photo']) ?>" 
                                 class="rounded-circle mb-3" 
                                 style="width: 150px; height: 150px; object-fit: cover;" 
                                 alt="Profile Photo">
                        <?php else: ?>
                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 150px; height: 150px;">
                                <i class="bi bi-person-fill text-white" style="font-size: 4rem;"></i>
                            </div>
                        <?php endif; ?>
                        <h4><?= esc($profile['full_name']) ?></h4>
                        <?php if ($profile['nickname']): ?>
                            <p class="text-muted">"<?= esc($profile['nickname']) ?>"</p>
                        <?php endif; ?>
                        <?php if ($profile['position']): ?>
                            <p class="mb-0"><strong><?= esc($profile['position']) ?></strong></p>
                        <?php endif; ?>
                        <?php if ($role): ?>
                            <span class="badge bg-primary"><?= esc(ucfirst($role)) ?></span>
                        <?php endif; ?>
                        <a href="<?= base_url('account/edit') ?>" class="btn btn-primary w-100 mt-3">
                            <i class="bi bi-pencil me-2"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Full Name</label>
                                <p class="mb-0"><?= esc($profile['full_name']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Gender</label>
                                <p class="mb-0"><?= esc($profile['gender']) ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Position</label>
                                <p class="mb-0"><?= esc($profile['position'] ?? '-') ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Role</label>
                                <p class="mb-0">
                                    <?php if ($role): ?>
                                        <span class="badge bg-primary"><?= esc(ucfirst($role)) ?></span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Place of Birth</label>
                                <p class="mb-0"><?= esc($profile['place_of_birth']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Date of Birth</label>
                                <p class="mb-0"><?= date('d F Y', strtotime($profile['date_of_birth'])) ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Religion</label>
                                <p class="mb-0"><?= esc($profile['religion']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Citizen ID</label>
                                <p class="mb-0"><?= esc($profile['citizen_id'] ?? '-') ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Phone</label>
                                <p class="mb-0"><?= esc($profile['phone']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Email</label>
                                <p class="mb-0"><?= esc($user->email) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Address</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small">Street Address</label>
                            <p class="mb-0"><?= esc($profile['street_address']) ?></p>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">District</label>
                                <p class="mb-0"><?= esc($profile['district']) ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Regency</label>
                                <p class="mb-0"><?= esc($profile['regency']) ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="text-muted small">Province</label>
                                <p class="mb-0"><?= esc($profile['province']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Postal Code</label>
                                <p class="mb-0"><?= esc($profile['postal_code'] ?? '-') ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Family Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Father's Name</label>
                                <p class="mb-0"><?= esc($profile['father_name']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Mother's Name</label>
                                <p class="mb-0"><?= esc($profile['mother_name']) ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="text-muted small">Emergency Contact</label>
                                <p class="mb-0"><?= esc($profile['emergency_contact_name']) ?></p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Contact Phone</label>
                                <p class="mb-0"><?= esc($profile['emergency_contact_phone']) ?></p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Relation</label>
                                <p class="mb-0"><?= esc($profile['emergency_contact_relation']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
