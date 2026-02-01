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
                                <img src="<?= base_url('writable/uploads/programs/thumbs/' . $program['thumbnail']) ?>" 
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
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" 
                               value="<?= old('category', $program['category']) ?>">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sub Category</label>
                        <input type="text" name="sub_category" class="form-control" 
                               value="<?= old('sub_category', $program['sub_category']) ?>">
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" <?= old('status', $program['status']) === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= old('status', $program['status']) === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>
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
<?= $this->endSection() ?>
