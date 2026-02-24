<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Hero Showcase Header -->
<div class="hero-section position-relative overflow-hidden py-5 mb-0" style="background: linear-gradient(135deg, var(--dark-red) 0%, #600000 100%);">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 40%); pointer-events: none;"></div>
    <div class="container position-relative py-4 text-center">
        <div class="badge bg-white text-danger mb-3 p-2 px-3 rounded-pill shadow-sm animate-fade-in fw-bold">
            <i class="bi bi-stars me-1"></i> Temukan Kursus Terbaikmu
        </div>
        <h1 class="display-3 fw-bold text-white mb-3 animate-slide-up">Intensive <span class="text-white-50">Programs</span></h1>
        <p class="lead text-white-50 mx-auto animate-slide-up-delay-1" style="max-width: 700px;">
            Tingkatkan kemahiran berbahasa Anda secara kilat melalui program intensif spesialis kami di Kampung Inggris Pare. Didesain khusus untuk progres cepat dan hasil yang terbukti nyata.
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

<!-- Language Navigation Bar - Deep Red Header Style -->
<div class="sticky-top" id="explore" style="top: 0; z-index: 1020;">
    <?php if (!empty($languages)): ?>
        <nav class="py-2 px-2 d-flex flex-wrap gap-2 justify-content-center" style="background: linear-gradient(135deg, var(--dark-red) 0%, #600000 100%);" role="tablist">
            <?php foreach ($languages as $langIndex => $language): ?>
                <button class="btn-lang-header <?= ($language === $selectedLanguage) ? 'active' : '' ?>"
                    id="lang-tab-<?= $langIndex ?>"
                    data-bs-toggle="tab"
                    data-bs-target="#language-<?= $langIndex ?>"
                    type="button"
                    role="tab"
                    aria-controls="language-<?= $langIndex ?>"
                    aria-selected="<?= ($language === $selectedLanguage) ? 'true' : 'false' ?>">
                    <i class="bi bi-translate me-2"></i>
                    <?= esc($language) ?>
                    <span class="badge-lang"><?= $programsByLanguage[$language]['total_programs'] ?></span>
                </button>
            <?php endforeach ?>
        </nav>
    <?php endif ?>
</div>

