<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Hero Showcase Header -->
<div class="hero-section position-relative overflow-hidden py-5 mb-0" style="background: linear-gradient(135deg, var(--dark-red) 0%, #600000 100%);">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 40%); pointer-events: none;"></div>
    <div class="container position-relative py-4 text-center">
        <div class="badge bg-white text-danger mb-3 p-2 px-3 rounded-pill shadow-sm animate-fade-in fw-bold">
            <i class="bi bi-stars me-1"></i> DISCOVER YOUR PATH
        </div>
        <h1 class="display-3 fw-bold text-white mb-3 animate-slide-up">Intensive <span class="text-white-50">Programs</span></h1>
        <p class="lead text-white-50 mx-auto animate-slide-up-delay-1" style="max-width: 700px;">
            Accelerate your English mastery with our specialized intensive programs in Kampung Inggris Pare. Engineered for rapid progress and real-world results.
        </p>
        
        <?php if (!empty($totalPrograms)): ?>
            <div class="mt-4 animate-slide-up-delay-2">
                <div class="d-inline-flex align-items-center bg-white bg-opacity-10 border border-white border-opacity-20 rounded-pill p-1 ps-3 shadow-lg">
                    <span class="text-white small fw-semibold me-3">Explore <?= $totalPrograms ?> Certified Tracks</span>
                    <a href="#explore" class="btn btn-light rounded-pill btn-sm px-3 fw-bold">Start Exploring</a>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>

<!-- Category Navigation Bar -->
<div class="bg-white sticky-top shadow-sm border-bottom py-2" id="explore" style="top: 0; z-index: 1020;">
    <div class="container">
        <?php if (!empty($categories)): ?>
            <ul class="nav nav-pills nav-fill category-pill-nav gap-2" role="tablist">
                <?php foreach ($categories as $index => $category): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill border <?= ($category === $selectedCategory) ? 'active' : '' ?>" 
                                id="tab-<?= $index ?>" 
                                data-bs-toggle="pill" 
                                data-bs-target="#category-<?= $index ?>" 
                                type="button" 
                                role="tab">
                            <span class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-grid-fill me-2 small"></i>
                                <?= esc($category) ?>
                                <span class="ms-2 opacity-50 small">(<?= $programsByCategory[$category]['total_programs'] ?>)</span>
                            </span>
                        </button>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>
    </div>
</div>

