<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h4 class="fw-bold">Edit Profile</h4>
            <p class="text-muted mb-0">Update your personal information</p>
        </div>
        <div class="col-auto">
            <a href="<?= base_url('account') ?>" class="btn btn-outline-secondary">
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

    <form action="<?= base_url('account/update') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <!-- Personal Information -->
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-person me-2"></i>Personal Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control" value="<?= old('full_name', $profile['full_name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nickname</label>
                        <input type="text" name="nickname" class="form-control" value="<?= old('nickname', $profile['nickname']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Position</label>
                        <input type="text" name="position" class="form-control" value="<?= old('position', $profile['position']) ?>" placeholder="e.g., Manager, Staff, Director">
                        <small class="text-muted">Your job position or title</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" value="<?= esc(ucfirst($role ?? 'User')) ?>" disabled>
                        <small class="text-muted">Your system role (managed by administrator)</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select" required>
                            <option value="">Select</option>
                            <option value="Male" <?= old('gender', $profile['gender']) === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= old('gender', $profile['gender']) === 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_birth" class="form-control" value="<?= old('date_of_birth', $profile['date_of_birth']) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Place of Birth <span class="text-danger">*</span></label>
                        <input type="text" name="place_of_birth" class="form-control" value="<?= old('place_of_birth', $profile['place_of_birth']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Religion <span class="text-danger">*</span></label>
                        <input type="text" name="religion" class="form-control" value="<?= old('religion', $profile['religion']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Citizen ID</label>
                        <input type="text" name="citizen_id" class="form-control" value="<?= old('citizen_id', $profile['citizen_id']) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-telephone me-2"></i>Contact Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control" value="<?= old('phone', $profile['phone']) ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address -->
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-geo-alt me-2"></i>Address
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Street Address <span class="text-danger">*</span></label>
                        <textarea name="street_address" class="form-control" rows="2" required><?= old('street_address', $profile['street_address']) ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">District <span class="text-danger">*</span></label>
                        <input type="text" name="district" class="form-control" value="<?= old('district', $profile['district']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Regency/City <span class="text-danger">*</span></label>
                        <input type="text" name="regency" class="form-control" value="<?= old('regency', $profile['regency']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Province <span class="text-danger">*</span></label>
                        <input type="text" name="province" class="form-control" value="<?= old('province', $profile['province']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control" value="<?= old('postal_code', $profile['postal_code']) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-exclamation-triangle me-2"></i>Emergency Contact
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="emergency_contact_name" class="form-control" value="<?= old('emergency_contact_name', $profile['emergency_contact_name']) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="tel" name="emergency_contact_phone" class="form-control" value="<?= old('emergency_contact_phone', $profile['emergency_contact_phone']) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Relationship <span class="text-danger">*</span></label>
                        <input type="text" name="emergency_contact_relation" class="form-control" value="<?= old('emergency_contact_relation', $profile['emergency_contact_relation']) ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Family -->
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-people me-2"></i>Family Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Father's Name <span class="text-danger">*</span></label>
                        <input type="text" name="father_name" class="form-control" value="<?= old('father_name', $profile['father_name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mother's Name <span class="text-danger">*</span></label>
                        <input type="text" name="mother_name" class="form-control" value="<?= old('mother_name', $profile['mother_name']) ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Files -->
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-file-earmark me-2"></i>Files
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Profile Photo</label>
                        <?php if ($profile['photo']): ?>
                            <div class="mb-2">
                                <img src="<?= base_url('writable/uploads/' . $profile['photo']) ?>" 
                                     class="img-thumbnail" 
                                     style="max-width: 150px;" 
                                     alt="Current Photo">
                                <p class="text-muted small mb-0">Current photo</p>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        <small class="text-muted">Leave empty to keep current photo. Accepted formats: JPG, PNG (Max 2MB)</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Add More Documents</label>
                        <?php if ($profile['documents']): ?>
                            <?php $docs = json_decode($profile['documents'], true); ?>
                            <div class="mb-2">
                                <p class="text-muted small mb-1"><?= count($docs) ?> document(s) uploaded</p>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="documents[]" class="form-control" accept=".pdf,image/*" multiple>
                        <small class="text-muted">Upload additional documents. Accepted formats: PDF, Images (Max 5MB each)</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Update Profile
            </button>
            <a href="<?= base_url('account') ?>" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
