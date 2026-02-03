<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="hero-section py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">Apply for Admission</h1>
        <p class="lead">Start your journey with us by completing the application form below</p>
    </div>
</div>

<!-- Main Content -->
<div class="container py-5">
    <?php if (session('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>
    
    <?php if (session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i><?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>
    
    <!-- Selected Program Banner -->
    <?php if (isset($selectedProgram) && $selectedProgram): ?>
        <div class="alert alert-info border-0 shadow-sm mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="alert-heading mb-2">
                        <i class="bi bi-mortarboard me-2"></i>Applying for: <?= esc($selectedProgram['title']) ?>
                    </h5>
                    <p class="mb-0">
                        <strong>Category:</strong> <?= esc($selectedProgram['category'] ?? 'N/A') ?> | 
                        <strong>Tuition Fee:</strong> 
                        <?php if ($selectedProgram['discount'] > 0): ?>
                            <span class="text-decoration-line-through">Rp <?= number_format($selectedProgram['tuition_fee'], 0, ',', '.') ?></span>
                            <span class="text-success fw-bold">
                                Rp <?= number_format($selectedProgram['tuition_fee'] * (1 - $selectedProgram['discount'] / 100), 0, ',', '.') ?>
                            </span>
                            <span class="badge bg-success"><?= number_format($selectedProgram['discount'], 0) ?>% OFF</span>
                        <?php else: ?>
                            <span class="fw-bold">Rp <?= number_format($selectedProgram['tuition_fee'], 0, ',', '.') ?></span>
                        <?php endif ?>
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-2 mt-md-0">
                    <a href="<?= base_url('programs/' . $selectedProgram['id']) ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-info-circle me-1"></i>View Program Details
                    </a>
                </div>
            </div>
        </div>
    <?php endif ?>
    
    <form action="<?= base_url('apply/submit') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <!-- Personal Information -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-person me-2"></i>Personal Information</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?= old('full_name') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="nickname" class="form-label">Nickname</label>
                        <input type="text" class="form-control" id="nickname" name="nickname" value="<?= old('nickname') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male" <?= old('gender') === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= old('gender') === 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?= old('date_of_birth') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="place_of_birth" class="form-label">Place of Birth <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" value="<?= old('place_of_birth') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="religion" class="form-label">Religion <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="religion" name="religion" value="<?= old('religion') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="citizen_id" class="form-label">Citizen ID Number</label>
                        <input type="text" class="form-control" id="citizen_id" name="citizen_id" value="<?= old('citizen_id') ?>">
                        <small class="text-muted">Optional - Only if you have an ID card</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-telephone me-2"></i>Contact Information</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?= old('phone') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Address -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Address</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="street_address" class="form-label">Street Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="street_address" name="street_address" rows="2" required><?= old('street_address') ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="district" class="form-label">District/Sub-district <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="district" name="district" value="<?= old('district') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="regency" class="form-label">Regency/City <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="regency" name="regency" value="<?= old('regency') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="province" class="form-label">Province <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="province" name="province" value="<?= old('province') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="postal_code" class="form-label">Postal Code</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?= old('postal_code') ?>">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Emergency Contact -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Emergency Contact</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="emergency_contact_name" class="form-label">Contact Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" value="<?= old('emergency_contact_name') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="emergency_contact_phone" class="form-label">Contact Phone <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" value="<?= old('emergency_contact_phone') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="emergency_contact_relation" class="form-label">Relationship <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="emergency_contact_relation" name="emergency_contact_relation" value="<?= old('emergency_contact_relation') ?>" required>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Family Information -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-people me-2"></i>Family Information</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="father_name" class="form-label">Father's Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="father_name" name="father_name" value="<?= old('father_name') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="mother_name" class="form-label">Mother's Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="mother_name" name="mother_name" value="<?= old('mother_name') ?>" required>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Course Selection -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-mortarboard me-2"></i>Course Selection</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="course" class="form-label">Desired Course/Program <span class="text-danger">*</span></label>
                    <?php if (isset($selectedProgram) && $selectedProgram): ?>
                        <!-- Pre-selected program (read-only) -->
                        <input type="text" class="form-control" value="<?= esc($selectedProgram['title']) ?>" readonly style="background-color: #f8f9fa;">
                        <input type="hidden" name="program_id" value="<?= esc($selectedProgram['id']) ?>">
                        <input type="hidden" name="course" value="<?= esc($selectedProgram['title']) ?>">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            You are applying for this program. 
                            <a href="<?= base_url('apply') ?>">Click here</a> to choose a different program.
                        </small>
                    <?php else: ?>
                        <!-- Dropdown for program selection -->
                        <select class="form-select" id="course" name="course" required>
                            <option value="">Select a Program</option>
                            <?php foreach ($programs as $program): ?>
                                <option value="<?= esc($program['id']) ?>" data-title="<?= esc($program['title']) ?>" <?= old('course') === $program['id'] ? 'selected' : '' ?>>
                                    <?= esc($program['title']) ?>
                                    <?php if ($program['discount'] > 0): ?>
                                        (<?= number_format($program['discount'], 0) ?>% OFF)
                                    <?php endif ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Not sure which program? <a href="<?= base_url('programs') ?>" target="_blank">Browse our programs</a>
                        </small>
                    <?php endif ?>
                </div>
            </div>
        </div>
        
        <!-- File Uploads -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-file-earmark-arrow-up me-2"></i>File Uploads</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="photo" class="form-label">Profile Photo <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg,image/jpg,image/png" required>
                        <small class="text-muted">Accepted: JPG, JPEG, PNG. Max: 2MB</small>
                    </div>
                    <div class="col-md-6">
                        <label for="documents" class="form-label">Supporting Documents</label>
                        <input type="file" class="form-control" id="documents" name="documents[]" accept=".pdf,.doc,.docx" multiple>
                        <small class="text-muted">Accepted: PDF, DOC, DOCX. Max: 5MB per file</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Notes -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-chat-left-text me-2"></i>Additional Information</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Any additional information you'd like to share..."><?= old('notes') ?></textarea>
                </div>
            </div>
        </div>
        
        <div class="text-center">
            <button type="submit" class="btn btn-dark-red btn-lg px-5">
                <i class="bi bi-send me-2"></i>Submit Application
            </button>
            <a href="<?= base_url('/') ?>" class="btn btn-outline-dark-red btn-lg px-5 ms-2">
                <i class="bi bi-x-circle me-2"></i>Cancel
            </a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
