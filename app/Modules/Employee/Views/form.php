<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold"><?= isset($staff) ? 'Edit Employee' : 'Add New Employee' ?></h4>
        <p class="text-muted mb-0"><?= isset($staff) ? 'Updating information for ' . esc($staff['full_name']) : 'Enter employee personal and employment details' ?></p>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('admin/employee') ?>" class="btn btn-outline-dark-red">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<!-- Superadmin Autofill Tool -->
<?php if (!isset($staff) && isset($user) && $user && $user->inGroup('superadmin')): ?>
    <div class="row mb-4">
        <div class="col">
            <div class="card bg-light border-primary shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 fw-bold text-primary"><i class="bi bi-magic me-2"></i>Testing Tool: Autofill Employee Form</h6>
                            <p class="small mb-0 text-muted">
                                Upload a <code>.txt</code> file to populate the form.
                                <a href="<?= base_url('templates/employee_autofill_template.txt') ?>" download class="text-decoration-none ms-1 fw-bold">
                                    <i class="bi bi-download me-1"></i>Download Template
                                </a>
                            </p>
                        </div>
                        <div class="ms-3" style="width: 250px;">
                            <input type="file" id="autofill_file" class="form-control form-control-sm" accept=".txt,.json">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const autofillFile = document.getElementById('autofill_file');
            if (!autofillFile) return;

            autofillFile.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (!file) return;

                // Check file type
                if (file.type !== 'application/json' && !file.name.endsWith('.txt') && !file.name.endsWith('.json')) {
                    alert('Please upload a .txt or .json file.\nDetected file type: ' + file.type);
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        const rawContent = e.target.result;
                        console.log('File content length:', rawContent.length);
                        console.log('First 100 chars:', rawContent.substring(0, 100));

                        const data = JSON.parse(rawContent);
                        console.log('Parsed data:', data);
                        const form = document.querySelector('form[action$="admin/employee/store"]');

                        if (!form) {
                            alert('Form not found!');
                            return;
                        }

                        const inputEl = event.target;
                        let filledCount = 0;

                        for (const key in data) {
                            const input = form.querySelector(`[name="${key}"], [name="${key}[]"]`);
                            if (input) {
                                if (input.type === 'checkbox' || input.type === 'radio') {
                                    if (input.value == data[key]) input.checked = true;
                                } else if (input.tagName === 'SELECT') {
                                    // Match by value or text
                                    let found = false;
                                    Array.from(input.options).forEach(opt => {
                                        if (opt.value == data[key] || opt.textContent.trim().toLowerCase() === String(data[key]).toLowerCase()) {
                                            input.value = opt.value;
                                            found = true;
                                        }
                                    });
                                    input.dispatchEvent(new Event('change'));
                                } else if (input.type !== 'file') {
                                    input.value = data[key];
                                    filledCount++;
                                }
                            }
                        }

                        const feedback = document.createElement('div');
                        feedback.className = 'alert alert-success mt-2 mb-0 py-2 small fw-medium';
                        feedback.innerHTML = `<i class="bi bi-check-circle me-1"></i> Form autofilled with ${filledCount} values!`;
                        inputEl.parentElement.appendChild(feedback);

                        inputEl.value = '';
                        setTimeout(() => feedback.remove(), 4000);

                    } catch (err) {
                        console.error('JSON Parse Error:', err);
                        console.log('Raw content:', e.target.result);
                        alert('Error parsing JSON file. Please ensure it is a valid JSON format.\n\nError: ' + err.message + '\n\nTip: Check the browser console (F12) for more details.');
                    }
                };
                reader.readAsText(file);
            });
        });
    </script>
<?php endif; ?>

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

