<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="hero-section py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">FEEC Intensive Programs</h1>
        <p class="lead">Accelerate your English mastery with our specialized intensive programs in Kampung Inggris Pare.</p>
        <?php if (!empty($totalPrograms)): ?>
            <div class="mt-3">
                <span class="badge bg-white text-dark px-4 py-2 fs-6">
                    <i class="bi bi-mortarboard-fill me-2" style="color: var(--dark-red);"></i>
                    <?= $totalPrograms ?> Programs Available
                </span>
            </div>
        <?php endif ?>
    </div>
</div>

<!-- Main Content -->
<div class="container py-5">
    <?php if (session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i><?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>
    
    <?php if (empty($programsByCategory)): ?>
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
            <h3 class="mt-3">No Programs Available</h3>
            <p class="text-muted">Please check back later for available programs.</p>
        </div>
    <?php else: ?>
        <!-- Category Tabs -->
        <div class="category-tabs-wrapper mb-5">
            <ul class="nav nav-pills category-tabs justify-content-center" role="tablist">
                <?php foreach ($categories as $index => $category): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= ($category === $selectedCategory) ? 'active' : '' ?>" 
                                id="tab-<?= $index ?>" 
                                data-bs-toggle="pill" 
                                data-bs-target="#category-<?= $index ?>" 
                                type="button" 
                                role="tab">
                            <i class="bi bi-bookmark-fill me-2"></i>
                            <?= esc($category) ?>
                            <span class="badge bg-white text-dark ms-2">
                                <?= $programsByCategory[$category]['total_programs'] ?>
                            </span>
                        </button>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <?php foreach ($categories as $catIndex => $category): ?>
                <div class="tab-pane fade <?= ($category === $selectedCategory) ? 'show active' : '' ?>" 
                     id="category-<?= $catIndex ?>" 
                     role="tabpanel">
                    
                    <!-- Sub-Category Tabs (Language) -->
                    <div class="sub-category-tabs-wrapper mb-4">
                        <ul class="nav nav-pills sub-category-tabs justify-content-center" role="tablist">
                            <?php 
                            $subCats = array_keys($programsByCategory[$category]['sub_categories']);
                            foreach ($subCats as $subIndex => $subCategory): 
                            ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?= ($subIndex === 0) ? 'active' : '' ?>" 
                                            id="sub-tab-<?= $catIndex ?>-<?= $subIndex ?>" 
                                            data-bs-toggle="pill" 
                                            data-bs-target="#sub-category-<?= $catIndex ?>-<?= $subIndex ?>" 
                                            type="button" 
                                            role="tab">
                                        <?= esc($subCategory) ?>
                                        <span class="badge bg-light text-dark ms-2">
                                            <?= count($programsByCategory[$category]['sub_categories'][$subCategory]) ?>
                                        </span>
                                    </button>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    </div>

                    <!-- Sub-Tab Content -->
                    <div class="tab-content">
                        <?php foreach ($subCats as $subIndex => $subCategory): ?>
                            <div class="tab-pane fade <?= ($subIndex === 0) ? 'show active' : '' ?>" 
                                 id="sub-category-<?= $catIndex ?>-<?= $subIndex ?>" 
                                 role="tabpanel">
                                
                                <!-- Programs Grid -->
                                <div class="row g-4">
                                    <?php foreach ($programsByCategory[$category]['sub_categories'][$subCategory] as $program): ?>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card h-100 shadow-sm program-card">
                                                <!-- Program Image -->
                                                <div class="program-image position-relative">
                                                    <?php if (!empty($program['thumbnail'])): ?>
                                                        <img src="<?= base_url('uploads/programs/thumbs/' . $program['thumbnail']) ?>" 
                                                             alt="<?= esc($program['title']) ?>" 
                                                             class="card-img-top">
                                                    <?php else: ?>
                                                        <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                            <i class="bi bi-mortarboard" style="font-size: 3rem; color: #ccc;"></i>
                                                        </div>
                                                    <?php endif ?>
                                                    
                                                    <!-- Discount Badge -->
                                                    <?php if (!empty($program['discount']) && $program['discount'] > 0): ?>
                                                        <span class="badge bg-success position-absolute top-0 start-0 m-2">
                                                            <i class="bi bi-tag-fill me-1"></i><?= number_format($program['discount'], 0) ?>% OFF
                                                        </span>
                                                    <?php endif ?>

                                                    <!-- Program Info Overlay -->
                                                    <div class="program-overlay">
                                                        <h5 class="card-title fw-bold mb-1"><?= esc($program['title']) ?></h5>
                                                        <div class="d-flex gap-1 flex-wrap">
                                                            <span class="badge-meta"><?= esc($category) ?></span>
                                                            <span class="badge-meta"><?= esc($subCategory) ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="card-body d-flex flex-column">
                                                    <p class="card-text text-muted flex-grow-1 mb-3">
                                                        <?php 
                                                        $description = $program['description'] ?? 'No description available';
                                                        echo esc(strlen($description) > 90 ? substr($description, 0, 90) . '...' : $description);
                                                        ?>
                                                    </p>
                                                    
                                                    <div class="pricing-section mb-3">
                                                        <div class="d-flex justify-content-between align-items-end">
                                                            <div>
                                                                <?php if ($program['discount'] > 0): ?>
                                                                    <div class="text-decoration-line-through text-muted extra-small">
                                                                        Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?>
                                                                    </div>
                                                                    <div class="h5 fw-bold mb-0" style="color: var(--dark-red);">
                                                                        Rp <?= number_format($program['tuition_fee'] * (1 - $program['discount'] / 100), 0, ',', '.') ?>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="h5 fw-bold mb-0" style="color: var(--dark-red);">
                                                                        Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?>
                                                                    </div>
                                                                <?php endif ?>
                                                                <small class="text-muted extra-small">per semester</small>
                                                            </div>
                                                            <div class="h-100 d-flex gap-1">
                                                                <a href="<?= base_url('programs/' . $program['id']) ?>" 
                                                                   class="btn-icon" title="View Details">
                                                                    <i class="bi bi-arrow-right"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="d-grid mt-auto">
                                                        <a href="<?= base_url('apply/' . $program['id']) ?>" 
                                                           class="btn btn-dark-red btn-sm">
                                                            <i class="bi bi-pencil-square me-1"></i> Apply Now
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</div>

<style>
/* Category Tabs */
.category-tabs-wrapper {
    background: #ffffff;
    padding: 1rem 0;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 2rem !important;
}

.category-tabs {
    gap: 0.5rem;
}

.category-tabs .nav-link {
    border-radius: 8px;
    padding: 0.6rem 1.25rem;
    font-weight: 500;
    color: #555;
    background-color: #f8f9fa;
    border: 1px solid #eee;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 0.9rem;
}

.category-tabs .nav-link:hover {
    background-color: #f0f0f0;
    color: var(--dark-red);
    transform: translateY(-1px);
}

.category-tabs .nav-link.active {
    background: var(--dark-red);
    border-color: var(--dark-red);
    color: white;
    box-shadow: 0 4px 12px rgba(139, 0, 0, 0.2);
}

.category-tabs .nav-link .badge {
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
    background-color: rgba(0,0,0,0.1) !important;
    color: inherit !important;
}

.category-tabs .nav-link.active .badge {
    background-color: rgba(255,255,255,0.2) !important;
}

/* Sub-Category Tabs */
.sub-category-tabs-wrapper {
    background-color: #fcfcfc;
    padding: 0.75rem;
    border-radius: 10px;
    border: 1px solid #f0f0f0;
    margin-bottom: 1.5rem !important;
}

.sub-category-tabs {
    gap: 0.25rem;
}

.sub-category-tabs .nav-link {
    border-radius: 6px;
    padding: 0.4rem 1rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: #666;
    background-color: transparent;
    border: 1px solid transparent;
}

.sub-category-tabs .nav-link:hover {
    color: var(--dark-red);
    background-color: rgba(139, 0, 0, 0.04);
}

.sub-category-tabs .nav-link.active {
    background-color: white;
    color: var(--dark-red);
    border-color: #eee;
    box-shadow: 0 2px 4px rgba(0,0,0,0.03);
}

/* Tab Content Animation */
.tab-pane {
    animation: fadeInSlide 0.3s ease-out;
}

@keyframes fadeInSlide {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Program Card Styles */
.program-card {
    transition: all 0.25s ease;
    border: 1px solid #f0f0f0;
    border-radius: 10px;
    overflow: hidden;
    background: white;
}

.program-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.06) !important;
    border-color: #e0e0e0;
}

