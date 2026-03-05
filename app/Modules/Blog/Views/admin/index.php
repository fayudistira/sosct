<?= $this->extend('Modules\Blog\Views\admin\layout') ?>

<?= $this->section('content') ?>
<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item active">All Posts</li>
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

<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-1">Blog Posts</h4>
        <p class="text-muted mb-0">Manage your educational blog content</p>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('admin/blog/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> New Post
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Total Posts</p>
                        <h4 class="mb-0"><?= $stats['total_posts'] ?? 0 ?></h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="bi bi-file-post text-primary fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Published</p>
                        <h4 class="mb-0"><?= $stats['published_posts'] ?? 0 ?></h4>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <i class="bi bi-check-circle text-success fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Drafts</p>
                        <h4 class="mb-0"><?= $stats['draft_posts'] ?? 0 ?></h4>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <i class="bi bi-pencil text-warning fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Total Views</p>
                        <h4 class="mb-0"><?= number_format($stats['total_views'] ?? 0) ?></h4>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded">
                        <i class="bi bi-eye text-info fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Posts List -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
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

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Date</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($posts)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-file-post fs-1 d-block mb-2"></i>
                                No blog posts found.
                                <a href="<?= base_url('admin/blog/create') ?>">Create your first post</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($posts as $index => $post): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <a href="<?= base_url('blog/' . $post['slug']) ?>" target="_blank" class="text-decoration-none">
                                        <?= esc($post['title']) ?>
                                    </a>
                                    <?php if ($post['is_featured']): ?>
                                        <span class="badge bg-warning ms-1">Featured</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($post['category_name']): ?>
                                        <span class="badge bg-primary"><?= esc($post['category_name']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($post['author_name'] ?? 'Unknown') ?></td>
                                <td>
                                    <?php if ($post['is_published']): ?>
                                        <span class="badge bg-success">Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= number_format($post['view_count']) ?></td>
                                <td>
                                    <?= date('M d, Y', strtotime($post['created_at'])) ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('admin/blog/edit/' . $post['id']) ?>" 
                                           class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= base_url('blog/' . $post['slug']) ?>" 
                                           target="_blank"
                                           class="btn btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="<?= base_url('admin/blog/toggle/' . $post['id']) ?>" 
                                              method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline-secondary" 
                                                    title="<?= $post['is_published'] ? 'Unpublish' : 'Publish' ?>">
                                                <i class="bi <?= $post['is_published'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
                                            </button>
                                        </form>
                                        <form action="<?= base_url('admin/blog/feature/' . $post['id']) ?>" 
                                              method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline-warning" 
                                                    title="<?= $post['is_featured'] ? 'Unfeature' : 'Feature' ?>">
                                                <i class="bi <?= $post['is_featured'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                                            </button>
                                        </form>
                                        <form action="<?= base_url('admin/blog/delete/' . $post['id']) ?>" 
                                              method="post" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this post?');">
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

        <!-- Pagination -->
        <?php if (isset($pager)): ?>
            <?= $pager->links() ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
