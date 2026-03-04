<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="hero-section py-4" style="background: linear-gradient(135deg, #8B0000 0%, #a52a2a 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="fw-bold mb-1" style="color: white;">Edit Terms & Conditions</h4>
                <p class="mb-0" style="color: rgba(255,255,255,0.8);">Update terms for <?= esc($term['language']) ?></p>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('settings/terms') ?>" class="btn btn-light">
                    <i class="bi bi-arrow-left me-1"></i> Back to Terms List
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Edit Terms & Conditions</h4>
        <p class="text-muted mb-0">Update terms and conditions for <?= esc($term['language']) ?></p>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('settings/terms') ?>" class="btn btn-outline-dark-red">
            <i class="bi bi-arrow-left me-1"></i> Back to Terms List
        </a>
    </div>
</div>

<!-- Error Messages -->
<?php if (session('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Validation Errors:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Edit Form -->
<form action="<?= base_url('settings/terms/update/' . $term['id']) ?>" method="post">
    <?= csrf_field() ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <i class="bi bi-file-text me-2"></i>Terms Content
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Language <span class="text-danger">*</span></label>
                        <select name="language" class="form-select form-select-sm" required>
                            <option value="">Select Language</option>
                            <?php foreach ($availableLanguages as $lang): ?>
                                <option value="<?= esc($lang['language']) ?>" 
                                        <?= $lang['language'] === $term['language'] ? 'selected' : '' ?>>
                                    <?= esc($lang['language']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">This should match the language in your programs</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-sm" 
                               value="<?= old('title', $term['title']) ?>" required
                               placeholder="e.g., Terms and Conditions">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control form-control-sm" rows="20" required
                                  placeholder="Enter terms and conditions content (HTML allowed)"><?= old('content', $term['content']) ?></textarea>
                        <small class="text-muted">You can use HTML tags for formatting</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <i class="bi bi-gear me-2"></i>Settings
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" 
                                   id="isActive" value="1" 
                                   <?= $term['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isActive">
                                Active
                            </label>
                        </div>
                        <small class="text-muted">Inactive terms won't be shown to applicants</small>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-dark-red w-100 mb-2">
                        <i class="bi bi-save me-1"></i> Update Terms
                    </button>
                    <a href="<?= base_url('settings/terms') ?>" class="btn btn-outline-secondary w-100">
                        Cancel
                    </a>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <h6><i class="bi bi-info-circle me-2"></i>Info</h6>
                    <small class="text-muted">
                        <strong>Created:</strong> <?= date('M d, Y H:i', strtotime($term['created_at'])) ?><br>
                        <strong>Updated:</strong> <?= date('M d, Y H:i', strtotime($term['updated_at'])) ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
