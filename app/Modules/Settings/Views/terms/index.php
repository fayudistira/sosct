<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="hero-section py-4" style="background: linear-gradient(135deg, #8B0000 0%, #a52a2a 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="fw-bold mb-1" style="color: white;">Terms & Conditions</h4>
                <p class="mb-0" style="color: rgba(255,255,255,0.8);">Manage terms and conditions for different languages</p>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('settings/terms/create') ?>" class="btn btn-light">
                    <i class="bi bi-plus-lg me-1"></i> Add New Terms
                </a>
                <a href="<?= base_url('settings') ?>" class="btn btn-outline-light">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
<!-- Success/Error Messages -->
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

<!-- Terms List -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Language</th>
                        <th>Title</th>
                        <th>Content Preview</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($terms)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-file-text fs-1 d-block mb-2"></i>
                                No terms and conditions found.
                                <a href="<?= base_url('settings/terms/create') ?>">Create your first terms</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($terms as $term): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-primary"><?= esc($term['language']) ?></span>
                                </td>
                                <td><?= esc($term['title']) ?></td>
                                <td>
                                    <?= substr(strip_tags($term['content']), 0, 80) ?>...
                                </td>
                                <td>
                                    <?php if ($term['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= date('M d, Y H:i', strtotime($term['updated_at'] ?? $term['created_at'])) ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('settings/terms/edit/' . $term['id']) ?>" 
                                           class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?= base_url('settings/terms/toggle/' . $term['id']) ?>" 
                                              method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline-secondary" 
                                                    title="<?= $term['is_active'] ? 'Deactivate' : 'Activate' ?>">
                                                <i class="bi <?= $term['is_active'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
                                            </button>
                                        </form>
                                        <form action="<?= base_url('settings/terms/delete/' . $term['id']) ?>" 
                                              method="post" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete these terms?');">
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
</div>

<!-- Info Card -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
            <div class="card-body">
                <h6><i class="bi bi-info-circle me-2"></i>How it works</h6>
                <ul class="mb-0 text-muted">
                    <li>Each language can have its own terms and conditions.</li>
                    <li>The terms will be displayed based on the program's language selected by the applicant.</li>
                    <li>Make sure the language matches the language field in your programs.</li>
                    <li>Only active terms will be shown to applicants.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