.program-image {
    position: relative;
    aspect-ratio: 16/9;
}

.program-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.program-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 1rem;
    background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 100%);
    color: white;
}

.program-overlay .card-title {
    font-size: 1rem;
    color: white;
    margin-bottom: 0.25rem;
    text-shadow: 0 1px 3px rgba(0,0,0,0.5);
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.badge-meta {
    font-size: 0.65rem;
    padding: 0.2rem 0.5rem;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 4px;
    color: #eee;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-text {
    font-size: 0.85rem;
    line-height: 1.5;
}

.pricing-section {
    background-color: #fafafa;
    padding: 0.6rem 0.8rem;
    border-radius: 8px;
    border: 1px solid #f0f0f0;
}

.extra-small {
    font-size: 0.7rem;
}

.btn-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: white;
    color: var(--dark-red);
    border: 1px solid #eee;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-icon:hover {
    background: var(--dark-red);
    color: white;
    border-color: var(--dark-red);
    transform: scale(1.1);
}

.btn-dark-red {
    background-color: var(--dark-red);
    border: none;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Responsive */
@media (max-width: 768px) {
    .category-tabs, .sub-category-tabs {
        overflow-x: auto;
        flex-wrap: nowrap;
        justify-content: flex-start !important;
        padding-bottom: 0.5rem;
    }
    .category-tabs .nav-item, .sub-category-tabs .nav-item {
        flex: 0 0 auto;
    }
}
</style>

<?= $this->endSection() ?>
