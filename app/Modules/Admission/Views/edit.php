<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <a href="<?= base_url('admission') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <?php if (session('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <form action="<?= base_url('admission/update/' . $admission['id']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <!-- Personal Information -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Registration Number</label>
                    <input type="text" class="form-control" value="<?= esc($admission['registration_number']) ?>" readonly>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control" value="<?= old('full_name', $admission['full_name']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nickname</label>
                        <input type="text" name="nickname" class="form-control" value="<?= old('nickname', $admission['nickname']) ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select" required>
                            <option value="Male" <?= old('gender', $admission['gender']) === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= old('gender', $admission['gender']) === 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_birth" class="form-control" value="<?= old('date_of_birth', $admission['date_of_birth']) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Place of Birth <span class="text-danger">*</span></label>
                        <input type="text" name="place_of_birth" class="form-control" value="<?= old('place_of_birth', $admission['place_of_birth']) ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Religion <span class="text-danger">*</span></label>
                        <input type="text" name="religion" class="form-control" value="<?= old('religion', $admission['religion']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Citizen ID</label>
                        <input type="text" name="citizen_id" class="form-control" value="<?= old('citizen_id', $admission['citizen_id']) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Contact Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control" value="<?= old('phone', $admission['phone']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="<?= old('email', $admission['email']) ?>" required>
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
                <div class="mb-3">
                    <label class="form-label">Street Address <span class="text-danger">*</span></label>
                    <textarea name="street_address" class="form-control" rows="2" required><?= old('street_address', $admission['street_address']) ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">District <span class="text-danger">*</span></label>
                        <input type="text" name="district" class="form-control" value="<?= old('district', $admission['district']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Regency/City <span class="text-danger">*</span></label>
                        <input type="text" name="regency" class="form-control" value="<?= old('regency', $admission['regency']) ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Province <span class="text-danger">*</span></label>
                        <input type="text" name="province" class="form-control" value="<?= old('province', $admission['province']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control" value="<?= old('postal_code', $admission['postal_code']) ?>">
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
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="emergency_contact_name" class="form-control" value="<?= old('emergency_contact_name', $admission['emergency_contact_name']) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="tel" name="emergency_contact_phone" class="form-control" value="<?= old('emergency_contact_phone', $admission['emergency_contact_phone']) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Relationship <span class="text-danger">*</span></label>
                        <input type="text" name="emergency_contact_relation" class="form-control" value="<?= old('emergency_contact_relation', $admission['emergency_contact_relation']) ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Family -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Family Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Father's Name <span class="text-danger">*</span></label>
                        <input type="text" name="father_name" class="form-control" value="<?= old('father_name', $admission['father_name']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mother's Name <span class="text-danger">*</span></label>
                        <input type="text" name="mother_name" class="form-control" value="<?= old('mother_name', $admission['mother_name']) ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course & Files -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Course & Files</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Course <span class="text-danger">*</span></label>
                        <input type="text" name="course" class="form-control" value="<?= old('course', $admission['course']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="pending" <?= old('status', $admission['status']) === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="approved" <?= old('status', $admission['status']) === 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="rejected" <?= old('status', $admission['status']) === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Profile Photo</label>
                    <?php if (!empty($admission['photo'])): ?>
                        <div class="mb-2">
                            <img src="<?= base_url('writable/uploads/admissions/photos/' . $admission['photo']) ?>" 
                                 alt="Current Photo" 
                                 style="max-height: 150px;">
                        </div>
                    <?php endif ?>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                    <small class="text-muted">Leave empty to keep current photo</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Supporting Documents</label>
                    <input type="file" name="documents[]" class="form-control" accept=".pdf,.doc,.docx" multiple>
                    <small class="text-muted">Upload new documents (will replace existing)</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3"><?= old('notes', $admission['notes']) ?></textarea>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Update Admission
            </button>
            <a href="<?= base_url('admission') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
