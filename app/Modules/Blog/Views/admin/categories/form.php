<?= $this->extend('Modules\Blog\Views\admin\layout') ?>

<?= $this->section('content') ?>
<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item active"><?= isset($category) ? 'Edit Category' : 'New Category' ?></li>
<?= $this->endSection() ?>

<!-- Success Message -->
<?php if (session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>
        <?= session('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Error Messages -->
<?php if (session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

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

<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-1"><?= $action === 'create' ? 'Create Category' : 'Edit Category' ?></h4>
        <p class="text-muted mb-0"><?= $action === 'create' ? 'Add a new category' : 'Update category details' ?></p>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('admin/blog/categories') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Categories
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <?php 
                $formAction = $action === 'create' 
                    ? base_url('admin/blog/categories/store')
                    : base_url('admin/blog/categories/update/' . $category['id']);
                ?>
                <form action="<?= $formAction ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= isset($category) ? esc($category['name']) : old('name') ?>" required
                               placeholder="Category name">
                    </div>

                    <!-- Slug -->
                    <div class="mb-3">
                        <label for="slug" class="form-label">URL Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug" 
                               value="<?= isset($category) ? esc($category['slug']) : old('slug') ?>"
                               placeholder="auto-generated-if-empty">
                        <div class="form-text">Alphanumeric characters, dashes, and underscores only</div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                                  placeholder="Category description"><?= isset($category) ? esc($category['description'] ?? '') : old('description') ?></textarea>
                    </div>

                    <!-- Image -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Image URL</label>
                        <input type="url" class="form-control" id="image" name="image" 
                               value="<?= isset($category) ? esc($category['image'] ?? '') : old('image') ?>"
                               placeholder="https://example.com/image.jpg">
                    </div>

                    <!-- Parent Category -->
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent Category</label>
                        <select class="form-select" id="parent_id" name="parent_id">
                            <option value="">No Parent (Root)</option>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <?php if (!isset($category) || $category['id'] !== $cat['id']): ?>
                                        <option value="<?= $cat['id'] ?>" 
                                                <?= (isset($category) && $category['parent_id'] == $cat['id']) ? 'selected' : '' ?>>
                                            <?= esc($cat['name']) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Display Order -->
                    <div class="mb-3">
                        <label for="display_order" class="form-label">Display Order</label>
                        <input type="number" class="form-control" id="display_order" name="display_order" 
                               value="<?= isset($category) ? ($category['display_order'] ?? 0) : old('display_order', 0) ?>"
                               min="0">
                    </div>

                    <!-- Active -->
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                               <?= (isset($category) && ($category['is_active'] ?? 1)) || old('is_active') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> <?= $action === 'create' ? 'Create Category' : 'Update Category' ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    nameInput.addEventListener('blur', function() {
        if (!slugInput.value) {
            slugInput.value = this.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-|-$/g, '');
        }
    });
});
</script>

<?= $this->endSection() ?>
