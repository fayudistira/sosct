<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="hero-section py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">Our Programs</h1>
        <p class="lead">Explore our comprehensive range of educational programs designed to help you achieve your goals</p>
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
                                <?= count($programsByCategory[$category]) ?>
                            </span>
                        </button>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <?php foreach ($categories as $index => $category): ?>
                <div class="tab-pane fade <?= ($category === $selectedCategory) ? 'show active' : '' ?>" 
                     id="category-<?= $index ?>" 
                     role="tabpanel">
                    
                    <!-- Category Header -->
                    <div class="text-center mb-4">
                        <h3 class="fw-bold" style="color: var(--dark-red);">
                            <i class="bi bi-grid-3x3-gap-fill me-2"></i><?= esc($category) ?>
                        </h3>
                        <p class="text-muted">
                            <?= count($programsByCategory[$category]) ?> program<?= count($programsByCategory[$category]) !== 1 ? 's' : '' ?> available in this category
                        </p>
                    </div>
                    
                    <!-- Programs Grid -->
                    <div class="row g-4">
                        <?php foreach ($programsByCategory[$category] as $program): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm program-card">
                                    <!-- Program Image -->
                                    <div class="program-image position-relative">
                                        <?php if (!empty($program['thumbnail'])): ?>
                                            <img src="<?= base_url('uploads/programs/thumbs/' . $program['thumbnail']) ?>" 
                                                 alt="<?= esc($program['title']) ?>" 
                                                 class="card-img-top"
                                                 style="height: 200px; object-fit: cover; width: 100%;">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="height: 200px;">
                                                <i class="bi bi-mortarboard" style="font-size: 3rem; color: #ccc;"></i>
                                            </div>
                                        <?php endif ?>
                                        
                                        <!-- Discount Badge -->
                                        <?php if (!empty($program['discount']) && $program['discount'] > 0): ?>
                                            <span class="badge bg-success position-absolute top-0 start-0 m-2">
                                                <i class="bi bi-tag-fill me-1"></i><?= number_format($program['discount'], 0) ?>% OFF
                                            </span>
                                        <?php endif ?>
                                        
                                        <!-- Sub Category Badge -->
                                        <?php if (!empty($program['sub_category'])): ?>
                                            <span class="badge position-absolute top-0 end-0 m-2" 
                                                  style="background-color: var(--dark-red);">
                                                <?= esc($program['sub_category']) ?>
                                            </span>
                                        <?php endif ?>
                                    </div>
                                    
                                    <div class="card-body d-flex flex-column">
                                        <!-- Program Title -->
                                        <h5 class="card-title fw-bold mb-3"><?= esc($program['title']) ?></h5>
                                        
                                        <!-- Program Description -->
                                        <p class="card-text text-muted flex-grow-1 mb-3">
                                            <?php 
                                            $description = $program['description'] ?? 'No description available';
                                            echo esc(strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description);
                                            ?>
                                        </p>
                                        
                                        <!-- Pricing -->
                                        <div class="pricing-section mb-3 pt-3" style="border-top: 1px solid #eee;">
                                            <?php if ($program['discount'] > 0): ?>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <span class="text-decoration-line-through text-muted small">
                                                        Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?>
                                                    </span>
                                                </div>
                                                <div class="h5 fw-bold mb-0" style="color: var(--dark-red);">
                                                    Rp <?= number_format($program['tuition_fee'] * (1 - $program['discount'] / 100), 0, ',', '.') ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="h5 fw-bold mb-0" style="color: var(--dark-red);">
                                                    Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?>
                                                </div>
                                            <?php endif ?>
                                            <small class="text-muted">per semester</small>
                                        </div>
                                        
                                        <!-- Action Buttons -->
                                        <div class="d-grid gap-2 mt-auto">
                                            <a href="<?= base_url('programs/' . $program['id']) ?>" 
                                               class="btn btn-outline-secondary">
                                                <i class="bi bi-info-circle me-1"></i> View Details
                                            </a>
                                            <a href="<?= base_url('apply/' . $program['id']) ?>" 
                                               class="btn btn-dark-red">
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
    <?php endif ?>
</div>

<style>
/* Category Tabs */
.category-tabs-wrapper {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 2rem 1rem;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.category-tabs {
    flex-wrap: wrap;
    gap: 1rem;
}

.category-tabs .nav-link {
    border-radius: 50px;
    padding: 1rem 2rem;
    font-weight: 600;
    color: #6c757d;
    background-color: white;
    border: 2px solid #e0e0e0;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    white-space: nowrap;
}

.category-tabs .nav-link:hover {
    background-color: var(--light-red);
    border-color: var(--dark-red);
    color: var(--dark-red);
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(139, 0, 0, 0.15);
}

.category-tabs .nav-link.active {
    background: linear-gradient(135deg, var(--dark-red) 0%, var(--medium-red) 100%);
    border-color: var(--dark-red);
    color: white;
    box-shadow: 0 6px 20px rgba(139, 0, 0, 0.3);
    transform: translateY(-3px);
}

.category-tabs .nav-link.active .badge {
    background-color: white !important;
    color: var(--dark-red) !important;
}

.category-tabs .nav-link .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

/* Tab Content Animation */
.tab-pane {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Program Card Styles */
.program-card {
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
}

.program-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(139, 0, 0, 0.15) !important;
    border-color: var(--dark-red);
}

.program-image {
    overflow: hidden;
    background-color: #f8f9fa;
}

.program-image img {
    transition: transform 0.4s ease;
}

.program-card:hover .program-image img {
    transform: scale(1.1);
}

.btn-dark-red {
    background-color: var(--dark-red);
    border-color: var(--dark-red);
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-dark-red:hover {
    background-color: var(--medium-red);
    border-color: var(--medium-red);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(139, 0, 0, 0.3);
}

.btn-outline-secondary {
    border-width: 2px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    transform: translateY(-2px);
}

.card-title {
    color: #2c3e50;
    min-height: 2.5rem;
}

.pricing-section {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    margin: 0 -1rem;
    padding-left: 1rem;
    padding-right: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .category-tabs {
        flex-direction: column;
    }
    
    .category-tabs .nav-link {
        width: 100%;
        justify-content: center;
    }
}
</style>

<?= $this->endSection() ?>