<!-- Main Programs Content -->
<div class="container py-5">
    <?php if (session('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i><?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>
    
    <?php if (empty($programsByCategory)): ?>
        <div class="text-center py-5">
            <div class="feature-icon mb-4 mx-auto" style="width: 100px; height: 100px; font-size: 3rem; background: var(--light-red); color: var(--dark-red);">
                <i class="bi bi-search"></i>
            </div>
            <h3 class="fw-bold">No Programs Found</h3>
            <p class="text-muted">We couldn't find any programs in this category. Please try another selection.</p>
            <a href="<?= base_url('programs') ?>" class="btn btn-dark-red rounded-pill mt-3">Reset Filters</a>
        </div>
    <?php else: ?>
        
        <!-- Tab Content -->
        <div class="tab-content" id="pills-tabContent">
            <?php foreach ($categories as $catIndex => $category): ?>
                <div class="tab-pane fade <?= ($category === $selectedCategory) ? 'show active' : '' ?>" 
                     id="category-<?= $catIndex ?>" 
                     role="tabpanel">
                    
                    <!-- Sub-Category Filter -->
                    <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                        <h4 class="fw-bold mb-0 me-4"><?= esc($category) ?></h4>
                        <div class="nav nav-pills sub-category-pills d-flex gap-2" role="tablist">
                            <?php 
                            $subCats = array_keys($programsByCategory[$category]['sub_categories']);
                            foreach ($subCats as $subIndex => $subCategory): 
                            ?>
                                <button class="nav-link btn btn-sm rounded-pill btn-sub-cat <?= ($subIndex === 0) ? 'active' : '' ?>" 
                                        id="sub-tab-<?= $catIndex ?>-<?= $subIndex ?>"
                                        data-bs-toggle="pill" 
                                        data-bs-target="#sub-category-<?= $catIndex ?>-<?= $subIndex ?>" 
                                        type="button" 
                                        role="tab"
                                        aria-controls="sub-category-<?= $catIndex ?>-<?= $subIndex ?>"
                                        aria-selected="<?= ($subIndex === 0) ? 'true' : 'false' ?>">
                                    <?= esc($subCategory) ?>
                                    <span class="ms-1 opacity-50 small">(<?= count($programsByCategory[$category]['sub_categories'][$subCategory]) ?>)</span>
                                </button>
                            <?php endforeach ?>
                        </div>
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
                                            <div class="card border-0 shadow-sm h-100 hover-lift overflow-hidden program-card-modern">
                                                <!-- Image Container -->
                                                <div class="position-relative overflow-hidden" style="height: 200px;">
                                                    <?php if (!empty($program['thumbnail'])): ?>
                                                        <img src="<?= base_url('uploads/programs/thumbs/' . $program['thumbnail']) ?>" 
                                                             alt="<?= esc($program['title']) ?>" 
                                                             class="w-100 h-100 object-fit-cover program-img-zoom">
                                                    <?php else: ?>
                                                        <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted opacity-50">
                                                            <i class="bi bi-image display-4"></i>
                                                        </div>
                                                    <?php endif ?>
                                                    
                                                    <!-- Category Overlay -->
                                                    <div class="position-absolute top-0 end-0 m-3">
                                                        <span class="badge bg-white text-dark shadow-sm py-2 px-3 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                                            <?= strtoupper(esc($subCategory)) ?>
                                                        </span>
                                                    </div>

                                                    <!-- Price Overlay -->
                                                    <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark text-white">
                                                        <div class="d-flex align-items-baseline">
                                                            <span class="h4 fw-bold mb-0">Rp <?= number_format($program['tuition_fee'] * (1 - ($program['discount'] ?? 0) / 100), 0, ',', '.') ?></span>
                                                            <?php if (!empty($program['discount']) && $program['discount'] > 0): ?>
                                                                <span class="ms-2 text-white-50 text-decoration-line-through small">Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?></span>
                                                                <span class="ms-auto badge bg-danger rounded-pill">-<?= $program['discount'] ?>%</span>
                                                            <?php endif ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="card-body d-flex flex-column p-4">
                                                    <h5 class="fw-bold mb-2 text-dark"><?= esc($program['title']) ?></h5>
                                                    <p class="text-muted small flex-grow-1 mb-4">
                                                        <?= esc(strlen($program['description'] ?? '') > 120 ? substr($program['description'], 0, 120) . '...' : ($program['description'] ?? 'Unlock your potential with our immersive educational experience.')) ?>
                                                    </p>
                                                    
                                                    <div class="d-flex align-items-center gap-3 pt-3 border-top mt-auto">
                                                        <a href="<?= base_url('programs/' . $program['id']) ?>" class="btn btn-outline-dark btn-sm rounded-pill flex-grow-1 fw-bold">
                                                            DETAILS
                                                        </a>
                                                        <a href="<?= base_url('apply/' . $program['id']) ?>" class="btn btn-dark-red btn-sm rounded-pill flex-grow-1 fw-bold">
                                                            APPLY NOW
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

<!-- Extra Showcase Divider -->
<div class="container py-5">
    <div class="bg-light rounded-5 p-5 border shadow-sm">
        <div class="row align-items-center g-4 text-center text-lg-start">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-2" style="color: var(--dark-red);">Can't find what you're looking for?</h3>
                <p class="text-muted mb-0">Our program advisors are available to help you choose the best track for your career goals. We offer custom corporate packages and group discounts.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="<?= base_url('contact') ?>" class="btn btn-outline-dark rounded-pill px-4 fw-bold">CONTACT COUNSELOR</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Nav Styling */
    .category-pill-nav .nav-link {
        color: #666;
        background: white;
        border-color: #eee !important;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        padding: 0.8rem 1.5rem;
    }
    
    .category-pill-nav .nav-link.active {
        background: var(--dark-red) !important;
        color: white !important;
        border-color: var(--dark-red) !important;
        box-shadow: 0 4px 15px rgba(139, 0, 0, 0.2);
        transform: translateY(-2px);
    }
    
    .category-pill-nav .nav-link:hover:not(.active) {
        background: #f8f9fa;
        border-color: #ddd !important;
        transform: translateY(-1px);
    }

    /* Sub-cat Pills */
    .btn-sub-cat {
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.4rem 1.2rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border-width: 2px;
        color: #6c757d;
        border: 2px solid #6c757d !important;
        background: transparent;
    }

    .btn-sub-cat.active {
        background-color: #212529 !important;
        color: white !important;
        border-color: #212529 !important;
    }

    .btn-sub-cat:hover:not(.active) {
        background-color: #f8f9fa;
        color: #212529;
        border-color: #212529 !important;
    }
    
    /* Modern Card */
    .program-card-modern {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid #f0f0f0 !important;
    }
    
    .program-card-modern:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    
    .program-img-zoom {
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    
    .program-card-modern:hover .program-img-zoom {
        transform: scale(1.1);
    }
    
    .bg-gradient-dark {
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
    }

    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
    }

    /* Tab Content Animation */
    .tab-pane {
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Animations from home page */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-slide-up { animation: fadeInUp 0.8s ease-out forwards; }
    .animate-slide-up-delay-1 { animation: fadeInUp 0.8s ease-out 0.2s forwards; opacity: 0; }
    .animate-slide-up-delay-2 { animation: fadeInUp 0.8s ease-out 0.4s forwards; opacity: 0; }
    .animate-fade-in { animation: fadeIn 1s ease-out forwards; }

    .rounded-5 { border-radius: 2rem !important; }
    
    /* Sticky nav adjustments */
    #explore {
        transition: all 0.3s;
    }
</style>

<script>
    // Pure CSS now handles the sub-tab appearance via .active class

    // Smooth scroll for explore button
    document.querySelector('a[href^="#"]').addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
</script>

<?= $this->endSection() ?>
