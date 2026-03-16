<?= $this->extend('Modules\Blog\Views\admin\layout') ?>

<?= $this->section('content') ?>
<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('admin/blog') ?>">Dashboard</a></li>
<li class="breadcrumb-item active">Statistics</li>
<?= $this->endSection() ?>

<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-1">Blog Statistics</h4>
        <p class="text-muted mb-0">Overview of your blog performance</p>
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
                        <p class="text-muted mb-0">Featured</p>
                        <h4 class="mb-0"><?= $stats['featured_posts'] ?? 0 ?></h4>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded">
                        <i class="bi bi-star text-danger fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Total Views Row -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Total Views</p>
                        <h2 class="mb-0"><?= number_format($stats['total_views'] ?? 0) ?></h2>
                    </div>
                    <div class="bg-info bg-opacity-10 p-4 rounded">
                        <i class="bi bi-eye text-info fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Avg Views per Post</p>
                        <h2 class="mb-0"><?= number_format($stats['total_posts'] > 0 ? ($stats['total_views'] / $stats['total_posts']) : 0, 1) ?></h2>
                    </div>
                    <div class="bg-secondary bg-opacity-10 p-4 rounded">
                        <i class="bi bi-bar-chart text-secondary fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popular Posts -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0"><i class="bi bi-fire me-2 text-danger"></i>Popular Posts</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 px-4 py-3">Title</th>
                                <th class="border-0 py-3">Category</th>
                                <th class="border-0 py-3">Views</th>
                                <th class="border-0 py-3">Published</th>
                                <th class="border-0 py-3 text-end px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($popularPosts)): ?>
                                <?php foreach ($popularPosts as $post): ?>
                                    <tr>
                                        <td class="px-4 py-3">
                                            <a href="<?= base_url('admin/blog/edit/' . $post['id']) ?>" class="text-decoration-none fw-semibold">
                                                <?= esc($post['title']) ?>
                                            </a>
                                        </td>
                                        <td class="py-3">
                                            <?php if (!empty($post['category_name'])): ?>
                                                <span class="badge bg-secondary"><?= esc($post['category_name']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3">
                                            <span class="text-primary fw-semibold">
                                                <i class="bi bi-eye me-1"></i><?= number_format($post['view_count'] ?? 0) ?>
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <?php if (!empty($post['published_at'])): ?>
                                                <span class="text-muted"><?= date('M d, Y', strtotime($post['published_at'])) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 text-end px-4">
                                            <a href="<?= base_url('blog/' . $post['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-box-arrow-up-right"></i> View
                                            </a>
                                            <a href="<?= base_url('admin/blog/edit/' . $post['id']) ?>" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No posts yet. Create your first post to see statistics.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