<!-- Main Programs Content -->
<div class="container py-3">
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
                    role="tabpanel"
                    aria-labelledby="lang-tab-<?= $langIndex ?>">

                    <?php 
                    $modes = array_keys($programsByLanguage[$language]['modes']);
                    if (!empty($modes)): 
                    ?>
                        <!-- Mode Navigation - Sub Tabs Style with Underline -->
                        <div class="bg-white border-bottom">
                            <div class="d-flex gap-4 overflow-auto px-4 py-0 scrollbar-hide justify-content-center" role="tablist">
                                <?php foreach ($modes as $modeIndex => $mode): ?>
                                    <button class="sub-tab-btn position-relative <?= ($modeIndex === 0) ? 'active' : '' ?>"
                                        id="mode-tab-<?= $langIndex ?>-<?= $modeIndex ?>"
                                        data-bs-toggle="tab"
                                        data-bs-target="#mode-<?= $langIndex ?>-<?= $modeIndex ?>"
                                        type="button"
                                        role="tab"
                                        aria-controls="mode-<?= $langIndex ?>-<?= $modeIndex ?>"
                                        aria-selected="<?= ($modeIndex === 0) ? 'true' : 'false' ?>">
                                        <i class="bi bi-<?= ($mode === 'online') ? 'wifi' : 'building' ?> me-1"></i>
                                        <?= ucfirst($mode) ?>
                                        <span class="ms-1 text-muted small">(<?= $programsByLanguage[$language]['modes'][$mode]['total_programs'] ?>)</span>
                                        <?php if ($modeIndex === 0): ?>
                                            <div class="position-absolute bottom-0 start-0 end-0 h-1 bg-danger rounded-top"></div>
                                        <?php endif ?>
                                    </button>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <!-- Mode Tab Content -->
                        <div class="tab-content">
                            <?php foreach ($modes as $modeIndex => $mode): ?>
                                <div class="tab-pane fade <?= ($modeIndex === 0) ? 'show active' : '' ?>"
                                    id="mode-<?= $langIndex ?>-<?= $modeIndex ?>"
                                    role="tabpanel"
                                    aria-labelledby="mode-tab-<?= $langIndex ?>-<?= $modeIndex ?>">

                                    <?php 
                                    $categories = array_keys($programsByLanguage[$language]['modes'][$mode]['categories']);
                                    if (!empty($categories)): 
                                    ?>
                                        <!-- Category Navigation - Pill Style -->
                                        <div class="text-center mb-3 pt-3">
                                            <div class="d-inline-flex flex-wrap gap-2 justify-content-center" role="tablist">
                                                <?php foreach ($categories as $catIndex => $category): ?>
                                                    <button class="pill-tab-btn <?= ($catIndex === 0) ? 'active' : '' ?>"
                                                        id="cat-tab-<?= $langIndex ?>-<?= $modeIndex ?>-<?= $catIndex ?>"
                                                        data-bs-toggle="tab"
                                                        data-bs-target="#category-<?= $langIndex ?>-<?= $modeIndex ?>-<?= $catIndex ?>"
                                                        type="button"
                                                        role="tab"
                                                        aria-controls="category-<?= $langIndex ?>-<?= $modeIndex ?>-<?= $catIndex ?>"
                                                        aria-selected="<?= ($catIndex === 0) ? 'true' : 'false' ?>">
                                                        <?= esc($category) ?>
                                                        <span class="badge-pill"><?= $programsByLanguage[$language]['modes'][$mode]['categories'][$category]['total_programs'] ?></span>
                                                    </button>
                                                <?php endforeach ?>
                                            </div>
                                        </div>

                                        <!-- Category Tab Content -->
                                        <div class="tab-content">
                                            <?php foreach ($categories as $catIndex => $category): ?>
                                                <div class="tab-pane fade <?= ($catIndex === 0) ? 'show active' : '' ?>"
                                                    id="category-<?= $langIndex ?>-<?= $modeIndex ?>-<?= $catIndex ?>"
                                                    role="tabpanel"
                                                    aria-labelledby="cat-tab-<?= $langIndex ?>-<?= $modeIndex ?>-<?= $catIndex ?>">

                                                    <?php 
                                                    $subCategories = array_keys($programsByLanguage[$language]['modes'][$mode]['categories'][$category]['sub_categories']);
                                                    $hasMultipleSubCats = count($subCategories) > 1;
                                                    ?>
                                                    
                                                    <?php if ($hasMultipleSubCats): ?>
                                                        <!-- Sub-Category Navigation - Small Pills -->
                                                        <div class="text-center mb-3">
                                                            <div class="d-inline-flex flex-wrap gap-2 justify-content-center" role="tablist">
                                                                <?php foreach ($subCategories as $subIndex => $subCategory): ?>
                                                                    <button class="pill-tab-btn pill-tab-btn-sm <?= ($subIndex === 0) ? 'active' : '' ?>"
                                                                        id="sub-tab-<?= $langIndex ?>-<?= $modeIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>"
                                                                        data-bs-toggle="tab"
                                                                        data-bs-target="#sub-category-<?= $langIndex ?>-<?= $modeIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>"
                                                                        type="button"
                                                                        role="tab"
                                                                        aria-controls="sub-category-<?= $langIndex ?>-<?= $modeIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>"
                                                                        aria-selected="<?= ($subIndex === 0) ? 'true' : 'false' ?>">
                                                                        <?= esc($subCategory) ?>
                                                                        <span class="badge-pill"><?= count($programsByLanguage[$language]['modes'][$mode]['categories'][$category]['sub_categories'][$subCategory]) ?></span>
                                                                    </button>
                                                                <?php endforeach ?>
                                                            </div>
                                                        </div>

                                                        <!-- Sub-Category Tab Content -->
                                                        <div class="tab-content">
                                                            <?php foreach ($subCategories as $subIndex => $subCategory): ?>
                                                                <div class="tab-pane fade <?= ($subIndex === 0) ? 'show active' : '' ?>"
                                                                    id="sub-category-<?= $langIndex ?>-<?= $modeIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>"
                                                                    role="tabpanel"
                                                                    aria-labelledby="sub-tab-<?= $langIndex ?>-<?= $modeIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>">
                                                                    <!-- Programs Grid -->
                                                                    <div class="row g-4">
                                                                        <?php 
                                                                        $progs = $programsByLanguage[$language]['modes'][$mode]['categories'][$category]['sub_categories'][$subCategory];
                                                                        foreach ($progs as $program): 
                                                                            $seed = crc32($program['id']);
                                                                            $randomId = ($seed % 1000) + 1;
                                                                            $finalPrice = $program['tuition_fee'] * (1 - ($program['discount'] ?? 0) / 100);
                                                                            $shareUrl = urlencode(base_url('programs/' . $program['id']));
                                                                            $shareText = "Program: " . $program['title'] . "%0A%0A";
                                                                            $shareText .= "Registrasi: Rp " . number_format($program['registration_fee'], 0, ',', '.') . "%0A";
                                                                            $shareText .= "Biaya Kursus: Rp " . number_format($finalPrice, 0, ',', '.') . "%0A";
                                                                            if (!empty($program['discount']) && $program['discount'] > 0) {
                                                                                $shareText .= "Diskon: " . $program['discount'] . "%25";
                                                                            }
                                                                            $whatsappShareUrl = 'https://wa.me/?text=' . $shareText . '%0A%0A' . $shareUrl;
                                                                        ?>
                                                                        <div class="col-md-6 col-lg-4">
                                                                            <div class="card border-0 shadow-sm h-100 hover-lift overflow-hidden program-card-modern">
                                                                                <div class="position-relative overflow-hidden" style="height: 200px;">
                                                                                    <?php if (!empty($program['thumbnail'])): ?>
                                                                                        <img src="<?= base_url('uploads/programs/thumbs/' . $program['thumbnail']) ?>" 
                                                                                            alt="<?= esc($program['title']) ?>" 
                                                                                            class="w-100 h-100 object-fit-cover program-img-zoom">
                                                                                    <?php else: ?>
                                                                                        <img src="https://picsum.photos/seed/<?= $randomId ?>/800/600" 
                                                                                            alt="<?= esc($program['title']) ?>" 
                                                                                            class="w-100 h-100 object-fit-cover program-img-zoom" 
                                                                                            loading="lazy">
                                                                                    <?php endif ?>
                    <div class="position-absolute top-0 end-0 m-3 d-flex gap-2">
                        <?php if (auth()->loggedIn() && auth()->user()->inGroup('superadmin')): ?>
                            <a href="<?= base_url('program/edit/' . $program['id']) ?>" 
                                class="badge bg-warning text-dark shadow-sm py-2 px-3 rounded-pill fw-bold text-decoration-none" 
                                style="font-size: 0.7rem;"
                                title="Edit Program">
                                <i class="bi bi-pencil-square me-1"></i>Edit
                            </a>
                        <?php endif ?>
                                                                                        <span class="badge bg-white text-dark shadow-sm py-2 px-3 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                                                                            <?= strtoupper(esc($program['sub_category'] ?? 'Standard')) ?>
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark text-white">
                                                                                        <div class="d-flex align-items-baseline">
                                                                                            <span class="h4 fw-bold mb-0">Rp <?= number_format($finalPrice, 0, ',', '.') ?></span>
                                                                                            <?php if (!empty($program['discount']) && $program['discount'] > 0): ?>
                                                                                                <span class="ms-2 text-white-50 text-decoration-line-through small">Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?></span>
                                                                                                <span class="ms-auto badge bg-danger rounded-pill">-<?= $program['discount'] ?>%</span>
                                                                                            <?php endif ?>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="card-body d-flex flex-column p-4">
                                                                                    <h5 class="fw-bold mb-2 text-dark"><?= esc($program['title']) ?></h5>
                                                                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                                                                        <?php if (!empty($program['language'])): ?>
                                                                                            <span class="badge bg-info text-white"><i class="bi bi-translate me-1"></i><?= esc($program['language']) ?></span>
                                                                                        <?php endif ?>
                                                                                        <?php if (!empty($program['language_level'])): ?>
                                                                                            <span class="badge bg-secondary"><?= esc($program['language_level']) ?></span>
                                                                                        <?php endif ?>
                                                                                        <?php if (!empty($program['mode'])): ?>
                                                                                            <span class="badge bg-light text-muted border"><i class="bi bi-<?= ($program['mode'] === 'online' ? 'wifi' : 'building') ?> me-1"></i><?= ucfirst($program['mode']) ?></span>
                                                                                        <?php endif ?>
                                                                                    </div>
                                                                                    <p class="text-muted small flex-grow-1 mb-4">
                                                                                        <?= esc(strlen($program['description'] ?? '') > 120 ? substr($program['description'], 0, 120) . '...' : ($program['description'] ?? 'Unlock your potential with our immersive educational experience.')) ?>
                                                                                    </p>
                                                                                    <div class="d-flex align-items-center gap-2 pt-3 border-top mt-auto">
                                                                                        <a href="<?= $whatsappShareUrl ?>" target="_blank" class="btn btn-outline-success btn-sm rounded" title="Share ke WhatsApp">
                                                                                            <i class="bi bi-share"></i>
                                                                                        </a>
                                                                                        <a href="<?= base_url('programs/' . $program['id']) ?>" class="btn btn-outline-dark btn-sm rounded flex-grow-1 fw-bold">DETAILS</a>
                                                                                        <a href="<?= base_url('apply/' . $program['id']) ?>" class="btn btn-dark-red btn-sm rounded flex-grow-1 fw-bold">APPLY NOW</a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php endforeach ?>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <!-- Single sub-category, show directly -->
                                                        <div class="row g-4">
                                                            <?php 
                                                            $subCatKey = $subCategories[0] ?? 'Standard';
                                                            $progs = $programsByLanguage[$language]['modes'][$mode]['categories'][$category]['sub_categories'][$subCatKey];
                                                            foreach ($progs as $program): 
                                                                $seed = crc32($program['id']);
                                                                $randomId = ($seed % 1000) + 1;
                                                                $finalPrice = $program['tuition_fee'] * (1 - ($program['discount'] ?? 0) / 100);
                                                                $shareUrl = urlencode(base_url('programs/' . $program['id']));
                                                                $shareText = "Program: " . $program['title'] . "%0A%0A";
                                                                $shareText .= "Registrasi: Rp " . number_format($program['registration_fee'], 0, ',', '.') . "%0A";
                                                                $shareText .= "Biaya Kursus: Rp " . number_format($finalPrice, 0, ',', '.') . "%0A";
                                                                if (!empty($program['discount']) && $program['discount'] > 0) {
                                                                    $shareText .= "Diskon: " . $program['discount'] . "%25";
                                                                }
                                                                $whatsappShareUrl = 'https://wa.me/?text=' . $shareText . '%0A%0A' . $shareUrl;
                                                            ?>
                                                            <div class="col-md-6 col-lg-4">
                                                                <div class="card border-0 shadow-sm h-100 hover-lift overflow-hidden program-card-modern">
                                                                    <div class="position-relative overflow-hidden" style="height: 200px;">
                                                                        <?php if (!empty($program['thumbnail'])): ?>
                                                                            <img src="<?= base_url('uploads/programs/thumbs/' . $program['thumbnail']) ?>" 
                                                                                alt="<?= esc($program['title']) ?>" 
                                                                                class="w-100 h-100 object-fit-cover program-img-zoom">
                                                                        <?php else: ?>
                                                                            <img src="https://picsum.photos/seed/<?= $randomId ?>/800/600" 
                                                                                alt="<?= esc($program['title']) ?>" 
                                                                                class="w-100 h-100 object-fit-cover program-img-zoom" 
                                                                                loading="lazy">
                                                                                    <?php endif ?>
                    <div class="position-absolute top-0 end-0 m-3 d-flex gap-2">
                        <?php if (auth()->loggedIn() && auth()->user()->inGroup('superadmin')): ?>
                            <a href="<?= base_url('program/edit/' . $program['id']) ?>" 
                                class="badge bg-warning text-dark shadow-sm py-2 px-3 rounded-pill fw-bold text-decoration-none" 
                                style="font-size: 0.7rem;"
                                title="Edit Program">
                                <i class="bi bi-pencil-square me-1"></i>Edit
                            </a>
                        <?php endif ?>
                                                                                        <span class="badge bg-white text-dark shadow-sm py-2 px-3 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                                                                            <?= strtoupper(esc($program['sub_category'] ?? 'Standard')) ?>
                                                                                        </span>
                                                                                    </div>
                                                                        <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark text-white">
                                                                            <div class="d-flex align-items-baseline">
                                                                                <span class="h4 fw-bold mb-0">Rp <?= number_format($finalPrice, 0, ',', '.') ?></span>
                                                                                <?php if (!empty($program['discount']) && $program['discount'] > 0): ?>
                                                                                    <span class="ms-2 text-white-50 text-decoration-line-through small">Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?></span>
                                                                                    <span class="ms-auto badge bg-danger rounded-pill">-<?= $program['discount'] ?>%</span>
                                                                                <?php endif ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-body d-flex flex-column p-4">
                                                                        <h5 class="fw-bold mb-2 text-dark"><?= esc($program['title']) ?></h5>
                                                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                                                            <?php if (!empty($program['language'])): ?>
                                                                                <span class="badge bg-info text-white"><i class="bi bi-translate me-1"></i><?= esc($program['language']) ?></span>
                                                                            <?php endif ?>
                                                                            <?php if (!empty($program['language_level'])): ?>
                                                                                <span class="badge bg-secondary"><?= esc($program['language_level']) ?></span>
                                                                            <?php endif ?>
                                                                            <?php if (!empty($program['mode'])): ?>
                                                                                <span class="badge bg-light text-muted border"><i class="bi bi-<?= ($program['mode'] === 'online' ? 'wifi' : 'building') ?> me-1"></i><?= ucfirst($program['mode']) ?></span>
                                                                            <?php endif ?>
                                                                        </div>
                                                                        <p class="text-muted small flex-grow-1 mb-4">
                                                                            <?= esc(strlen($program['description'] ?? '') > 120 ? substr($program['description'], 0, 120) . '...' : ($program['description'] ?? 'Unlock your potential with our immersive educational experience.')) ?>
                                                                        </p>
                                                                        <div class="d-flex align-items-center gap-2 pt-3 border-top mt-auto">
                                                                            <a href="<?= $whatsappShareUrl ?>" target="_blank" class="btn btn-outline-success btn-sm rounded" title="Share ke WhatsApp">
                                                                                <i class="bi bi-share"></i>
                                                                            </a>
                                                                            <a href="<?= base_url('programs/' . $program['id']) ?>" class="btn btn-outline-dark btn-sm rounded flex-grow-1 fw-bold">DETAILS</a>
                                                                            <a href="<?= base_url('apply/' . $program['id']) ?>" class="btn btn-dark-red btn-sm rounded flex-grow-1 fw-bold">APPLY NOW</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
    /* ============================================
       MODERN TAB NAVIGATION SYSTEM
       Inspired by Deep Red Design
       Mobile-first, sleek, and accessible
       ============================================ */

    /* Custom Scrollbar for mobile navigation */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Language Header Tabs - Deep Red Background */
    .btn-lang-header {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        border: none;
        background: transparent;
        color: rgba(255, 255, 255, 0.7);
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-lang-header:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .btn-lang-header.active {
        background: white;
        color: var(--dark-red);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-lang-header .badge-lang {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: 0.5rem;
        padding: 0.15rem 0.5rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.2);
        color: inherit;
    }

    .btn-lang-header.active .badge-lang {
        background: var(--light-red);
        color: var(--dark-red);
    }

    /* Sub Tab Buttons - Underline Style */
    .sub-tab-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        padding: 1rem 0.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: #9ca3af;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: color 0.2s ease;
        white-space: nowrap;
    }

    .sub-tab-btn:hover {
        color: #4b5563;
    }

    .sub-tab-btn.active {
        color: var(--dark-red);
    }

    .sub-tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--dark-red);
        border-radius: 3px 3px 0 0;
    }

    /* Pill Tab Buttons - Rounded Style */
    .pill-tab-btn {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: #666;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.25s ease;
        white-space: nowrap;
    }

    .pill-tab-btn:hover {
        border-color: #d1d5db;
        background: #f9fafb;
    }

    .pill-tab-btn.active {
        background: var(--dark-red);
        color: white;
        border-color: var(--dark-red);
        box-shadow: 0 2px 8px rgba(139, 0, 0, 0.25);
    }

    .pill-tab-btn .badge-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: 0.4rem;
        padding: 0.1rem 0.45rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        background: #f3f4f6;
        color: #6b7280;
    }

    .pill-tab-btn.active .badge-pill {
        background: rgba(255, 255, 255, 0.25);
        color: white;
    }

    /* Small variant for sub-categories */
    .pill-tab-btn-sm {
        padding: 0.35rem 0.85rem;
        font-size: 0.8rem;
    }

    .pill-tab-btn-sm .badge-pill {
        font-size: 0.65rem;
        padding: 0.1rem 0.35rem;
    }

    /* Modern Card */
    .program-card-modern {
        transition: all 0.3s ease;
        border: 1px solid #eee !important;
        border-radius: 16px !important;
        overflow: hidden;
    }

    .program-card-modern:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
        border-color: #ddd !important;
    }

    .program-img-zoom {
        transition: transform 0.5s ease;
    }

    .program-card-modern:hover .program-img-zoom {
        transform: scale(1.05);
    }

    .bg-gradient-dark {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 100%);
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
            transform: translateY(5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .btn-lang-header {
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
        }

        .sub-tab-btn {
            padding: 0.75rem 0.4rem;
            font-size: 0.85rem;
        }

        .pill-tab-btn {
            padding: 0.4rem 0.85rem;
            font-size: 0.8rem;
        }

        .pill-tab-btn-sm {
            padding: 0.3rem 0.7rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .btn-lang-header {
            padding: 0.5rem 0.85rem;
            font-size: 0.8rem;
        }

        .btn-lang-header .badge-lang {
            font-size: 0.65rem;
            padding: 0.1rem 0.4rem;
        }
    }
</style>

<script>
    // Smooth scroll to explore section
    document.querySelector('a[href^="#explore"]')?.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href'))?.scrollIntoView({
            behavior: 'smooth'
        });
    });

    // Handle sub-tab button active state with underline indicator
    document.querySelectorAll('.sub-tab-btn').forEach(btn => {
        btn.addEventListener('shown.bs.tab', function(e) {
            // Remove underline from all sibling tabs
            this.closest('.d-flex').querySelectorAll('.sub-tab-btn').forEach(tab => {
                const indicator = tab.querySelector('.position-absolute.bottom-0');
                if (indicator) indicator.remove();
                tab.classList.remove('active');
            });
            // Add underline to active tab
            this.classList.add('active');
            const indicator = document.createElement('div');
            indicator.className = 'position-absolute bottom-0 start-0 end-0 h-1 bg-danger rounded-top';
            this.appendChild(indicator);
        });
    });
</script>

<?= $this->endSection() ?>
