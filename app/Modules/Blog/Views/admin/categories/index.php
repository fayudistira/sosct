<?= $this->extend('Modules\Blog\Views\admin\layout') ?>

<?= $this->section('content') ?>
<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item active">Categories</li>
<?= $this->endSection() ?>

<!-- Success Message -->
<?php if (session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>
        <?= session('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Error Message -->
<?php if (session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item active">Categories</li>
<?= $this->endSection() ?>

<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-1">Blog Categories</h4>
        <p class="text-muted mb-0">Organize your blog posts with categories</p>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('admin/blog/categories/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> New Category
        </a>
        <a href="<?= base_url('admin/blog') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Posts
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <!-- Messages -->
        <?php if (session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>
                <?= session('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Parent</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-folder fs-1 d-block mb-2"></i>
                                No categories found.
                                <a href="<?= base_url('admin/blog/categories/create') ?>">Create your first category</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><strong><?= esc($category['name']) ?></strong></td>
                                <td><code><?= esc($category['slug']) ?></code></td>
                                <td>
                                    <?php if ($category['description']): ?>
                                        <?= substr(esc($category['description']), 0, 50) ?>...
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($category['parent_id']): ?>
                                        <?= esc($category['parent_id']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Root</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($category['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $category['display_order'] ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('admin/blog/categories/edit/' . $category['id']) ?>" 
                                           class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?= base_url('admin/blog/categories/toggle/' . $category['id']) ?>" 
                                              method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline-secondary" 
                                                    title="<?= $category['is_active'] ? 'Deactivate' : 'Activate' ?>">
                                                <i class="bi <?= $category['is_active'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
                                            </button>
                                        </form>
                                        <form action="<?= base_url('admin/blog/categories/delete/' . $category['id']) ?>" 
                                              method="post" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this category?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