<form action="<?= isset($staff) ? base_url('admin/employee/update/' . $staff['id']) : base_url('admin/employee/store') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row">
        <!-- Sidebar: Account & Essentials -->
        <div class="col-md-4">
            <!-- Account Info -->
            <div class="dashboard-card mb-4">
                <div class="card-header"><i class="bi bi-shield-lock me-2"></i>Account Credentials</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control form-control-sm" value="<?= old('username', $staff['username'] ?? '') ?>" <?= isset($staff) ? 'readonly' : 'required' ?>>
                        <?php if (isset($staff)): ?>
                            <small class="text-muted">Username cannot be changed.</small>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control form-control-sm" value="<?= old('email', $staff['email'] ?? '') ?>" required>
                    </div>
                    <?php if (!isset($staff)): ?>
                        <div class="mb-3">
                            <label class="form-label">Initial Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control form-control-sm" required>
                            <small class="text-muted">Minimum 8 characters.</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Employment Essentials -->
            <div class="dashboard-card mb-4">
                <div class="card-header"><i class="bi bi-briefcase me-2"></i>Employment Status</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Employment Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select form-select-sm" required>
                            <?php foreach ($statusOptions as $opt): ?>
                                <option value="<?= $opt ?>" <?= old('status', $staff['status'] ?? '') === $opt ? 'selected' : '' ?>><?= ucfirst($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Employment Type <span class="text-danger">*</span></label>
                        <select name="employment_type" class="form-select form-select-sm" required>
                            <?php foreach ($employmentTypes as $type): ?>
                                <option value="<?= $type ?>" <?= old('employment_type', $staff['employment_type'] ?? '') === $type ? 'selected' : '' ?>><?= ucfirst($type) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hire Date <span class="text-danger">*</span></label>
                        <input type="date" name="hire_date" class="form-control form-control-sm" value="<?= old('hire_date', $staff['hire_date'] ?? date('Y-m-d')) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Salary (Monthly)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">IDR</span>
                            <input type="number" name="salary" class="form-control" value="<?= old('salary', $staff['salary'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Column: Personal & Detailed Info -->
        <div class="col-md-8">
            <!-- Personal Info -->
            <div class="dashboard-card mb-4">
                <div class="card-header"><i class="bi bi-person me-2"></i>Personal Information</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control form-control-sm" value="<?= old('full_name', $staff['full_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nickname</label>
                            <input type="text" name="nickname" class="form-control form-control-sm" value="<?= old('nickname', $staff['nickname'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select form-select-sm" required>
                                <option value="Male" <?= old('gender', $staff['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= old('gender', $staff['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control form-control-sm" value="<?= old('phone', $staff['phone'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Place of Birth <span class="text-danger">*</span></label>
                            <input type="text" name="place_of_birth" class="form-control form-control-sm" value="<?= old('place_of_birth', $staff['place_of_birth'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" class="form-control form-control-sm" value="<?= old('date_of_birth', $staff['date_of_birth'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Religion <span class="text-danger">*</span></label>
                            <input type="text" name="religion" class="form-control form-control-sm" value="<?= old('religion', $staff['religion'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Citizen ID (KTP/NIK)</label>
                            <input type="text" name="citizen_id" class="form-control form-control-sm" value="<?= old('citizen_id', $staff['citizen_id'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="dashboard-card mb-4">
                <div class="card-header"><i class="bi bi-exclamation-triangle me-2"></i>Emergency Contact</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="emergency_contact_name" class="form-control form-control-sm" value="<?= old('emergency_contact_name', $staff['emergency_contact_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="tel" name="emergency_contact_phone" class="form-control form-control-sm" value="<?= old('emergency_contact_phone', $staff['emergency_contact_phone'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Relationship <span class="text-danger">*</span></label>
                            <input type="text" name="emergency_contact_relation" class="form-control form-control-sm" value="<?= old('emergency_contact_relation', $staff['emergency_contact_relation'] ?? '') ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Family Information -->
            <div class="dashboard-card mb-4">
                <div class="card-header"><i class="bi bi-people me-2"></i>Family Information</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Father's Name <span class="text-danger">*</span></label>
                            <input type="text" name="father_name" class="form-control form-control-sm" value="<?= old('father_name', $staff['father_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mother's Name <span class="text-danger">*</span></label>
                            <input type="text" name="mother_name" class="form-control form-control-sm" value="<?= old('mother_name', $staff['mother_name'] ?? '') ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Position & Address -->
            <div class="dashboard-card mb-4">
                <div class="card-header"><i class="bi bi-geo-alt me-2"></i>Job & Address</div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Position/Title <span class="text-danger">*</span></label>
                            <input type="text" name="position" class="form-control form-control-sm" value="<?= old('position', $staff['position'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <input type="text" name="department" class="form-control form-control-sm" value="<?= old('department', $staff['department'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Street Address <span class="text-danger">*</span></label>
                            <textarea name="street_address" class="form-control form-control-sm" rows="2" required><?= old('street_address', $staff['street_address'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">District <span class="text-danger">*</span></label>
                            <input type="text" name="district" class="form-control form-control-sm" value="<?= old('district', $staff['district'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Regency/City <span class="text-danger">*</span></label>
                            <input type="text" name="regency" class="form-control form-control-sm" value="<?= old('regency', $staff['regency'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Province <span class="text-danger">*</span></label>
                            <input type="text" name="province" class="form-control form-control-sm" value="<?= old('province', $staff['province'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" class="form-control form-control-sm" value="<?= old('postal_code', $staff['postal_code'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Files & Attachments -->
            <div class="dashboard-card mb-4">
                <div class="card-header"><i class="bi bi-paperclip me-2"></i>Attachments</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" name="photo" class="form-control form-control-sm" accept="image/*">
                            <?php if (isset($staff['photo'])): ?>
                                <small class="text-muted d-block mt-1">Current: <a href="<?= base_url('uploads/' . $staff['photo']) ?>" target="_blank"><?= basename($staff['photo']) ?></a></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Employment Documents</label>
                            <input type="file" name="documents[]" class="form-control form-control-sm" accept=".pdf,.doc,.docx" multiple>
                            <small class="text-muted">Contract, certificates, etc. (Multiple files allowed)</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mb-5">
                <button type="submit" class="btn btn-dark-red px-4">
                    <i class="bi bi-save me-1"></i> <?= isset($staff) ? 'Update Employee' : 'Create Employee' ?>
                </button>
                <a href="<?= base_url('admin/employee') ?>" class="btn btn-outline-dark-red">Cancel</a>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>