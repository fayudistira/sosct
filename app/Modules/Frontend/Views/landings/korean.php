<?php
/**
 * Korean Landing Page
 * 
 * Page-specific SEO and content for Korean language course
 */

// Page-specific variables for SEO
$pageTitle = 'Kursus Bahasa Korea Kampung Inggris Pare - SOS Course | TOPIK';
$pageDescription = 'Kursus Bahasa Korea terbaik di Kampung Inggris Pare, Kediri. Spesialis TOPIK I-II. Persiapan K-Pop, kerja, dan kuliah di Korea Selatan dengan native speaker.';
$pageKeywords = 'kursus bahasa korea pare, Kampung Inggris Pare, les korea kediri, kursus korea pare, belajar korea di pare, kursus bahasa korea bersertifikat, topik pare, k-pop, kerja korea, kuliah korea, sos course';
$ogImage = 'https://images.pexels.com/photos/3403138/pexels-photo-3403138.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2';
?>

<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('extra_head') ?>
<!-- Page-specific styles for Korean landing -->
<style>
    /* Custom Scrollbar for mobile navigation */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Language Header Tabs - Orange Background */
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
        color: #f77f00;
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
        background: #ffe0b2;
        color: #f77f00;
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
        background: #f77f00;
        color: white;
        border-color: #f77f00;
        box-shadow: 0 2px 8px rgba(247, 127, 0, 0.25);
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

    /* Table Section Tabs - Rounded Corner Full Width */
    .table-tab-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: #666;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.25s ease;
        width: 100%;
    }

    .table-tab-btn:hover {
        border-color: #f77f00;
        background: #fff8f0;
    }

    .table-tab-btn.active {
        background: #f77f00;
        color: white;
        border-color: #f77f00;
        box-shadow: 0 4px 12px rgba(247, 127, 0, 0.25);
    }

    .table-tab-btn .badge-table {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: 0.5rem;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #f3f4f6;
        color: #6b7280;
    }

    .table-tab-btn.active .badge-table {
        background: rgba(255, 255, 255, 0.25);
        color: white;
    }

    /* Table Section Category Tabs - Rounded Corner */
    .table-cat-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1.25rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: #666;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .table-cat-btn:hover {
        border-color: #f77f00;
        background: #fff8f0;
    }

    .table-cat-btn.active {
        background: #f77f00;
        color: white;
        border-color: #f77f00;
        box-shadow: 0 2px 8px rgba(247, 127, 0, 0.2);
    }

    .table-cat-btn .badge-table {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: 0.4rem;
        padding: 0.15rem 0.5rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        background: #f3f4f6;
        color: #6b7280;
    }

    .table-cat-btn.active .badge-table {
        background: rgba(255, 255, 255, 0.25);
        color: white;
    }

    /* Mobile Program Cards */
    .mobile-program-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 1rem;
        padding: 1rem;
        border: 1px solid #f0f0f0;
    }

    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
    .bg-gradient-danger {
        background: linear-gradient(135deg, #f77f00 0%, #dc2f02 100%);
    }
    .program-card-modern {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid #f0f0f0 !important;
        border-radius: 16px !important;
        overflow: hidden;
    }
    .program-card-modern:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        border-color: #ddd !important;
    }
    .program-img-zoom {
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .program-card-modern:hover .program-img-zoom {
        transform: scale(1.05);
    }
    .bg-gradient-dark {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 100%);
    }
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
    .feature-icon-orange {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #f77f00 0%, #dc2f02 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 1.5rem;
    }
    .section-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #f77f00 0%, #dc2f02 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        margin-right: 12px;
    }
    .reason-icon {
        width: 56px;
        height: 56px;
        background: #fff3e0;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f77f00;
        font-size: 1.5rem;
        margin-bottom: 16px;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .btn-lang-header {
            padding: 0.6rem 1rem;
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
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="hero-section position-relative overflow-hidden py-5" style="min-height: 70vh;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 10% 20%, rgba(247, 127, 0, 0.2) 0%, transparent 50%); pointer-events: none;"></div>
    <div class="position-absolute bottom-0 end-0 w-100 h-100" style="background: radial-gradient(circle at 90% 80%, rgba(247, 127, 0, 0.2) 0%, transparent 50%); pointer-events: none;"></div>

    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="https://flagcdn.com/w80/kr.png" alt="South Korea Flag" class="rounded shadow" width="60" height="40">
                    <div class="badge bg-white text-danger p-2 px-3 rounded-pill shadow-sm">
                        <span class="fw-bold">TOPIK Certified</span>
                    </div>
                </div>
                <h1 class="display-3 fw-bold mb-4 text-white">
                    Kursus Bahasa Korea
                </h1>
                <h2 class="h3 text-white-50 mb-4">한국어 코스</h2>
                <!-- Meta Tags -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <span class="badge bg-white bg-opacity-30 text-white px-3 py-2 rounded-pill border border-white border-opacity-25">
                        <i class="bi bi-clock me-1"></i> Fleksibel
                    </span>
                    <span class="badge bg-white bg-opacity-30 text-white px-3 py-2 rounded-pill border border-white border-opacity-25">
                        <i class="bi bi-people me-1"></i> Kelas Kecil
                    </span>
                    <span class="badge bg-white bg-opacity-30 text-white px-3 py-2 rounded-pill border border-white border-opacity-25">
                        <i class="bi bi-award me-1"></i> Sertifikat TOPIK
                    </span>
                    <span class="badge bg-white bg-opacity-30 text-white px-3 py-2 rounded-pill border border-white border-opacity-25">
                        <i class="bi bi-translate me-1"></i> Native Speaker
                    </span>
                </div>
                <p class="lead mb-4 text-white-50" style="font-size: 1.2rem;">
                    Ikuti tren K-Culture! Bergabunglah dengan SOS Course and Training dan persiapkan diri Anda untuk TOPIK, beasiswa, dan karir di Korea Selatan.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="https://wa.me/6285810310950?text=Hai,%20saya%20tertarik%20dengan%20kursus%20Bahasa%20Korea"
                        target="_blank"
                        class="btn btn-warning btn-lg px-4 shadow fw-bold">
                        <i class="bi bi-whatsapp me-2"></i>Konsultasi Gratis
                    </a>
                    <a href="<?= base_url('apply') ?>" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-pencil-square me-2"></i>Daftar Sekarang
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="bg-white bg-opacity-10 rounded-5 p-5 backdrop-blur">
                    <div class="display-1 mb-3">🎎</div>
                    <h3 class="text-white fw-bold mb-3">한국어</h3>
                    <p class="text-white-50">Bahasa Korea</p>
                    <div class="mt-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                    <div class="h4 fw-bold text-white mb-0">TOPIK 1-6</div>
                                    <small class="text-white-50">Level Tersedia</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                    <div class="h4 fw-bold text-white mb-0">Native</div>
                                    <small class="text-white-50">Pengajar</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- All Programs Table Section -->
<?php if (!empty($programsByMode)): ?>
<div class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: #f77f00;">Daftar Lengkap Program Korea</h2>
            <p class="text-muted">Temukan program yang sesuai dengan kebutuhan Anda</p>
        </div>

        <!-- Mode Navigation for Table -->
        <?php if (count($modes) > 1): ?>
        <div class="row g-2 mb-4" role="tablist">
            <?php foreach ($modes as $modeIndex => $mode): ?>
                <div class="col">
                    <button class="table-tab-btn <?= ($modeIndex === 0) ? 'active' : '' ?>"
                        id="table-mode-tab-<?= $modeIndex ?>"
                        data-bs-toggle="tab"
                        data-bs-target="#table-mode-<?= $modeIndex ?>"
                        type="button"
                        role="tab"
                        aria-controls="table-mode-<?= $modeIndex ?>"
                        aria-selected="<?= ($modeIndex === 0) ? 'true' : 'false' ?>">
                        <i class="bi bi-<?= ($mode === 'online') ? 'wifi' : 'building' ?> me-2"></i>
                        <?= ucfirst($mode) ?>
                        <span class="badge-table"><?= $programsByMode[$mode]['total_programs'] ?></span>
                    </button>
                </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>

        <!-- Mode Tab Content for Table -->
        <div class="tab-content" id="tableModeTabContent">
            <?php foreach ($modes as $modeIndex => $mode): ?>
                <div class="tab-pane fade <?= ($modeIndex === 0) ? 'show active' : '' ?>"
                    id="table-mode-<?= $modeIndex ?>"
                    role="tabpanel"
                    aria-labelledby="table-mode-tab-<?= $modeIndex ?>">

                    <?php 
                    $categories = array_keys($programsByMode[$mode]['categories']);
                    if (count($categories) > 1): 
                    ?>
                        <!-- Category Navigation for Table -->
                        <div class="d-flex flex-wrap gap-2 justify-content-center mb-4" role="tablist">
                            <?php foreach ($categories as $catIndex => $category): ?>
                                <button class="table-cat-btn <?= ($catIndex === 0) ? 'active' : '' ?>"
                                    id="table-cat-tab-<?= $modeIndex ?>-<?= $catIndex ?>"
                                    data-bs-toggle="tab"
                                    data-bs-target="#table-cat-<?= $modeIndex ?>-<?= $catIndex ?>"
                                    type="button"
                                    role="tab"
                                    aria-controls="table-cat-<?= $modeIndex ?>-<?= $catIndex ?>"
                                    aria-selected="<?= ($catIndex === 0) ? 'true' : 'false' ?>">
                                    <?= esc($category) ?>
                                    <span class="badge-table"><?= $programsByMode[$mode]['categories'][$category]['total_programs'] ?></span>
                                </button>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                    <!-- Category Tab Content for Table -->
                    <div class="tab-content">
                        <?php foreach ($categories as $catIndex => $category): ?>
                            <?php 
                            $subCategories = $programsByMode[$mode]['categories'][$category]['sub_categories'];
                            $subCatKeys = array_keys($subCategories);
                            $hasMultipleSubCats = count($subCatKeys) > 1;
                            $isCatActive = ($catIndex === 0);
                            ?>
                            
                            <div class="tab-pane fade <?= $isCatActive ? 'show active' : '' ?>"
                                id="table-cat-<?= $modeIndex ?>-<?= $catIndex ?>"
                                role="tabpanel"
                                aria-labelledby="table-cat-tab-<?= $modeIndex ?>-<?= $catIndex ?>">
                            
                                <?php if ($hasMultipleSubCats): ?>
                                    <!-- Sub-Category Navigation for Table -->
                                    <div class="d-flex flex-wrap gap-2 justify-content-center mb-3" role="tablist">
                                        <?php foreach ($subCatKeys as $subIndex => $subCategory): ?>
                                            <button class="table-cat-btn <?= ($subIndex === 0) ? 'active' : '' ?>"
                                                id="table-sub-tab-<?= $modeIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>"
                                                data-bs-toggle="tab"
                                                data-bs-target="#table-sub-<?= $modeIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>"
                                                type="button"
                                                role="tab"
                                                aria-controls="table-sub-<?= $modeIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>"
                                                aria-selected="<?= ($subIndex === 0) ? 'true' : 'false' ?>">
                                                <?= esc($subCategory) ?>
                                                <span class="badge-table"><?= count($subCategories[$subCategory]) ?></span>
                                            </button>
                                        <?php endforeach ?>
                                    </div>

                                    <!-- Sub-Category Tab Content for Table -->
                                    <div class="tab-content">
                                        <?php foreach ($subCatKeys as $subIndex => $subCategory): 
                                            $progs = $subCategories[$subCategory];
                                        ?>
                                            <div class="tab-pane fade <?= ($subIndex === 0) ? 'show active' : '' ?>"
                                                id="table-sub-<?= $modeIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>"
                                                role="tabpanel"
                                                aria-labelledby="table-sub-tab-<?= $modeIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>">
                                                
                                                <!-- Program Table - Desktop Only -->
                                                <div class="card border-0 shadow-sm overflow-hidden d-none d-md-block">
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover align-middle mb-0">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th class="ps-4" style="width: 30%;">Program</th>
                                                                        <th style="width: 25%;">Deskripsi</th>
                                                                        <th style="width: 15%;" class="text-end">Harga</th>
                                                                        <th style="width: 15%;" class="text-center">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($progs as $program): 
                                                                        $finalPrice = $program['tuition_fee'] * (1 - ($program['discount'] ?? 0) / 100);
                                                                    ?>
                                                                        <tr>
                                                                            <td class="ps-4">
                                                                                <div class="d-flex align-items-start gap-3">
                                                                                    <div class="flex-shrink-0">
                                                                                        <?php if (!empty($program['thumbnail'])): ?>
                                                                                            <img src="<?= base_url('uploads/programs/thumbs/' . $program['thumbnail']) ?>" 
                                                                                                alt="<?= esc($program['title']) ?>" 
                                                                                                class="rounded" 
                                                                                                style="width: 60px; height: 45px; object-fit: cover;">
                                                                                        <?php else: ?>
                                                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 45px;">
                                                                                                <i class="bi bi-journal-text text-muted"></i>
                                                                                            </div>
                                                                                        <?php endif ?>
                                                                                    </div>
                                                                                    <div>
                                                                                        <h6 class="mb-1 fw-bold text-dark"><?= esc($program['title']) ?></h6>
                                                                                        <div class="d-flex flex-wrap gap-1">
                                                                                            <?php if (!empty($program['language'])): ?>
                                                                                                <span class="badge bg-info bg-opacity-10 text-info" style="font-size: 0.65rem;">
                                                                                                    <i class="bi bi-translate me-1"></i><?= esc($program['language']) ?>
                                                                                                </span>
                                                                                            <?php endif ?>
                                                                                            <?php if (!empty($program['language_level'])): ?>
                                                                                                <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size: 0.65rem;">
                                                                                                    <?= esc($program['language_level']) ?>
                                                                                                </span>
                                                                                            <?php endif ?>
                                                                                            <?php if (!empty($program['duration'])): ?>
                                                                                                <span class="badge bg-light text-dark border" style="font-size: 0.65rem;">
                                                                                                    <i class="bi bi-clock me-1"></i><?= esc($program['duration']) ?>
                                                                                                </span>
                                                                                            <?php endif ?>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <p class="mb-0 text-muted small">
                                                                                    <?= esc(strlen($program['description'] ?? '') > 100 ? substr($program['description'], 0, 100) . '...' : ($program['description'] ?? '-')) ?>
                                                                                </p>
                                                                            </td>
                                                                            <td class="text-end">
                                                                                <?php if (!empty($program['discount']) && $program['discount'] > 0): ?>
                                                                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                                                                        <span class="badge bg-danger rounded-pill">-<?= $program['discount'] ?>%</span>
                                                                                    </div>
                                                                                    <div class="text-decoration-line-through text-muted small">Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?></div>
                                                                                    <div class="fw-bold text-danger">Rp <?= number_format($finalPrice, 0, ',', '.') ?></div>
                                                                                <?php else: ?>
                                                                                    <div class="fw-bold text-dark">Rp <?= number_format($finalPrice, 0, ',', '.') ?></div>
                                                                                <?php endif ?>
                                                                                <div class="text-muted small">Reg: Rp <?= number_format($program['registration_fee'] ?? 0, 0, ',', '.') ?></div>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <div class="d-flex gap-2 justify-content-center">
                                                                                    <a href="<?= base_url('programs/' . $program['id']) ?>" 
                                                                                        class="btn btn-outline-secondary btn-sm rounded px-3" 
                                                                                        title="Detail">
                                                                                        <i class="bi bi-eye"></i>
                                                                                    </a>
                                                                                    <a href="<?= base_url('apply/' . $program['id']) ?>" 
                                                                                        class="btn btn-warning btn-sm rounded px-3 fw-bold">
                                                                                        Apply
                                                                                    </a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Program Cards - Mobile Only -->
                                                <div class="d-md-none">
                                                    <?php foreach ($progs as $program): 
                                                        $finalPrice = $program['tuition_fee'] * (1 - ($program['discount'] ?? 0) / 100);
                                                    ?>
                                                        <div class="mobile-program-card">
                                                            <div class="d-flex gap-3">
                                                                <div class="flex-shrink-0">
                                                                    <?php if (!empty($program['thumbnail'])): ?>
                                                                        <img src="<?= base_url('uploads/programs/thumbs/' . $program['thumbnail']) ?>" 
                                                                            alt="<?= esc($program['title']) ?>" 
                                                                            class="rounded" 
                                                                            style="width: 80px; height: 60px; object-fit: cover;">
                                                                    <?php else: ?>
                                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 60px;">
                                                                            <i class="bi bi-journal-text text-muted fs-4"></i>
                                                                        </div>
                                                                    <?php endif ?>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h6 class="fw-bold mb-1"><?= esc($program['title']) ?></h6>
                                                                    <p class="text-muted small mb-2">
                                                                        <?= esc(strlen($program['description'] ?? '') > 80 ? substr($program['description'], 0, 80) . '...' : ($program['description'] ?? '-')) ?>
                                                                    </p>
                                                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                                                        <?php if (!empty($program['language_level'])): ?>
                                                                            <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size: 0.65rem;">
                                                                                <?= esc($program['language_level']) ?>
                                                                            </span>
                                                                        <?php endif ?>
                                                                        <?php if (!empty($program['duration'])): ?>
                                                                            <span class="badge bg-light text-dark border" style="font-size: 0.65rem;">
                                                                                <i class="bi bi-clock me-1"></i><?= esc($program['duration']) ?>
                                                                            </span>
                                                                        <?php endif ?>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <?php if (!empty($program['discount']) && $program['discount'] > 0): ?>
                                                                                <span class="badge bg-danger rounded-pill">-<?= $program['discount'] ?>%</span>
                                                                                <span class="text-decoration-line-through text-muted small">Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?></span>
                                                                            <?php endif ?>
                                                                            <span class="fw-bold text-danger">Rp <?= number_format($finalPrice, 0, ',', '.') ?></span>
                                                                        </div>
                                                                        <div class="d-flex gap-2">
                                                                            <a href="<?= base_url('programs/' . $program['id']) ?>" class="btn btn-outline-secondary btn-sm">
                                                                                <i class="bi bi-eye"></i>
                                                                            </a>
                                                                            <a href="<?= base_url('apply/' . $program['id']) ?>" class="btn btn-warning btn-sm fw-bold">
                                                                                Apply
                                                                            </a>
                                                                        </div>
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
                                    <!-- Single sub-category, show table directly -->
                                    <?php
                                    $progs = $subCategories[$subCatKeys[0]];
                                    ?>

                                    <!-- Program Table - Desktop Only -->
                                    <div class="card border-0 shadow-sm overflow-hidden d-none d-md-block">
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th class="ps-4" style="width: 30%;">Program</th>
                                                            <th style="width: 25%;">Deskripsi</th>
                                                            <th style="width: 15%;" class="text-end">Harga</th>
                                                            <th style="width: 15%;" class="text-center">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($progs as $program): 
                                                            $finalPrice = $program['tuition_fee'] * (1 - ($program['discount'] ?? 0) / 100);
                                                        ?>
                                                            <tr>
                                                                <td class="ps-4">
                                                                    <div class="d-flex align-items-start gap-3">
                                                                        <div class="flex-shrink-0">
                                                                            <?php if (!empty($program['thumbnail'])): ?>
                                                                                <img src="<?= base_url('uploads/programs/thumbs/' . $program['thumbnail']) ?>" 
                                                                                    alt="<?= esc($program['title']) ?>" 
                                                                                    class="rounded" 
                                                                                    style="width: 60px; height: 45px; object-fit: cover;">
                                                                            <?php else: ?>
                                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 45px;">
                                                                                    <i class="bi bi-journal-text text-muted"></i>
                                                                                </div>
                                                                            <?php endif ?>
                                                                        </div>
                                                                        <div>
                                                                            <h6 class="mb-1 fw-bold text-dark"><?= esc($program['title']) ?></h6>
                                                                            <div class="d-flex flex-wrap gap-1">
                                                                                <?php if (!empty($program['language'])): ?>
                                                                                    <span class="badge bg-info bg-opacity-10 text-info" style="font-size: 0.65rem;">
                                                                                        <i class="bi bi-translate me-1"></i><?= esc($program['language']) ?>
                                                                                    </span>
                                                                                <?php endif ?>
                                                                                <?php if (!empty($program['language_level'])): ?>
                                                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size: 0.65rem;">
                                                                                        <?= esc($program['language_level']) ?>
                                                                                    </span>
                                                                                <?php endif ?>
                                                                                <?php if (!empty($program['duration'])): ?>
                                                                                    <span class="badge bg-light text-dark border" style="font-size: 0.65rem;">
                                                                                        <i class="bi bi-clock me-1"></i><?= esc($program['duration']) ?>
                                                                                    </span>
                                                                                <?php endif ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <p class="mb-0 text-muted small">
                                                                        <?= esc(strlen($program['description'] ?? '') > 100 ? substr($program['description'], 0, 100) . '...' : ($program['description'] ?? '-')) ?>
                                                                    </p>
                                                                </td>
                                                                <td class="text-end">
                                                                    <?php if (!empty($program['discount']) && $program['discount'] > 0): ?>
                                                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                                                            <span class="badge bg-danger rounded-pill">-<?= $program['discount'] ?>%</span>
                                                                        </div>
                                                                        <div class="text-decoration-line-through text-muted small">Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?></div>
                                                                        <div class="fw-bold text-danger">Rp <?= number_format($finalPrice, 0, ',', '.') ?></div>
                                                                    <?php else: ?>
                                                                        <div class="fw-bold text-dark">Rp <?= number_format($finalPrice, 0, ',', '.') ?></div>
                                                                    <?php endif ?>
                                                                    <div class="text-muted small">Reg: Rp <?= number_format($program['registration_fee'] ?? 0, 0, ',', '.') ?></div>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="d-flex gap-2 justify-content-center">
                                                                        <a href="<?= base_url('programs/' . $program['id']) ?>" 
                                                                            class="btn btn-outline-secondary btn-sm rounded px-3" 
                                                                            title="Detail">
                                                                            <i class="bi bi-eye"></i>
                                                                        </a>
                                                                        <a href="<?= base_url('apply/' . $program['id']) ?>" 
                                                                            class="btn btn-warning btn-sm rounded px-3 fw-bold">
                                                                            Apply
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Program Cards - Mobile Only -->
                                    <div class="d-md-none">
                                        <?php foreach ($progs as $program): 
                                            $finalPrice = $program['tuition_fee'] * (1 - ($program['discount'] ?? 0) / 100);
                                        ?>
                                            <div class="mobile-program-card">
                                                <div class="d-flex gap-3">
                                                    <div class="flex-shrink-0">
                                                        <?php if (!empty($program['thumbnail'])): ?>
                                                            <img src="<?= base_url('uploads/programs/thumbs/' . $program['thumbnail']) ?>" 
                                                                alt="<?= esc($program['title']) ?>" 
                                                                class="rounded" 
                                                                style="width: 80px; height: 60px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 60px;">
                                                                <i class="bi bi-journal-text text-muted fs-4"></i>
                                                            </div>
                                                        <?php endif ?>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fw-bold mb-1"><?= esc($program['title']) ?></h6>
                                                        <p class="text-muted small mb-2">
                                                            <?= esc(strlen($program['description'] ?? '') > 80 ? substr($program['description'], 0, 80) . '...' : ($program['description'] ?? '-')) ?>
                                                        </p>
                                                        <div class="d-flex flex-wrap gap-1 mb-2">
                                                            <?php if (!empty($program['language_level'])): ?>
                                                                <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size: 0.65rem;">
                                                                    <?= esc($program['language_level']) ?>
                                                                </span>
                                                            <?php endif ?>
                                                            <?php if (!empty($program['duration'])): ?>
                                                                <span class="badge bg-light text-dark border" style="font-size: 0.65rem;">
                                                                    <i class="bi bi-clock me-1"></i><?= esc($program['duration']) ?>
                                                                </span>
                                                            <?php endif ?>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <?php if (!empty($program['discount']) && $program['discount'] > 0): ?>
                                                                    <span class="badge bg-danger rounded-pill">-<?= $program['discount'] ?>%</span>
                                                                    <span class="text-decoration-line-through text-muted small">Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?></span>
                                                                <?php endif ?>
                                                                <span class="fw-bold text-danger">Rp <?= number_format($finalPrice, 0, ',', '.') ?></span>
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <a href="<?= base_url('programs/' . $program['id']) ?>" class="btn btn-outline-secondary btn-sm">
                                                                    <i class="bi bi-eye"></i>
                                                                </a>
                                                                <a href="<?= base_url('apply/' . $program['id']) ?>" class="btn btn-warning btn-sm fw-bold">
                                                                    Apply
                                                                </a>
                                                            </div>
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
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Why Learn Korean Section -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold mb-3" style="color: var(--dark-red);">Mengapa Belajar Bahasa Korea?</h2>
        <p class="lead text-muted">Ikuti gelombang K-Culture dan buka peluang karir di Korea Selatan</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-music-note-beamed"></i>
                    </div>
                    <h5 class="fw-bold mb-3">K-Pop & K-Drama</h5>
                    <p class="text-muted small mb-0">Nikmati K-Pop dan K-Drama tanpa subtitle dan pahami budaya Korea secara langsung.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Peluang Karir</h5>
                    <p class="text-muted small mb-0">Banyak perusahaan Korea membuka lowongan untuk pekerja asing yang fasih berbahasa Korea.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Beasiswa KGSP</h5>
                    <p class="text-muted small mb-0">Dapatkan beasiswa penuh dari pemerintah Korea untuk kuliah di universitas ternama.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Sertifikasi TOPIK</h5>
                    <p class="text-muted small mb-0">Dapatkan sertifikasi TOPIK yang diakui internasional untuk keperluan karir dan akademik.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 7 Reasons Section -->
<div class="py-5" style="background: linear-gradient(180deg, #fff8f0 0%, #fff 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: #f77f00;">Mengapa Harus SOS Course?</h2>
        </div>

        <div class="row g-4">
            <!-- Reason 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-music-note-beamed"></i>
                        </div>
                        <h5 class="fw-bold mb-2">K-Pop Wave</h5>
                        <p class="text-muted small mb-0">Ikuti tren K-Pop dan Korean Wave yang sedang populer di seluruh dunia.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-globe"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Ekonomi K-Pop</h5>
                        <p class="text-muted small mb-0">Korea Selatan adalah ekonomi terbesar ke-10 di dunia.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Beasiswa Korea</h5>
                        <p class="text-muted small mb-0">Persiapan untuk mendapatkan beasiswa ke universitas Korea Selatan.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Peluang Kerja</h5>
                        <p class="text-muted small mb-0">Banyak perusahaan Korea yang membutuhkan tenaga kerja Indonesia.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 5 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Native Speaker</h5>
                        <p class="text-muted small mb-0">Pengajar native speaker Korea untuk pronunciation yang benar.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 6 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-award"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Sertifikat TOPIK</h5>
                        <p class="text-muted small mb-0">Sertifikasi TOPIK yang diakui resmi oleh pemerintah Korea.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 7 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Kampung Inggris Pare</h5>
                        <p class="text-muted small mb-0">Terlettak di lokasi strategis di Kampung Inggris Pare, Kediri.</p>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
</div>

<!-- Mode Navigation Bar - Sticky -->
<?php if (!empty($modes)): ?>
    <div class="sticky-top" id="explore" style="top: 0; z-index: 1020;">
        <nav class="py-2 px-2 d-flex flex-wrap gap-2 justify-content-center" style="background: linear-gradient(135deg, var(--dark-red) 0%, #600000 100%);" role="tablist">
            <?php foreach ($modes as $modeIndex => $mode): ?>
                <button class="btn-lang-header <?= ($modeIndex === 0) ? 'active' : '' ?>"
                    id="mode-tab-<?= $modeIndex ?>"
                    data-bs-toggle="tab"
                    data-bs-target="#mode-<?= $modeIndex ?>"
                    type="button"
                    role="tab"
                    aria-controls="mode-<?= $modeIndex ?>"
                    aria-selected="<?= ($modeIndex === 0) ? 'true' : 'false' ?>">
                    <i class="bi bi-<?= ($mode === 'online') ? 'wifi' : 'building' ?> me-2"></i>
                    <?= ucfirst($mode) ?>
                    <span class="badge-lang"><?= $programsByMode[$mode]['total_programs'] ?></span>
                </button>
            <?php endforeach ?>
        </nav>
    </div>
<?php endif ?>

<!-- Available Programs Section -->
<div class="container py-3">
    <?php if (empty($programsByMode)): ?>
        <div class="text-center py-5">
            <div class="feature-icon mb-4 mx-auto" style="width: 100px; height: 100px; font-size: 3rem; background: var(--light-red); color: var(--dark-red);">
                <i class="bi bi-search"></i>
            </div>
            <h3 class="fw-bold">No Programs Available</h3>
            <p class="text-muted">Belum ada program Korea yang tersedia saat ini. Silakan hubungi kami untuk informasi lebih lanjut.</p>
            <a href="https://wa.me/6285810310950?text=Hai,%20saya%20mau%20tanya%20tentang%20program%20Korea"
                target="_blank"
                class="btn btn-dark-red rounded-pill mt-3">
                <i class="bi bi-whatsapp me-2"></i>Hubungi Kami
            </a>
        </div>
    <?php else: ?>
        <!-- Tab Content for Modes -->
        <div class="tab-content" id="modeTabContent">
            <?php foreach ($modes as $modeIndex => $mode): ?>
                <div class="tab-pane fade <?= ($modeIndex === 0) ? 'show active' : '' ?>"
                    id="mode-<?= $modeIndex ?>"
                    role="tabpanel"
                    aria-labelledby="mode-tab-<?= $modeIndex ?>">

                    <?php 
                    $categories = array_keys($programsByMode[$mode]['categories']);
                    if (!empty($categories)): 
                    ?>
                        <!-- Category Navigation - Pill Style -->
                        <div class="text-center mb-3 pt-3">
                            <div class="d-inline-flex flex-wrap gap-2 justify-content-center" role="tablist">
                                <?php foreach ($categories as $catIndex => $category): ?>
                                    <button class="pill-tab-btn <?= ($catIndex === 0) ? 'active' : '' ?>"
                                        id="cat-tab-<?= $modeIndex ?>-<?= $catIndex ?>"
                                        data-bs-toggle="tab"
                                        data-bs-target="#category-<?= $modeIndex ?>-<?= $catIndex ?>"
                                        type="button"
                                        role="tab"
                                        aria-controls="category-<?= $modeIndex ?>-<?= $catIndex ?>"
                                        aria-selected="<?= ($catIndex === 0) ? 'true' : 'false' ?>">
                                        <?= esc($category) ?>
                                        <span class="badge-pill"><?= $programsByMode[$mode]['categories'][$category]['total_programs'] ?></span>
                                    </button>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <!-- Category Tab Content -->
                        <div class="tab-content">
                            <?php foreach ($categories as $catIndex => $category): ?>
                                <div class="tab-pane fade <?= ($catIndex === 0) ? 'show active' : '' ?>"
                                    id="category-<?= $modeIndex ?>-<?= $catIndex ?>"
                                    role="tabpanel"
                                    aria-labelledby="cat-tab-<?= $modeIndex ?>-<?= $catIndex ?>">

                                    <?php 
                                    $subCategories = array_keys($programsByMode[$mode]['categories'][$category]['sub_categories']);
                                    $hasMultipleSubCats = count($subCategories) > 1;
                                    ?>
                                    
                                    <?php if ($hasMultipleSubCats): ?>
                                        <!-- Sub-Category Navigation - Small Pills -->
                                        <div class="text-center mb-3">
                                            <div class="d-inline-flex flex-wrap gap-2 justify-content-center" role="tablist">
                                                <?php foreach ($subCategories as $subIndex => $subCategory): ?>
                                                    <button class="pill-tab-btn pill-tab-btn-sm <?= ($subIndex === 0) ? 'active' : '' ?>"
                                                        id="sub-tab-<?= $modeIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>"
                                                        data-bs-toggle="tab"
                                                        data-bs-target="#sub-category-<?= $modeIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>"
                                                        type="button"
                                                        role="tab"
                                                        aria-controls="sub-category-<?= $modeIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>"
                                                        aria-selected="<?= ($subIndex === 0) ? 'true' : 'false' ?>">
                                                        <?= esc($subCategory) ?>
                                                        <span class="badge-pill"><?= count($programsByMode[$mode]['categories'][$category]['sub_categories'][$subCategory]) ?></span>
                                                    </button>
                                                <?php endforeach ?>
                                            </div>
                                        </div>

                                        <!-- Sub-Category Tab Content -->
                                        <div class="tab-content">
                                            <?php foreach ($subCategories as $subIndex => $subCategory): ?>
                                                <div class="tab-pane fade <?= ($subIndex === 0) ? 'show active' : '' ?>"
                                                    id="sub-category-<?= $modeIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>"
                                                    role="tabpanel"
                                                    aria-labelledby="sub-tab-<?= $modeIndex ?>-<?= $catIndex ?>-<?= $subIndex ?>">
                                                    <!-- Programs Grid -->
                                                    <div class="row g-4">
                                                        <?php 
                                                        $progs = $programsByMode[$mode]['categories'][$category]['sub_categories'][$subCategory];
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
                                                                            <select class="badge bg-info text-white border-0 py-2 px-2 rounded-pill fw-bold sort-order-select" 
                                                                                data-program-id="<?= $program['id'] ?>"
                                                                                style="font-size: 0.7rem; cursor: pointer;"
                                                                                title="Change Sort Order">
                                                                                <?php for ($i = 1; $i <= 20; $i++): ?>
                                                                                    <option value="<?= $i ?>" <?= ($program['sort_order'] ?? 1) == $i ? 'selected' : '' ?>>
                                                                                        #<?= $i ?>
                                                                                    </option>
                                                                                <?php endfor; ?>
                                                                            </select>
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
                                            $progs = $programsByMode[$mode]['categories'][$category]['sub_categories'][$subCatKey];
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
                                                                <select class="badge bg-info text-white border-0 py-2 px-2 rounded-pill fw-bold sort-order-select" 
                                                                    data-program-id="<?= $program['id'] ?>"
                                                                    style="font-size: 0.7rem; cursor: pointer;"
                                                                    title="Change Sort Order">
                                                                    <?php for ($i = 1; $i <= 20; $i++): ?>
                                                                        <option value="<?= $i ?>" <?= ($program['sort_order'] ?? 1) == $i ? 'selected' : '' ?>>
                                                                            #<?= $i ?>
                                                                        </option>
                                                                    <?php endfor; ?>
                                                                </select>
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

<!-- Program Levels Section -->
<div class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: var(--dark-red);">Level Program Kami</h2>
            <p class="lead text-muted">Pilih level yang sesuai dengan kemampuan dan tujuan Anda</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-danger text-white py-3">
                        <h5 class="fw-bold mb-0">TOPIK 1 (Beginner)</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Hangeul dasar</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Kosakata dasar (800 kata)</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Percakapan sehari-hari</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Grammar dasar</li>
                        </ul>
                        <a href="<?= base_url('apply') ?>" class="btn btn-dark-red w-100">Daftar Level Ini</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-danger text-white py-3">
                        <h5 class="fw-bold mb-0">TOPIK 2 (Intermediate)</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Percakapan bisnis dasar</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Kosakata lanjutan (3000 kata)</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Bacaan teks kompleks</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Grammar intermediate</li>
                        </ul>
                        <a href="<?= base_url('apply') ?>" class="btn btn-dark-red w-100">Daftar Level Ini</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-danger text-white py-3">
                        <h5 class="fw-bold mb-0">TOPIK 3-6 (Advanced)</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Fluensi tingkat tinggi</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Kosakata mahir (5000+ kata)</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Bisnis Korea profesional</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Preparasi kerja/kuliah di Korea</li>
                        </ul>
                        <a href="<?= base_url('apply') ?>" class="btn btn-dark-red w-100">Daftar Level Ini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container py-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-6">
            <h2 class="display-5 fw-bold mb-4" style="color: var(--dark-red);">Keunggulan Program Kami</h2>

            <div class="d-flex gap-4 mb-4">
                <div class="bg-light p-3 rounded-4">
                    <i class="bi bi-person-video3 text-danger fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold">Pengajar Native Speaker</h5>
                    <p class="text-muted small mb-0">Belajar langsung dari pengajar asli Korea dengan pengalaman mengajar profesional.</p>
                </div>
            </div>

            <div class="d-flex gap-4 mb-4">
                <div class="bg-light p-3 rounded-4">
                    <i class="bi bi-book-half text-danger fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold">Kurikulum Standar TOPIK</h5>
                    <p class="text-muted small mb-0">Mengikuti standar internasional TOPIK untuk memastikan kualitas pembelajaran.</p>
                </div>
            </div>

            <div class="d-flex gap-4 mb-4">
                <div class="bg-light p-3 rounded-4">
                    <i class="bi bi-people-fill text-danger fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold">Kelas Kecil</h5>
                    <p class="text-muted small mb-0">Maksimal 10-15 siswa per kelas untuk perhatian yang lebih personal.</p>
                </div>
            </div>

            <div class="d-flex gap-4 mb-4">
                <div class="bg-light p-3 rounded-4">
                    <i class="bi bi-shield-check-fill text-danger fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold">Garansi Bisa</h5>
                    <p class="text-muted small mb-0">Kami menjamin kemampuan Korea Anda meningkat dengan metode yang terbukti efektif.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="bg-gradient-danger rounded-5 p-5 text-white shadow-lg">
                <h3 class="fw-bold mb-4">Program Unggulan</h3>
                <div class="bg-white bg-opacity-20 rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-2">K-Culture Immersion</h5>
                    <p class="small mb-0 opacity-75">Program khusus dengan fokus pada budaya Korea populer, termasuk K-Pop, K-Drama, dan gaya hidup Korea modern.</p>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-white bg-opacity-20 rounded-3 p-3 text-center">
                            <div class="h4 fw-bold mb-0">TOPIK 1-6</div>
                            <small class="opacity-75">Level Tersedia</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white bg-opacity-20 rounded-3 p-3 text-center">
                            <div class="h4 fw-bold mb-0">Native</div>
                            <small class="opacity-75">Pengajar</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- About Section -->
<div class="container py-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-6">
            <h2 class="display-5 fw-bold mb-4" style="color: #f77f00;">Tentang Kursus Bahasa Korea SOS Course</h2>
            <p class="text-muted mb-4">
                SOS Course and Training adalah lembaga kursus bahasa Korea terbaik di <strong>Kampung Inggris Pare</strong>, Kediri Jawa Timur yang telah berdiri sejak tahun 2013.
            </p>
            <p class="text-muted mb-4">
                Kami adalah pusat pembelajaran bahasa Korea yang spesialis untuk persiapan TOPIK (Test of Proficiency in Korean) dari level I hingga II. Programs kami juga mencakup K-Pop, Korean Wave, dan persiapan bekerja di Korea Selatan.
            </p>
            <p class="text-muted mb-4">
                Dengan pengajar native speaker Korea dan kurikulum yang disesuaikan dengan standar TOPIK, kami siap membantu Anda seringk memahami bahasa Korea dan lulus TOPIK dengan nilai tinggi.
            </p>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="bg-warning text-dark rounded-4 px-4 py-3 text-center">
                    <div class="h4 fw-bold mb-0">TOPIK</div>
                    <small>I & II</small>
                </div>
                <div class="bg-warning text-dark rounded-4 px-4 py-3 text-center">
                    <div class="h4 fw-bold mb-0">K-Pop</div>
                    <small>Class</small>
                </div>
                <div class="bg-warning text-dark rounded-4 px-4 py-3 text-center">
                    <div class="h4 fw-bold mb-0">Business</div>
                    <small>Korean</small>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="bg-gradient-danger rounded-5 p-5 text-white shadow-lg">
                <h3 class="fw-bold mb-4">Program Dimulai</h3>
                <div class="display-4 fw-bold mb-3">Setiap Senin</div>
                <p class="mb-0 opacity-75">Sepanjang tahun</p>
                <hr class="my-4 opacity-25">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-music-note-beamed fs-1 opacity-50"></i>
                    <div>
                        <div class="fw-bold">K-Pop Class</div>
                        <small class="opacity-75">Latihan Tari & Vokal</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="py-5 bg-gradient-danger text-white">
    <div class="container text-center py-5">
        <h2 class="display-5 fw-bold mb-3">Siap Menguasai Bahasa Korea?</h2>
        <p class="lead mb-4 opacity-75">Daftar sekarang dan dapatkan konsultasi gratis dengan pengajar kami.<br>Mulai perjalanan bahasa Korea Anda bersama SOS Course and Training!</p>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="https://wa.me/6285810310950?text=Hai,%20saya%20tertarik%20dengan%20kursus%20Bahasa%20Korea"
                target="_blank"
                class="btn btn-warning btn-lg px-5 shadow fw-bold">
                <i class="bi bi-whatsapp me-2"></i>Konsultasi Gratis via WhatsApp
            </a>
            <a href="<?= base_url('apply') ?>" class="btn btn-outline-light btn-lg px-5">
                <i class="bi bi-pencil-square me-2"></i>Daftar Sekarang
            </a>
        </div>
    </div>
</div>

<style>
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

    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .bg-gradient-danger {
        background: linear-gradient(135deg, var(--dark-red) 0%, var(--medium-red) 100%);
    }

    /* Modern Card */
    .program-card-modern {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid #f0f0f0 !important;
        border-radius: 16px !important;
        overflow: hidden;
    }

    .program-card-modern:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        border-color: #ddd !important;
    }

    .program-img-zoom {
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .program-card-modern:hover .program-img-zoom {
        transform: scale(1.05);
    }

    .bg-gradient-dark {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 100%);
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
    // Handle sort order change via AJAX for superadmin
    document.querySelectorAll('.sort-order-select').forEach(select => {
        select.addEventListener('change', function() {
            const programId = this.dataset.programId;
            const sortOrder = this.value;
            const originalValue = this.dataset.originalValue || this.value;
            
            this.disabled = true;
            this.style.opacity = '0.6';
            
            fetch(`/api/programs/${programId}/sort-order`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ sort_order: parseInt(sortOrder) })
            })
            .then(response => response.json())
            .then(data => {
                this.disabled = false;
                this.style.opacity = '1';
                
                if (data.status === 'success') {
                    this.classList.remove('bg-info');
                    this.classList.add('bg-success');
                    setTimeout(() => {
                        this.classList.remove('bg-success');
                        this.classList.add('bg-info');
                    }, 1500);
                    this.dataset.originalValue = sortOrder;
                } else {
                    alert(data.messages?.error || 'Failed to update sort order');
                    this.value = originalValue;
                }
            })
            .catch(error => {
                this.disabled = false;
                this.style.opacity = '1';
                this.value = originalValue;
                alert('An error occurred. Please try again.');
            });
        });
        select.dataset.originalValue = select.value;
    });
</script>
<?= $this->endSection() ?>