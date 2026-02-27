<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Create New Admission</h4>
        <p class="text-muted mb-0">Fill in the admission application form</p>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('admission') ?>" class="btn btn-outline-dark-red">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<!-- Superadmin Autofill Tool -->
<?php if ($user && $user->inGroup('superadmin')): ?>
    <div class="row mb-4">
        <div class="col">
            <div class="card bg-light border-primary shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 fw-bold text-primary"><i class="bi bi-magic me-2"></i>Testing Tool: Autofill From JSON</h6>
                            <p class="small mb-0 text-muted">
                                Upload a <code>.txt</code> file to populate the form.
                                <a href="<?= base_url('templates/admission_autofill_template.txt') ?>" download class="text-decoration-none ms-1 fw-bold">
                                    <i class="bi bi-download me-1"></i>Download Template
                                </a>
                            </p>
                        </div>
                        <div class="ms-3" style="width: 300px;">
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
            console.log('Autofill file element:', autofillFile);

            if (!autofillFile) {
                console.error('Autofill file input not found!');
                return;
            }

            autofillFile.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (!file) return;

                console.log('Processing file:', file.name);

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

                        // Try to parse JSON
                        const data = JSON.parse(rawContent);
                        console.log('Parsed JSON data:', data);

                        const form = document.querySelector('form[action$="admission/store"]');
                        console.log('Form found:', !!form);

                        if (!form) {
                            alert('Form not found! Check console for details.');
                            console.log('All form actions on page:', Array.from(document.querySelectorAll('form')).map(f => f.action));
                            return;
                        }

                        const inputEl = event.target;
                        let filledCount = 0;
                        let notFound = [];

                        for (const key in data) {
                            const input = form.querySelector(`[name="${key}"], [name="${key}[]"]`);
                            console.log(`Field "${key}":`, input ? 'FOUND' : 'NOT FOUND');

                            if (input) {
                                if (input.type === 'checkbox' || input.type === 'radio') {
                                    if (input.value == data[key]) input.checked = true;
                                } else if (input.tagName === 'SELECT') {
                                    let found = false;
                                    Array.from(input.options).forEach(opt => {
                                        if (opt.value == data[key] || opt.textContent.trim().includes(data[key])) {
                                            input.value = opt.value;
                                            found = true;
                                        }
                                    });
                                    if (!found && key === 'course') console.warn('Program not found:', data[key]);
                                    input.dispatchEvent(new Event('change'));
                                } else if (input.type !== 'file') {
                                    console.log(`Setting "${key}" = "${data[key]}"`);
                                    input.value = data[key];
                                    filledCount++;
                                }
                            } else {
                                notFound.push(key);
                            }
                        }

                        if (notFound.length > 0) {
                            console.warn('Fields not in form:', notFound);
                        }

                        // Specific handling for 'course'
                        if (data.course) {
                            const courseSelect = form.querySelector('select[name="course"]');
                            if (courseSelect) {
                                courseSelect.value = data.course;
                                courseSelect.dispatchEvent(new Event('change'));
                            }
                        }

                        // Show feedback
                        const feedback = document.createElement('div');
                        feedback.className = 'alert alert-success mt-2 mb-0 py-2 small fw-medium';
                        feedback.innerHTML = `<i class="bi bi-check-circle me-1"></i> Form autofilled with ${filledCount} values!`;
                        inputEl.parentElement.appendChild(feedback);

                        console.log('Total fields filled:', filledCount);

                        inputEl.value = '';
                        setTimeout(() => feedback.remove(), 4000);

                    } catch (err) {
                        console.error('JSON Parse Error:', err);
                        alert('Error parsing JSON: ' + err.message + '\nCheck console for details.');
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

<form action="<?= base_url('admission/store') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- Personal Information -->
    <div class="dashboard-card mb-3">
        <div class="card-header">
            <i class="bi bi-person me-2"></i>Personal Information
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control form-control-sm" value="<?= old('full_name') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nickname</label>
                    <input type="text" name="nickname" class="form-control form-control-sm" value="<?= old('nickname') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select form-select-sm" required>
                        <option value="">Select</option>
                        <option value="Male" <?= old('gender') === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= old('gender') === 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" name="date_of_birth" class="form-control form-control-sm" value="<?= old('date_of_birth') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Place of Birth <span class="text-danger">*</span></label>
                    <input type="text" name="place_of_birth" class="form-control form-control-sm" value="<?= old('place_of_birth') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Religion <span class="text-danger">*</span></label>
                    <input type="text" name="religion" class="form-control form-control-sm" value="<?= old('religion') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Citizen ID</label>
                    <input type="text" name="citizen_id" class="form-control form-control-sm" value="<?= old('citizen_id') ?>">
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
                    <input type="tel" name="phone" class="form-control form-control-sm" value="<?= old('phone') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control form-control-sm" value="<?= old('email') ?>" required>
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
                    <textarea name="street_address" class="form-control form-control-sm" rows="2" required><?= old('street_address') ?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">District <span class="text-danger">*</span></label>
                    <input type="text" name="district" class="form-control form-control-sm" value="<?= old('district') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Regency/City <span class="text-danger">*</span></label>
                    <input type="text" name="regency" class="form-control form-control-sm" value="<?= old('regency') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Province <span class="text-danger">*</span></label>
                    <input type="text" name="province" class="form-control form-control-sm" value="<?= old('province') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Postal Code</label>
                    <input type="text" name="postal_code" class="form-control form-control-sm" value="<?= old('postal_code') ?>">
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
                    <input type="text" name="emergency_contact_name" class="form-control form-control-sm" value="<?= old('emergency_contact_name') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="tel" name="emergency_contact_phone" class="form-control form-control-sm" value="<?= old('emergency_contact_phone') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Relationship <span class="text-danger">*</span></label>
                    <input type="text" name="emergency_contact_relation" class="form-control form-control-sm" value="<?= old('emergency_contact_relation') ?>" required>
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
                    <input type="text" name="father_name" class="form-control form-control-sm" value="<?= old('father_name') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mother's Name <span class="text-danger">*</span></label>
                    <input type="text" name="mother_name" class="form-control form-control-sm" value="<?= old('mother_name') ?>" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Course & Files -->
    <div class="dashboard-card mb-3">
        <div class="card-header">
            <i class="bi bi-mortarboard me-2"></i>Course & Files
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Course <span class="text-danger">*</span></label>
                    <select name="program_id" class="form-select form-select-sm" required>
                        <option value="">Select Program</option>
                        <?php foreach ($programs as $program): ?>
                            <option value="<?= esc($program['id']) ?>" <?= old('program_id') === $program['id'] ? 'selected' : '' ?>>
                                <?= esc($program['title']) ?> (Rp <?= number_format($program['registration_fee'] ?? 0, 0, ',', '.') ?>)
                            </option>
                        <?php endforeach ?>
                    </select>
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
                            <option value="<?= $value ?>" <?= old('start_date') === $value ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <small class="text-muted">10th of each month (moved to Monday if weekend)</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select form-select-sm" required>
                        <option value="pending" <?= old('status') === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= old('status') === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= old('status') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Profile Photo <span class="text-danger">*</span></label>
                    <input type="file" name="photo" class="form-control form-control-sm" accept="image/jpeg,image/jpg,image/png,image/webp" required>
                    <small class="text-muted">Accepted formats: JPG, PNG, WebP. Images will be converted to WebP for optimization.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Supporting Documents</label>
                    <input type="file" name="documents[]" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,image/*" multiple>
                    <small class="text-muted">Accepted formats: PDF, DOC, DOCX, JPG, PNG, GIF (Max 5MB each)</small>
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control form-control-sm" rows="3"><?= old('notes') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-dark-red">
            <i class="bi bi-save me-1"></i> Save Admission
        </button>
        <a href="<?= base_url('admission') ?>" class="btn btn-outline-dark-red">Cancel</a>
    </div>
</form>
<?= $this->endSection() ?>