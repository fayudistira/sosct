<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Edit Admission</h4>
        <p class="text-muted mb-0">Update admission application information</p>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('admission') ?>" class="btn btn-outline-dark-red">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<?php if (session('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Validation Errors:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>

<form action="<?= base_url('admission/update/' . $admission['admission_id']) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- Personal Information -->
    <div class="dashboard-card mb-3">
        <div class="card-header">
            <i class="bi bi-person me-2"></i>Personal Information
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Registration Number</label>
                <input type="text" class="form-control form-control-sm" value="<?= esc($admission['registration_number']) ?>" readonly style="background-color: var(--light-red);">
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control form-control-sm" value="<?= old('full_name', $admission['full_name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nickname</label>
                    <input type="text" name="nickname" class="form-control form-control-sm" value="<?= old('nickname', $admission['nickname']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select form-select-sm" required>
                        <option value="Male" <?= old('gender', $admission['gender']) === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= old('gender', $admission['gender']) === 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" name="date_of_birth" class="form-control form-control-sm" value="<?= old('date_of_birth', $admission['date_of_birth']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Place of Birth <span class="text-danger">*</span></label>
                    <input type="text" name="place_of_birth" class="form-control form-control-sm" value="<?= old('place_of_birth', $admission['place_of_birth']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Religion <span class="text-danger">*</span></label>
                    <input type="text" name="religion" class="form-control form-control-sm" value="<?= old('religion', $admission['religion']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Citizen ID</label>
                    <input type="text" name="citizen_id" class="form-control form-control-sm" value="<?= old('citizen_id', $admission['citizen_id']) ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Contact -->
    <div class="dashboard-card mb-3">
        <div class="card-header">
            <i class="bi bi-telephone me-2"></i>Contact Information
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="tel" name="phone" class="form-control form-control-sm" value="<?= old('phone', $admission['phone']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control form-control-sm" value="<?= old('email', $admission['email']) ?>" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Address -->
    <div class="dashboard-card mb-3">
        <div class="card-header">
            <i class="bi bi-geo-alt me-2"></i>Address
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Street Address <span class="text-danger">*</span></label>
                    <textarea name="street_address" class="form-control form-control-sm" rows="2" required><?= old('street_address', $admission['street_address']) ?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">District <span class="text-danger">*</span></label>
                    <input type="text" name="district" class="form-control form-control-sm" value="<?= old('district', $admission['district']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Regency/City <span class="text-danger">*</span></label>
                    <input type="text" name="regency" class="form-control form-control-sm" value="<?= old('regency', $admission['regency']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Province <span class="text-danger">*</span></label>
                    <input type="text" name="province" class="form-control form-control-sm" value="<?= old('province', $admission['province']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Postal Code</label>
                    <input type="text" name="postal_code" class="form-control form-control-sm" value="<?= old('postal_code', $admission['postal_code']) ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Contact -->
    <div class="dashboard-card mb-3">
        <div class="card-header">
            <i class="bi bi-exclamation-triangle me-2"></i>Emergency Contact
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="emergency_contact_name" class="form-control form-control-sm" value="<?= old('emergency_contact_name', $admission['emergency_contact_name']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="tel" name="emergency_contact_phone" class="form-control form-control-sm" value="<?= old('emergency_contact_phone', $admission['emergency_contact_phone']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Relationship <span class="text-danger">*</span></label>
                    <input type="text" name="emergency_contact_relation" class="form-control form-control-sm" value="<?= old('emergency_contact_relation', $admission['emergency_contact_relation']) ?>" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Family -->
    <div class="dashboard-card mb-3">
        <div class="card-header">
            <i class="bi bi-people me-2"></i>Family Information
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Father's Name <span class="text-danger">*</span></label>
                    <input type="text" name="father_name" class="form-control form-control-sm" value="<?= old('father_name', $admission['father_name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mother's Name <span class="text-danger">*</span></label>
                    <input type="text" name="mother_name" class="form-control form-control-sm" value="<?= old('mother_name', $admission['mother_name']) ?>" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Course & Files -->
    <div class="dashboard-card mb-3">
        <div class="card-header">
            <i class="bi bi-mortarboard me-2"></i>Program, Status & Files
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Program <span class="text-danger">*</span></label>
                    <select name="program_id" class="form-select form-select-sm" required disabled>
                        <option value="">Select Program</option>
                        <?php foreach ($programs as $program): ?>
                            <option value="<?= esc($program['id']) ?>" <?= old('program_id', $admission['program_id']) === $program['id'] ? 'selected' : '' ?>>
                                <?= esc($program['title']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <input type="hidden" name="program_id" value="<?= esc($admission['program_id']) ?>">
                    <small class="text-muted">Program cannot be changed after admission is created</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Start Date</label>
                    <?php
                    // Generate start date options: 10th of each month for current and next year
                    $startDateOptions = [];
                    $currentYear = date('Y');
                    $nextYear = $currentYear + 1;
                    
                    for ($year = $currentYear; $year <= $nextYear; $year++) {
                        for ($month = 1; $month <= 12; $month++) {
                            // Skip past months in current year
                            if ($year == $currentYear && $month < date('n')) {
                                continue;
                            }
                            
                            // Find the 10th day of the month
                            $tenthDay = mktime(0, 0, 0, $month, 10, $year);
                            $dayOfWeek = date('N', $tenthDay);
                            
                            // If 10th is Friday (5), Saturday (6), or Sunday (7), move to next Monday
                            if ($dayOfWeek >= 5) {
                                $daysUntilMonday = 8 - $dayOfWeek; // Days until next Monday
                                $tenthDay = strtotime("+{$daysUntilMonday} days", $tenthDay);
                            }
                            
                            $dateValue = date('Y-m-d', $tenthDay);
                            $displayDate = date('F d, Y (l)', $tenthDay);
                            $startDateOptions[$dateValue] = $displayDate;
                        }
                    }
                    ?>
                    <select name="start_date" class="form-select form-select-sm">
                        <option value="">Select Start Date</option>
                        <?php foreach ($startDateOptions as $value => $label): ?>
                            <option value="<?= $value ?>" <?= old('start_date', $admission['start_date'] ?? '') === $value ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <small class="text-muted">10th of each month (moved to Monday if weekend)</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select form-select-sm" required>
                        <option value="pending" <?= old('status', $admission['status']) === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= old('status', $admission['status']) === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= old('status', $admission['status']) === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        <option value="withdrawn" <?= old('status', $admission['status']) === 'withdrawn' ? 'selected' : '' ?>>Withdrawn</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Profile Photo</label>
                    <?php if (!empty($admission['photo'])): ?>
                        <div class="mb-2">
                            <img src="<?= base_url('uploads/' . $admission['photo']) ?>"
                                alt="Current Photo"
                                class="img-thumbnail"
                                style="max-height: 100px;">
                        </div>
                    <?php endif ?>
                    <input type="file" name="photo" class="form-control form-control-sm" accept="image/jpeg,image/jpg,image/png,image/webp">
                    <small class="text-muted">Leave empty to keep current photo. Images will be converted to WebP.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Supporting Documents</label>
                    <input type="file" name="documents[]" class="form-control form-control-sm" accept=".pdf,.doc,.docx" multiple>
                    <small class="text-muted">Upload new documents (will add to existing)</small>
                </div>
                <div class="col-12">
                    <label class="form-label">Applicant Notes</label>
                    <?php
                    $applicantNotesValue = $admission['applicant_notes'] ?? '';
                    if (is_array($applicantNotesValue)) {
                        $applicantNotesValue = json_encode($applicantNotesValue);
                    }
                    /** @var string $applicantNotesValue */
                    ?>
                    <textarea name="applicant_notes" class="form-control form-control-sm" rows="2"><?= old('applicant_notes', $applicantNotesValue) ?></textarea>
                    <small class="text-muted">Notes from the applicant</small>
                </div>
                <div class="col-12">
                    <label class="form-label">Admin Notes</label>
                    <?php
                    $adminNotesValue = $admission['notes'] ?? '';
                    if (is_array($adminNotesValue)) {
                        $adminNotesValue = json_encode($adminNotesValue);
                    }
                    /** @var string $adminNotesValue */
                    ?>
                    <textarea name="notes" class="form-control form-control-sm" rows="2"><?= old('notes', $adminNotesValue) ?></textarea>
                    <small class="text-muted">Internal notes for staff only</small>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-dark-red">
            <i class="bi bi-save me-1"></i> Update Admission
        </button>
        <a href="<?= base_url('admission') ?>" class="btn btn-outline-dark-red">Cancel</a>
    </div>
</form>
<?= $this->endSection() ?>