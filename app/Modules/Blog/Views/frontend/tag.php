<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('title') ?>
<?= $metaTitle ?? 'Tag: ' . ($tag['name'] ?? 'Blog') ?>
<?= $this->endSection() ?>

<?= $this->section('meta') ?>
<meta name="description" content="<?= $metaDescription ?? 'Browse articles tagged with ' . ($tag['name'] ?? 'tag') ?>">
<?php if (!empty($metaKeywords)): ?>
<meta name="keywords" content="<?= esc($metaKeywords) ?>">
<?php endif; ?>
<!-- Open Graph -->
<meta property="og:title" content="<?= $metaTitle ?? 'Tag: ' . ($tag['name'] ?? 'Blog') ?>">
<meta property="og:description" content="<?= $metaDescription ?? 'Browse articles tagged with ' . ($tag['name'] ?? 'tag') ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?= current_url() ?>">
<!-- Twitter Card -->
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="<?= $metaTitle ?? 'Tag: ' . ($tag['name'] ?? 'Blog') ?>">
<meta name="twitter:description" content="<?= $metaDescription ?? 'Browse articles tagged with ' . ($tag['name'] ?? 'tag') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="bi bi-tag me-2 text-primary"></i><?= esc($tag['name'] ?? 'Tag') ?>
                </h1>
                <p class="lead text-muted">Browse articles tagged with "<?= esc($tag['name'] ?? 'tag') ?>"</p>
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
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                <article class="card mb-4 border-0 shadow-sm">
                    <?php if (!empty($post['featured_image'])): ?>
                    <a href="<?= base_url('blog/' . $post['slug']) ?>">
                        <img src="<?= esc($post['featured_image']) ?>" class="card-img-top" alt="<?= esc($post['title']) ?>" style="height: 250px; object-fit: cover;">
                    </a>
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="mb-2">
                            <?php if (!empty($post['categories'])): ?>
                                <?php foreach ($post['categories'] as $cat): ?>
                                <a href="<?= base_url('blog/category/' . $cat['slug']) ?>" class="badge bg-primary text-decoration-none">
                                    <?= esc($cat['name']) ?>
                                </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <h2 class="card-title h4">
                            <a href="<?= base_url('blog/' . $post['slug']) ?>" class="text-decoration-none text-dark">
                                <?= esc($post['title']) ?>
                            </a>
                        </h2>
                        <p class="card-text text-muted"><?= esc($post['excerpt'] ?? strip_tags(mb_substr($post['content'] ?? '', 0, 200))) ?>...</p>
                        <div class="d-flex align-items-center text-muted small">
                            <span class="me-3">
                                <i class="bi bi-calendar me-1"></i>
                                <?= date('M d, Y', strtotime($post['published_at'] ?? $post['created_at'])) ?>
                            </span>
                            <span class="me-3">
                                <i class="bi bi-eye me-1"></i>
                                <?= number_format($post['view_count'] ?? 0) ?> views
                            </span>
                            <?php if (!empty($post['author_name'])): ?>
                            <span>
                                <i class="bi bi-person me-1"></i>
                                <?= esc($post['author_name']) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
                
                <!-- Pagination -->
                <?php if (!empty($pager)): ?>
                <div class="d-flex justify-content-center">
                    <?= $pager->links() ?>
                </div>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-tag fs-1 text-muted"></i>
                    <h3 class="mt-3">No posts found</h3>
                    <p class="text-muted">There are no articles with this tag yet.</p>
                    <a href="<?= base_url('blog') ?>" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-1"></i> Back to Blog
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Categories Widget -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-folder me-2"></i>Categories</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($categories)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($categories as $category): ?>
                            <a href="<?= base_url('blog/category/' . $category['slug']) ?>" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-folder2 me-2"></i><?= esc($category['name']) ?></span>
                                <span class="badge bg-secondary rounded-pill"><?= $category['post_count'] ?? 0 ?></span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No categories yet.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Popular Tags -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-tags me-2"></i>Popular Tags</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <?php if (!empty($popularTags)): ?>
                            <?php foreach ($popularTags as $tagItem): ?>
                            <a href="<?= base_url('blog/tag/' . $tagItem['slug']) ?>" 
                               class="badge <?= (isset($tag['id']) && $tagItem['id'] === $tag['id']) ? 'bg-primary' : 'bg-light text-dark' ?> text-decoration-none">
                                <?= esc($tagItem['name']) ?>
                                <span class="badge bg-secondary"><?= $tagItem['post_count'] ?? 0 ?></span>
                            </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0">No tags yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Recent Posts -->
            <?php if (!empty($recentPosts)): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-clock me-2"></i>Recent Posts</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentPosts as $recent): ?>
                        <a href="<?= base_url('blog/' . $recent['slug']) ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?= esc($recent['title']) ?></h6>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                <?= date('M d, Y', strtotime($recent['published_at'] ?? $recent['created_at'])) ?>
                            </small>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
