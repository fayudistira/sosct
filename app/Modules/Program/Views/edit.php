<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h3>Edit Program</h3>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= base_url('program') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

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

    <form action="<?= base_url('program/update/' . $program['id']) ?>" method="post" enctype="multipart/form-data">
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
                            value="<?= old('title', $program['title']) ?>" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"><?= old('description', $program['description']) ?></textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Thumbnail Image</label>
                        <?php if (!empty($program['thumbnail'])): ?>
                            <div class="mb-2">
                                <img src="<?= base_url('uploads/programs/thumbs/' . $program['thumbnail']) ?>"
                                    alt="Current thumbnail"
                                    class="img-thumbnail"
                                    style="max-width: 200px; max-height: 150px; object-fit: cover;">
                                <p class="text-muted small mb-0">Current thumbnail</p>
                            </div>
                        <?php endif ?>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image. Recommended size: 800x600px. Max 2MB.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Language</label>
                        <select name="language" class="form-select">
                            <option value="">-- Select Language --</option>
                            <option value="Mandarin" <?= old('language', $program['language'] ?? '') === 'Mandarin' ? 'selected' : '' ?>>Mandarin</option>
                            <option value="Japanese" <?= old('language', $program['language'] ?? '') === 'Japanese' ? 'selected' : '' ?>>Japanese</option>
                            <option value="Korean" <?= old('language', $program['language'] ?? '') === 'Korean' ? 'selected' : '' ?>>Korean</option>
                            <option value="German" <?= old('language', $program['language'] ?? '') === 'German' ? 'selected' : '' ?>>German</option>
                            <option value="English" <?= old('language', $program['language'] ?? '') === 'English' ? 'selected' : '' ?>>English</option>
                            <option value="Other" <?= old('language', $program['language'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                        <small class="text-muted">Primary language taught in this program</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Language Level</label>
                        <select name="language_level" class="form-select">
                            <option value="">-- Select Level --</option>
                            <option value="Beginner" <?= old('language_level', $program['language_level'] ?? '') === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
                            <option value="Intermediate" <?= old('language_level', $program['language_level'] ?? '') === 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
                            <option value="Advanced" <?= old('language_level', $program['language_level'] ?? '') === 'Advanced' ? 'selected' : '' ?>>Advanced</option>
                            <option value="All Levels" <?= old('language_level', $program['language_level'] ?? '') === 'All Levels' ? 'selected' : '' ?>>All Levels</option>
                        </select>
                        <small class="text-muted">Proficiency level for this program</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <div class="btn-group w-100" role="group" aria-label="Category selection">
                            <input type="radio" class="btn-check" name="category" id="category-regular" value="Regular" <?= old('category', $program['category']) === 'Regular' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="category-regular">
                                <i class="bi bi-people-fill me-1"></i>Regular
                            </label>
                            
                            <input type="radio" class="btn-check" name="category" id="category-private" value="Privat" <?= old('category', $program['category']) === 'Privat' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="category-private">
                                <i class="bi bi-person-fill me-1"></i>Privat
                            </label>
                            
                            <input type="radio" class="btn-check" name="category" id="category-paket" value="Paket" <?= old('category', $program['category']) === 'Paket' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="category-paket">
                                <i class="bi bi-box-fill me-1"></i>Paket
                            </label>
                        </div>
                        <small class="text-muted">Select program category type</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sub Category</label>
                        <input type="text" name="sub_category" class="form-control"
                            value="<?= old('sub_category', $program['sub_category']) ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Duration</label>
                        <input type="text" name="duration" class="form-control"
                            value="<?= old('duration', $program['duration']) ?>" placeholder="e.g., 3 months, 6 weeks">
                        <small class="text-muted">Course duration (e.g., 3 months, 6 weeks, 1 year)</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" <?= old('status', $program['status']) === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= old('status', $program['status']) === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Delivery Mode <span class="text-danger">*</span></label>
                        <div class="btn-group w-100" role="group" aria-label="Mode selection">
                            <input type="radio" class="btn-check" name="mode" id="mode-offline" value="offline" <?= old('mode', $program['mode'] ?? 'offline') === 'offline' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-success" for="mode-offline">
                                <i class="bi bi-building me-1"></i>Offline (In-Person)
                            </label>
                            
                            <input type="radio" class="btn-check" name="mode" id="mode-online" value="online" <?= old('mode', $program['mode'] ?? 'offline') === 'online' ? 'checked' : '' ?>>
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
                    <?php
                    $curriculum = old('curriculum', $program['curriculum'] ?? []);
                    if (empty($curriculum)):
                        $curriculum = [['chapter' => '', 'description' => '']];
                    endif;
                    ?>
                    <?php foreach ($curriculum as $index => $chapter): ?>
                        <div class="curriculum-item mb-3">
                            <div class="row">
                                <div class="col-md-5">
                                    <label class="form-label">Chapter Title</label>
                                    <input type="text" name="curriculum[<?= $index ?>][chapter]" class="form-control"
                                        value="<?= esc($chapter['chapter'] ?? '') ?>"
                                        placeholder="e.g., Chapter <?= $index + 1 ?>: Topic Name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Description</label>
                                    <input type="text" name="curriculum[<?= $index ?>][description]" class="form-control"
                                        value="<?= esc($chapter['description'] ?? '') ?>"
                                        placeholder="Brief description of this chapter">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-curriculum" <?= count($curriculum) === 1 ? 'disabled' : '' ?>>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
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
                            value="<?= old('registration_fee', $program['registration_fee']) ?>" step="0.01" min="0">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tuition Fee (Rp)</label>
                        <input type="number" name="tuition_fee" class="form-control"
                            value="<?= old('tuition_fee', $program['tuition_fee']) ?>" step="0.01" min="0">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Discount (%)</label>
                        <input type="number" name="discount" class="form-control"
                            value="<?= old('discount', $program['discount']) ?>" step="0.01" min="0" max="100">
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
                            placeholder="e.g.&#10;Interactive Learning&#10;Expert Instructors&#10;Flexible Schedule"><?= old('features', $program['features']) ?></textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Facilities</label>
                        <small class="text-muted d-block mb-2">Enter one facility per line</small>
                        <textarea name="facilities" class="form-control" rows="6"
                            placeholder="e.g.&#10;Modern Classrooms&#10;Computer Lab&#10;Library"><?= old('facilities', $program['facilities']) ?></textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Extra Facilities</label>
                        <small class="text-muted d-block mb-2">Enter one extra facility per line</small>
                        <textarea name="extra_facilities" class="form-control" rows="6"
                            placeholder="e.g.&#10;Free WiFi&#10;Parking Area&#10;Cafeteria"><?= old('extra_facilities', $program['extra_facilities']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Program
                </button>
                <a href="<?= base_url('program') ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let curriculumIndex = <?= count($curriculum) ?>;
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