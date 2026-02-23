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
            Accelerate your language mastery with our specialized intensive programs in Kampung Inggris Pare. Engineered for rapid progress and real-world results.
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

<!-- Language Navigation Bar -->
<div class="bg-white sticky-top shadow-sm border-bottom py-2" id="explore" style="top: 0; z-index: 1020;">
    <div class="container">
        <?php if (!empty($languages)): ?>
            <ul class="nav nav-pills nav-fill language-pill-nav gap-2" role="tablist">
                <?php foreach ($languages as $langIndex => $language): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill border <?= ($language === $selectedLanguage) ? 'active' : '' ?>"
                            id="lang-tab-<?= $langIndex ?>"
                            data-bs-toggle="pill"
                            data-bs-target="#language-<?= $langIndex ?>"
                            type="button"
                            role="tab">
                            <span class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-translate me-2 small"></i>
                                <?= esc($language) ?>
                                <span class="ms-2 opacity-50 small">(<?= $programsByLanguage[$language]['total_programs'] ?>)</span>
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

    <?php if (empty($programsByLanguage)): ?>
        <div class="text-center py-5">
            <div class="feature-icon mb-4 mx-auto" style="width: 100px; height: 100px; font-size: 3rem; background: var(--light-red); color: var(--dark-red);">
                <i class="bi bi-search"></i>
            </div>
            <h3 class="fw-bold">No Programs Found</h3>
            <p class="text-muted">We couldn't find any programs. Please try another selection.</p>
            <a href="<?= base_url('programs') ?>" class="btn btn-dark-red rounded-pill mt-3">Reset Filters</a>
        </div>
    <?php else: ?>

        <!-- Tab Content for Languages -->
        <div class="tab-content" id="languageTabContent">
            <?php foreach ($languages as $langIndex => $language): ?>
                <div class="tab-pane fade <?= ($language === $selectedLanguage) ? 'show active' : '' ?>"
                    id="language-<?= $langIndex ?>"
                    role="tabpanel">

                    <?php 
                    $categories = array_keys($programsByLanguage[$language]['categories']);
                    if (!empty($categories)): 
                        $firstCategory = true;
                    ?>
                        <!-- Category Navigation for this Language -->
                        <div class="d-flex align-items-center mb-4 pb-2 border-bottom flex-wrap gap-2">
                            <h4 class="fw-bold mb-0 me-3"><?= esc($language) ?></h4>
                            <div class="nav nav-pills category-pills d-flex gap-2" role="tablist">
                                <?php foreach ($categories as $catIndex => $category): ?>
                                    <button class="nav-link btn btn-sm rounded-pill btn-cat <?= ($catIndex === 0) ? 'active' : '' ?>"
                                        id="cat-tab-<?= $langIndex ?>-<?= $catIndex ?>"
                                        data-bs-toggle="pill"
                                        data-bs-target="#category-<?= $langIndex ?>-<?= $catIndex ?>"
                                        type="button"
                                        role="tab">
                                        <?= esc($category) ?>
                                        <span class="ms-1 opacity-50 small">(<?= $programsByLanguage[$language]['categories'][$category]['total_programs'] ?>)</span>
                                    </button>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <!-- Category Tab Content -->
                        <div class="tab-content">
                            <?php foreach ($categories as $catIndex => $category): ?>
                                <div class="tab-pane fade <?= ($catIndex === 0) ? 'show active' : '' ?>"
                                    id="category-<?= $langIndex ?>-<?= $catIndex ?>"
                                    role="tabpanel">

                                    <?php 
                                    $subCategories = array_keys($programsByLanguage[$language]['categories'][$category]['sub_categories']);
                                    $hasMultipleSubCats = count($subCategories) > 1;
                                    ?>
                                    
                                    <?php if ($hasMultipleSubCats): ?>
                                        <!-- Sub-Category Navigation -->
                                        <div class="d-flex align-items-center mb-3 pb-2 flex-wrap gap-2">
                                            <span class="text-muted small fw-semibold me-2">Type:</span>
                                            <div class="nav nav-pills sub-category-pills d-flex gap-2" role="tablist">
                                                <?php foreach ($subCategories as $subIndex => $subCategory): ?>
                                                    <button class="nav-link btn btn-sm rounded-pill btn-sub-cat <?= ($subIndex === 0) ? 'active' : '' ?>"
                                                        id="sub-tab-<?= $langIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>"
                                                        data-bs-toggle="pill"
                                                        data-bs-target="#sub-category-<?= $langIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>"
                                                        type="button"
                                                        role="tab">
                                                        <?= esc($subCategory) ?>
                                                        <span class="ms-1 opacity-50 small">(<?= count($programsByLanguage[$language]['categories'][$category]['sub_categories'][$subCategory]) ?>)</span>
                                                    </button>
                                                <?php endforeach ?>
                                            </div>
                                        </div>

                                        <!-- Sub-Category Tab Content -->
                                        <div class="tab-content">
                                            <?php foreach ($subCategories as $subIndex => $subCategory): ?>
                                                <div class="tab-pane fade <?= ($subIndex === 0) ? 'show active' : '' ?>"
                                                    id="sub-category-<?= $langIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>"
                                                    role="tabpanel">
                                                    <!-- Programs Grid -->
                                                    <div class="row g-4">
                                                        <?php foreach ($programsByLanguage[$language]['categories'][$category]['sub_categories'][$subCategory] as $program): ?>
                                                            <?= $this->include('Modules\Frontend\Views\Programs\_program_card', ['program' => $program]) ?>
                                                        <?php endforeach ?>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    <?php else: ?>
                                        <!-- Single sub-category, show directly -->
                                        <div class="row g-4">
                                            <?php foreach ($programsByLanguage[$language]['categories'][$category]['sub_categories'][$subCategories[0] ?? 'Standard'] as $program): ?>
                                                <?= $this->include('Modules\Frontend\Views\Programs\_program_card', ['program' => $program]) ?>
                                            <?php endforeach ?>
                                        </div>
                                    <?php endif ?>
                                </div>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>
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
                <h3 class="fw-bold mb-2" style="color: var(--dark-red);">Masih bingung memilih program yang tepat?</h3>
                <p class="text-muted mb-0">Admin kami siap membantu Anda memilih program yang sesuai dengan kebutuhan Anda.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <?php
                $whatsappMessage = "Halo Admin SOS,%0A%0ASaya ingin bertanya mengenai program kursus di SOS.%0AMohon bantuannya untuk memilih program yang tepat.%0A%0ATerima kasih.";
                $whatsappNumber = "6285810310950";
                $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMessage}";
                ?>
                <a href="<?= $whatsappUrl ?>" target="_blank" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
                    <i class="bi bi-whatsapp me-2"></i>Hubungi Admin
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Language Nav Styling */
    .language-pill-nav .nav-link {
        color: #666;
        background: white;
        border-color: #eee !important;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        padding: 0.8rem 1.5rem;
    }

    .language-pill-nav .nav-link.active {
        background: var(--dark-red) !important;
        color: white !important;
        border-color: var(--dark-red) !important;
        box-shadow: 0 4px 15px rgba(139, 0, 0, 0.2);
        transform: translateY(-2px);
    }

    .language-pill-nav .nav-link:hover:not(.active) {
        background: #f8f9fa;
        border-color: #ddd !important;
        transform: translateY(-1px);
    }

    /* Category Pills */
    .btn-cat {
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.5rem 1.2rem;
        letter-spacing: 0.5px;
        color: #495057;
        border: 2px solid #dee2e6 !important;
        background: white;
    }

    .btn-cat.active {
        background-color: var(--dark-red) !important;
        color: white !important;
        border-color: var(--dark-red) !important;
    }

    .btn-cat:hover:not(.active) {
        background-color: #f8f9fa;
        border-color: #adb5bd !important;
    }

    /* Sub-cat Pills */
    .btn-sub-cat {
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.4rem 1rem;
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
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
    }

    .program-img-zoom {
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .program-card-modern:hover .program-img-zoom {
        transform: scale(1.1);
    }

    .bg-gradient-dark {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, transparent 100%);
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
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-up {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    .animate-slide-up-delay-1 {
        animation: fadeInUp 0.8s ease-out 0.2s forwards;
        opacity: 0;
    }

    .animate-slide-up-delay-2 {
        animation: fadeInUp 0.8s ease-out 0.4s forwards;
        opacity: 0;
    }

    .animate-fade-in {
        animation: fadeIn 1s ease-out forwards;
    }

    .rounded-5 {
        border-radius: 2rem !important;
    }

    #explore {
        transition: all 0.3s;
    }
</style>

<script>
    document.querySelector('a[href^="#explore"]')?.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href'))?.scrollIntoView({
            behavior: 'smooth'
        });
    });
</script>

<?= $this->endSection() ?>
