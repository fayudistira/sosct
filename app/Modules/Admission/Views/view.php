<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <a href="<?= base_url('admission') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
            <a href="<?= base_url('admission/edit/' . $admission['id']) ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Personal Information -->
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Registration Number:</strong><br>
                            <span class="badge bg-primary fs-6"><?= esc($admission['registration_number']) ?></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong><br>
                            <?php
                            $badgeClass = match($admission['status']) {
                                'pending' => 'bg-warning',
                                'approved' => 'bg-success',
                                'rejected' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                            ?>
                            <span class="badge <?= $badgeClass ?> fs-6"><?= ucfirst($admission['status']) ?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Full Name:</strong><br>
                            <?= esc($admission['full_name']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Nickname:</strong><br>
                            <?= esc($admission['nickname'] ?? '-') ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Gender:</strong><br>
                            <?= esc($admission['gender']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Date of Birth:</strong><br>
                            <?= date('M d, Y', strtotime($admission['date_of_birth'])) ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Place of Birth:</strong><br>
                            <?= esc($admission['place_of_birth']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Religion:</strong><br>
                            <?= esc($admission['religion']) ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Citizen ID:</strong><br>
                            <?= esc($admission['citizen_id'] ?? '-') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Phone:</strong><br>
                            <?= esc($admission['phone']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong><br>
                            <?= esc($admission['email']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Address</h5>
                </div>
                <div class="card-body">
                    <p><strong>Street Address:</strong><br><?= esc($admission['street_address']) ?></p>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>District:</strong> <?= esc($admission['district']) ?><br>
                            <strong>Regency/City:</strong> <?= esc($admission['regency']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Province:</strong> <?= esc($admission['province']) ?><br>
                            <strong>Postal Code:</strong> <?= esc($admission['postal_code'] ?? '-') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Emergency Contact</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Name:</strong><br>
                            <?= esc($admission['emergency_contact_name']) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Phone:</strong><br>
                            <?= esc($admission['emergency_contact_phone']) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Relationship:</strong><br>
                            <?= esc($admission['emergency_contact_relation']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Family Information -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Family Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Father's Name:</strong><br>
                            <?= esc($admission['father_name']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Mother's Name:</strong><br>
                            <?= esc($admission['mother_name']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course & Notes -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Course & Additional Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Desired Course:</strong><br><?= esc($admission['course']) ?></p>
                    <p><strong>Application Date:</strong><br><?= date('M d, Y', strtotime($admission['application_date'])) ?></p>
                    <?php if (!empty($admission['notes'])): ?>
                        <p><strong>Additional Notes:</strong><br><?= nl2br(esc($admission['notes'])) ?></p>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <!-- Photo and Documents -->
        <div class="col-md-4">
            <!-- Profile Photo -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Profile Photo</h5>
                </div>
                <div class="card-body text-center">
                    <?php if (!empty($admission['photo'])): ?>
                        <img src="<?= base_url('writable/uploads/admissions/photos/' . $admission['photo']) ?>" 
                             alt="Profile Photo" 
                             class="img-fluid rounded"
                             style="max-height: 300px;">
                    <?php else: ?>
                        <p class="text-muted">No photo uploaded</p>
                    <?php endif ?>
                </div>
            </div>

            <!-- Supporting Documents -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Supporting Documents</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($admission['documents'])): ?>
                        <ul class="list-group">
                            <?php foreach ($admission['documents'] as $index => $doc): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Document <?= $index + 1 ?>
                                    <a href="<?= base_url('admission/download/' . $admission['id'] . '/' . $doc) ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No documents uploaded</p>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
