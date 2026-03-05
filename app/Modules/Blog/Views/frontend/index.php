<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('title') ?>
<?= $metaTitle ?? 'Blog - Educational Articles & Tutorials' ?>
<?= $this->endSection() ?>

<?= $this->section('meta') ?>
<meta name="description" content="<?= $metaDescription ?? 'Read the latest educational articles, tutorials, and learning resources.' ?>">
<?php if (!empty($metaKeywords)): ?>
<meta name="keywords" content="<?= esc($metaKeywords) ?>">
<?php endif; ?>
<!-- Open Graph -->
<meta property="og:title" content="<?= $metaTitle ?? 'Blog' ?>">
<meta property="og:description" content="<?= $metaDescription ?? 'Educational articles and tutorials' ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?= current_url() ?>">
<!-- Twitter Card -->
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="<?= $metaTitle ?? 'Blog' ?>">
<meta name="twitter:description" content="<?= $metaDescription ?? 'Educational articles and tutorials' ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Educational Blog</h1>
                <p class="lead text-muted">Discover learning resources, tutorials, and educational articles to enhance your knowledge.</p>
            </div>
            <div class="col-lg-4">
                <form action="<?= base_url('blog/search') ?>" method="get" class="d-flex">
                    <input type="text" name="q" class="form-control me-2" placeholder="Search articles..." 
                           value="<?= service('request')->getGet('q') ?? '' ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Featured Posts -->
            <?php if (!empty($featuredPosts) && (config('Blog')->enableFeaturedPosts ?? true)): ?>
            <div class="row mb-5">
                <div class="col-12">
                    <h4 class="mb-4"><i class="bi bi-star-fill text-warning me-2"></i>Featured Articles</h4>
                    <div class="row">
                        <?php foreach ($featuredPosts as $featured): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <?php if (!empty($featured['featured_image'])): ?>
                                <img src="<?= esc($featured['featured_image']) ?>" class="card-img-top" alt="<?= esc($featured['title']) ?>" style="height: 200px; object-fit: cover;">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="<?= base_url('blog/' . $featured['slug']) ?>" class="text-decoration-none text-dark">
                                            <?= esc($featured['title']) ?>
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted small">
                                        <?= esc($featured['excerpt'] ?? substr(strip_tags($featured['content']), 0, 100)) ?>...
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">
                                            <i class="bi bi-clock me-1"></i><?= $featured['reading_time'] ?? 5 ?> min read
                                        </span>
                                        <span class="text-muted small">
                                            <i class="bi bi-calendar me-1"></i><?= date('M d, Y', strtotime($featured['published_at'])) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Latest Posts -->
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center mb-4">
                    <h4>Latest Articles</h4>
                </div>
            </div>

            <?php if (empty($posts)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i> No blog posts available yet. Check back soon!
            </div>
            <?php else: ?>
            <div class="row">
                <?php foreach ($posts as $post): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <?php if (!empty($post['featured_image'])): ?>
                        <a href="<?= base_url('blog/' . $post['slug']) ?>">
                            <img src="<?= esc($post['featured_image']) ?>" class="card-img-top" alt="<?= esc($post['title']) ?>" style="height: 200px; object-fit: cover;">
                        </a>
                        <?php endif; ?>
                        <div class="card-body">
                            <?php if (!empty($post['category_name'])): ?>
                            <a href="<?= base_url('blog/category/' . $post['category_slug']) ?>" class="badge bg-primary text-decoration-none mb-2">
                                <?= esc($post['category_name']) ?>
                            </a>
                            <?php endif; ?>
                            <h5 class="card-title">
                                <a href="<?= base_url('blog/' . $post['slug']) ?>" class="text-decoration-none text-dark">
                                    <?= esc($post['title']) ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted">
                                <?= esc($post['excerpt'] ?? substr(strip_tags($post['content']), 0, 100)) ?>...
                            </p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">
                                    <i class="bi bi-person me-1"></i><?= esc($post['author_name'] ?? 'Admin') ?>
                                </span>
                                <span class="text-muted small">
                                    <i class="bi bi-clock me-1"></i><?= $post['reading_time'] ?? 5 ?> min
                                </span>
                            </div>
                            <div class="text-muted small mt-2">
                                <i class="bi bi-calendar me-1"></i><?= date('M d, Y', strtotime($post['published_at'])) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if (isset($pager)): ?>
            <div class="d-flex justify-content-center mt-4">
                <?= $pager->links() ?>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Categories -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-folder me-2"></i>Categories</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($categories)): ?>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($categories as $category): ?>
                        <li class="mb-2">
                            <a href="<?= base_url('blog/category/' . $category['slug']) ?>" class="text-decoration-none d-flex justify-content-between align-items-center">
                                <?= esc($category['name']) ?>
                                <span class="badge bg-secondary"><?= $category['post_count'] ?? 0 ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p class="text-muted mb-0">No categories yet</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Popular Tags -->
            <?php if (!empty($popularTags)): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-tags me-2"></i>Popular Tags</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($popularTags as $tag): ?>
                        <a href="<?= base_url('blog/tag/' . $tag['slug']) ?>" class="badge bg-light text-dark text-decoration-none">
                            <?= esc($tag['name']) ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Recent Posts -->
            <?php if (!empty($recentPosts)): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Articles</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($recentPosts as $recent): ?>
                    <div class="mb-3">
                        <a href="<?= base_url('blog/' . $recent['slug']) ?>" class="text-decoration-none">
                            <h6 class="mb-1"><?= esc($recent['title']) ?></h6>
                        </a>
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i><?= date('M d, Y', strtotime($recent['published_at'])) ?>
                        </small>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
