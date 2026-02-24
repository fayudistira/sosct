<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h3>Create New Program</h3>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= base_url('program') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Superadmin Autofill Tool -->
    <?php if (isset($user) && $user && $user->inGroup('superadmin')): ?>
        <div class="row mb-4">
            <div class="col">
                <div class="card bg-light border-primary shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-1 fw-bold text-primary"><i class="bi bi-magic me-2"></i>Testing Tool: Autofill Program Form</h6>
                                <p class="small mb-0 text-muted">
                                    Upload a <code>.txt</code> file to populate the form.
                                    <a href="<?= base_url('templates/program_autofill_template.txt') ?>" download class="text-decoration-none ms-1 fw-bold">
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
                            const form = document.querySelector('form[action$="program/store"]');

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
                                        let found = false;
                                        Array.from(input.options).forEach(opt => {
                                            if (opt.value == data[key] || opt.textContent.trim().toLowerCase() === String(data[key]).toLowerCase()) {
                                                input.value = opt.value;
                                                found = true;
                                            }
                                        });
                                        input.dispatchEvent(new Event('change'));
                                    } else if (input.tagName === 'TEXTAREA') {
                                        input.value = data[key];
                                        filledCount++;
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

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <form action="<?= base_url('program/store') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Basic Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Program Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control"
                            value="<?= old('title') ?>" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"><?= old('description') ?></textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Thumbnail Image</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        <small class="text-muted">Recommended size: 800x600px. Max 2MB.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Language</label>
                        <select name="language" class="form-select">
                            <option value="">-- Select Language --</option>
                            <option value="Mandarin" <?= old('language') === 'Mandarin' ? 'selected' : '' ?>>Mandarin</option>
                            <option value="Japanese" <?= old('language') === 'Japanese' ? 'selected' : '' ?>>Japanese</option>
                            <option value="Korean" <?= old('language') === 'Korean' ? 'selected' : '' ?>>Korean</option>
                            <option value="German" <?= old('language') === 'German' ? 'selected' : '' ?>>German</option>
                            <option value="English" <?= old('language') === 'English' ? 'selected' : '' ?>>English</option>
                            <option value="Other" <?= old('language') === 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                        <small class="text-muted">Primary language taught in this program</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Language Level</label>
                        <select name="language_level" class="form-select">
                            <option value="">-- Select Level --</option>
                            <option value="Beginner" <?= old('language_level') === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
                            <option value="Intermediate" <?= old('language_level') === 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
                            <option value="Advanced" <?= old('language_level') === 'Advanced' ? 'selected' : '' ?>>Advanced</option>
                            <option value="All Levels" <?= old('language_level') === 'All Levels' ? 'selected' : '' ?>>All Levels</option>
                        </select>
                        <small class="text-muted">Proficiency level for this program</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <div class="btn-group w-100" role="group" aria-label="Category selection">
                            <input type="radio" class="btn-check" name="category" id="category-regular" value="Regular" <?= old('category') === 'Regular' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="category-regular">
                                <i class="bi bi-people-fill me-1"></i>Regular
                            </label>
                            
                            <input type="radio" class="btn-check" name="category" id="category-private" value="Privat" <?= old('category') === 'Privat' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="category-private">
                                <i class="bi bi-person-fill me-1"></i>Privat
                            </label>
                            
                            <input type="radio" class="btn-check" name="category" id="category-paket" value="Paket" <?= old('category') === 'Paket' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="category-paket">
                                <i class="bi bi-box-fill me-1"></i>Paket
                            </label>
                        </div>
                        <small class="text-muted">Select program category type</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sub Category</label>
                        <input type="text" name="sub_category" class="form-control"
                            value="<?= old('sub_category') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Duration</label>
                        <input type="text" name="duration" class="form-control"
                            value="<?= old('duration') ?>" placeholder="e.g., 3 months, 6 weeks">
                        <small class="text-muted">Course duration (e.g., 3 months, 6 weeks, 1 year)</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" <?= old('status') === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Delivery Mode <span class="text-danger">*</span></label>
                        <div class="btn-group w-100" role="group" aria-label="Mode selection">
                            <input type="radio" class="btn-check" name="mode" id="mode-offline" value="offline" <?= old('mode', 'offline') === 'offline' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-success" for="mode-offline">
                                <i class="bi bi-building me-1"></i>Offline (In-Person)
                            </label>
                            
                            <input type="radio" class="btn-check" name="mode" id="mode-online" value="online" <?= old('mode') === 'online' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-info" for="mode-online">
                                <i class="bi bi-wifi me-1"></i>Online (Remote)
                            </label>
                        </div>
                        <small class="text-muted">Select delivery method</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Curriculum</h5>
            </div>
            <div class="card-body">
                <div id="curriculum-container">
                    <div class="curriculum-item mb-3">
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label">Chapter Title</label>
                                <input type="text" name="curriculum[0][chapter]" class="form-control"
                                    placeholder="e.g., Chapter 1: Introduction">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Description</label>
                                <input type="text" name="curriculum[0][description]" class="form-control"
                                    placeholder="Brief description of this chapter">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-curriculum" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm" id="add-curriculum">
                    <i class="bi bi-plus-circle"></i> Add Chapter
                </button>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Fee Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Registration Fee (Rp)</label>
                        <input type="number" name="registration_fee" class="form-control"
                            value="<?= old('registration_fee', 0) ?>" step="0.01" min="0">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tuition Fee (Rp)</label>
                        <input type="number" name="tuition_fee" class="form-control"
                            value="<?= old('tuition_fee', 0) ?>" step="0.01" min="0">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Discount (%)</label>
                        <input type="number" name="discount" class="form-control"
                            value="<?= old('discount', 0) ?>" step="0.01" min="0" max="100">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Features & Facilities</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Features</label>
                        <small class="text-muted d-block mb-2">Enter one feature per line</small>
                        <textarea name="features" class="form-control" rows="6"
                            placeholder="e.g.&#10;Interactive Learning&#10;Expert Instructors&#10;Flexible Schedule"><?= old('features') ?></textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Facilities</label>
                        <small class="text-muted d-block mb-2">Enter one facility per line</small>
                        <textarea name="facilities" class="form-control" rows="6"
                            placeholder="e.g.&#10;Modern Classrooms&#10;Computer Lab&#10;Library"><?= old('facilities') ?></textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Extra Facilities</label>
                        <small class="text-muted d-block mb-2">Enter one extra facility per line</small>
                        <textarea name="extra_facilities" class="form-control" rows="6"
                            placeholder="e.g.&#10;Free WiFi&#10;Parking Area&#10;Cafeteria"><?= old('extra_facilities') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Create Program
                </button>
                <a href="<?= base_url('program') ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let curriculumIndex = 1;
        const container = document.getElementById('curriculum-container');
        const addButton = document.getElementById('add-curriculum');

        addButton.addEventListener('click', function() {
            const newItem = document.createElement('div');
            newItem.className = 'curriculum-item mb-3';
            newItem.innerHTML = `
            <div class="row">
                <div class="col-md-5">
                    <label class="form-label">Chapter Title</label>
                    <input type="text" name="curriculum[${curriculumIndex}][chapter]" class="form-control" 
                           placeholder="e.g., Chapter ${curriculumIndex + 1}: Topic Name">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Description</label>
                    <input type="text" name="curriculum[${curriculumIndex}][description]" class="form-control" 
                           placeholder="Brief description of this chapter">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-curriculum">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;

            container.appendChild(newItem);
            curriculumIndex++;

            // Enable remove buttons
            updateRemoveButtons();
        });

        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-curriculum')) {
                e.target.closest('.curriculum-item').remove();
                updateRemoveButtons();
            }
        });

        function updateRemoveButtons() {
            const items = container.querySelectorAll('.curriculum-item');
            items.forEach((item, index) => {
                const removeBtn = item.querySelector('.remove-curriculum');
                if (items.length === 1) {
                    removeBtn.disabled = true;
                } else {
                    removeBtn.disabled = false;
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>