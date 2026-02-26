<?php
/**
 * German Landing Page
 * 
 * Page-specific SEO and content for German language course
 */

// Page-specific variables for SEO
$pageTitle = 'Kursus Bahasa Jerman Terbaik di Kampung Inggris Pare | SOS Course';
$pageDescription = 'SOS Course & Training adalah kursus bahasa Jerman terbaik di Kampung Inggris Pare, Kediri. Program Reguler, Semi-Privat, dan Privat dengan tutor berpengalaman. Program persiapan Goethe-Zertifikat dan studi ke Jerman.';
$pageKeywords = 'kursus bahasa jerman pare, kampung inggris pare, les bahasa jerman kediri, belajar bahasa jerman di pare, kursus bahasa jerman bersertifikat, goethe zertifikat, studi jerman,beasiswa jerman, sos course jerman';
$ogImage = 'https://images.pexels.com/photos/2278099/pexels-photo-2278099.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2';
?>

<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('extra_head') ?>
<!-- Page-specific styles for German landing -->
<style>
    /* Custom Scrollbar for mobile navigation */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Language Header Tabs - Yellow/Gold Background */
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
        color: #ffc107;
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
        background: #fff3cd;
        color: #ffc107;
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
        background: #ffc107;
        color: #000;
        border-color: #ffc107;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.25);
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
        background: rgba(0, 0, 0, 0.1);
        color: #000;
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
        border-color: #ffc107;
        background: #fffbf0;
    }

    .table-tab-btn.active {
        background: #ffc107;
        color: #000;
        border-color: #ffc107;
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.25);
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
        background: rgba(0, 0, 0, 0.1);
        color: #000;
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
        border-color: #ffc107;
        background: #fffbf0;
    }

    .table-cat-btn.active {
        background: #ffc107;
        color: #000;
        border-color: #ffc107;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.2);
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
        background: rgba(0, 0, 0, 0.1);
        color: #000;
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
    .bg-gradient-warning {
        background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
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
    .feature-icon-gold {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: #000;
        font-size: 1.5rem;
    }
    .section-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #000;
        font-size: 1.2rem;
        margin-right: 12px;
    }
    .reason-icon {
        width: 56px;
        height: 56px;
        background: #fff3cd;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffc107;
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
<div class="hero-section position-relative overflow-hidden py-5" style="min-height: 65vh; background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.2) 0%, transparent 50%); pointer-events: none;"></div>
    <div class="position-absolute bottom-0 end-0 w-100 h-100" style="background: radial-gradient(circle at 90% 80%, rgba(255, 255, 255, 0.15) 0%, transparent 50%); pointer-events: none;"></div>

    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="https://flagcdn.com/w80/de.png" alt="Germany Flag" class="rounded shadow-sm" width="60" height="40">
                    <div class="badge bg-dark p-2 px-3 rounded-pill shadow-sm fw-bold text-white">
                        SOS Course & Training
                    </div>
                </div>
                <h1 class="display-3 fw-bold mb-3 text-dark">
                    Kursus Bahasa Jerman Terbaik di Pare
                </h1>
                <p class="lead mb-4 text-dark" style="font-size: 1.15rem;">
                    Wujudkan mimpi studi dan kerja di Jerman. Kursus bahasa Jerman dengan program persiapan Goethe-Zertifikat dan metode pengajaran intensif.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="https://wa.me/6282240781299?text=Hai,%20saya%20tertarik%20dengan%20kursus%20Bahasa%20Jerman"
                        target="_blank"
                        class="btn btn-dark btn-lg px-4 shadow fw-bold">
                        <i class="bi bi-whatsapp me-2"></i>Konsultasi Gratis
                    </a>
                    <a href="<?= base_url('apply') ?>" class="btn btn-outline-dark btn-lg px-4">
                        <i class="bi bi-pencil-square me-2"></i>Daftar Sekarang
                    </a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="bg-dark rounded-5 p-5 text-white shadow-lg">
                    <h3 class="fw-bold mb-4">Program Dimulai</h3>
                    <div class="display-4 fw-bold mb-3" style="color: #ffc107;">Tanggal 10</div>
                    <p class="mb-0 opacity-75">Tiap bulannya</p>
                    <hr class="my-4 opacity-25">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-award fs-1" style="color: #ffc107;"></i>
                        <div>
                            <div class="fw-bold">Garansi Seumur Hidup</div>
                            <small class="opacity-75">Berlaku S&K</small>
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
            <h2 class="display-5 fw-bold mb-3" style="color: #ffc107;">Daftar Lengkap Program Bahasa Jerman</h2>
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
                                                                                    <div class="fw-bold" style="color: #ffc107;">Rp <?= number_format($finalPrice, 0, ',', '.') ?></div>
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
                                                                                    <a href="https://wa.me/6282240781299?text=Hai,%20saya%20tertarik%20dengan%20program%20<?= urlencode($program['title']) ?>%20di%20SOS%20Course" 
                                                                                        class="btn btn-success btn-sm rounded px-3" 
                                                                                        title="Share via WhatsApp" target="_blank">
                                                                                        <i class="bi bi-share"></i>
                                                                                    </a>
                                                                                    <a href="<?= base_url('apply/' . $program['id']) ?>" 
                                                                                        class="btn btn-warning btn-sm rounded px-3 fw-bold text-dark">
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
                                                                            <i class="bi bi-journal-text text-muted"></i>
                                                                        </div>
                                                                    <?php endif ?>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mb-1 fw-bold text-dark"><?= esc($program['title']) ?></h6>
                                                                    <div class="d-flex flex-wrap gap-1 mb-2">
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
                                                                    <div>
                                                                        <?php if (!empty($program['discount']) && $program['discount'] > 0): ?>
                                                                            <span class="badge bg-danger rounded-pill me-1">-<?= $program['discount'] ?>%</span>
                                                                            <span class="text-decoration-line-through text-muted small">Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?></span>
                                                                            <span class="fw-bold ms-1" style="color: #ffc107;">Rp <?= number_format($finalPrice, 0, ',', '.') ?></span>
                                                                        <?php else: ?>
                                                                            <span class="fw-bold text-dark">Rp <?= number_format($finalPrice, 0, ',', '.') ?></span>
                                                                        <?php endif ?>
                                                                        <div class="text-muted small">Reg: Rp <?= number_format($program['registration_fee'] ?? 0, 0, ',', '.') ?></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mt-3 pt-3 border-top">
                                                                <div class="d-grid gap-2">
                                                                    <a href="<?= base_url('programs/' . $program['id']) ?>" 
                                                                        class="btn btn-outline-secondary btn-sm rounded">
                                                                        <i class="bi bi-eye me-1"></i>Detail
                                                                    </a>
                                                                    <div class="d-flex gap-2">
                                                                        <a href="https://wa.me/6282240781299?text=Hai,%20saya%20tertarik%20dengan%20program%20<?= urlencode($program['title']) ?>%20di%20SOS%20Course" 
                                                                            class="btn btn-success btn-sm rounded flex-fill" 
                                                                            target="_blank">
                                                                            <i class="bi bi-share me-1"></i>Share
                                                                        </a>
                                                                        <a href="<?= base_url('apply/' . $program['id']) ?>" 
                                                                            class="btn btn-warning btn-sm rounded flex-fill fw-bold text-dark">
                                                                            Apply
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
                                <?php else: ?>
                                    <!-- Single sub-category, show table directly -->
                                    <?php 
                                    $subCatKey = $subCatKeys[0] ?? 'Standard';
                                    $progs = $subCategories[$subCatKey];
                                    ?>
                                    
                                    <!-- Program Table - Desktop Only -->
                                    <div class="card border-0 shadow-sm overflow-hidden d-none d-md-block">
                                        <div class="card-header bg-white py-3 border-0">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                <h5 class="mb-0 fw-bold"><?= esc($category) ?> - <?= esc($subCatKey) ?></h5>
                                                <span class="badge bg-light text-muted rounded-pill"><?= count($progs) ?> Program</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th class="ps-4" style="width: 30%;">Program</th>
                                                            <th style="width: 25%;">Deskripsi</th>
                                                            <th style="width: 15%;" class="text-end">Biaya</th>
                                                            <th style="width: 15%;" class="text-center"></th>
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
                                                                        <div class="fw-bold" style="color: #ffc107;">Rp <?= number_format($finalPrice, 0, ',', '.') ?></div>
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
                                                                        <a href="https://wa.me/6282240781299?text=Hai,%20saya%20tertarik%20dengan%20program%20<?= urlencode($program['title']) ?>%20di%20SOS%20Course" 
                                                                            class="btn btn-success btn-sm rounded px-3" 
                                                                            title="Share via WhatsApp" target="_blank">
                                                                            <i class="bi bi-share"></i>
                                                                        </a>
                                                                        <a href="<?= base_url('apply/' . $program['id']) ?>" 
                                                                            class="btn btn-warning btn-sm rounded px-3 fw-bold text-dark">
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
                                        <div class="mb-3">
                                            <h5 class="fw-bold"><?= esc($category) ?> - <?= esc($subCatKey) ?></h5>
                                            <span class="badge bg-light text-muted rounded-pill"><?= count($progs) ?> Program</span>
                                        </div>
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
                                                                <i class="bi bi-journal-text text-muted"></i>
                                                            </div>
                                                        <?php endif ?>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold text-dark"><?= esc($program['title']) ?></h6>
                                                        <div class="d-flex flex-wrap gap-1 mb-2">
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
                                                        <div>
                                                            <?php if (!empty($program['discount']) && $program['discount'] > 0): ?>
                                                                <span class="badge bg-danger rounded-pill me-1">-<?= $program['discount'] ?>%</span>
                                                                <span class="text-decoration-line-through text-muted small">Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?></span>
                                                                <span class="fw-bold ms-1" style="color: #ffc107;">Rp <?= number_format($finalPrice, 0, ',', '.') ?></span>
                                                            <?php else: ?>
                                                                <span class="fw-bold text-dark">Rp <?= number_format($finalPrice, 0, ',', '.') ?></span>
                                                            <?php endif ?>
                                                            <div class="text-muted small">Reg: Rp <?= number_format($program['registration_fee'] ?? 0, 0, ',', '.') ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3 pt-3 border-top">
                                                    <div class="d-grid gap-2">
                                                        <a href="<?= base_url('programs/' . $program['id']) ?>" 
                                                            class="btn btn-outline-secondary btn-sm rounded">
                                                            <i class="bi bi-eye me-1"></i>Detail
                                                        </a>
                                                        <div class="d-flex gap-2">
                                                            <a href="https://wa.me/6282240781299?text=Hai,%20saya%20tertarik%20dengan%20program%20<?= urlencode($program['title']) ?>%20di%20SOS%20Course" 
                                                                class="btn btn-success btn-sm rounded flex-fill" 
                                                                target="_blank">
                                                                <i class="bi bi-share me-1"></i>Share
                                                            </a>
                                                            <a href="<?= base_url('apply/' . $program['id']) ?>" 
                                                                class="btn btn-warning btn-sm rounded flex-fill fw-bold text-dark">
                                                                Apply
                                                            </a>
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
<?php endif ?>

<!-- Why Learn German Section -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold mb-3" style="color: #ffc107;">Mengapa Belajar Bahasa Jerman?</h2>
        <p class="lead text-muted">Jerman adalah kekuatan ekonomi terbesar di Eropa dengan banyak peluang.</p>
    </div>

    <div class="row g-4 justify-content-center text-center">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon-gold mb-3">
                        <i class="bi bi-globe"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Ekonomi Terbesar Eropa</h5>
                    <p class="text-muted small mb-0">Jerman adalah kekuatan ekonomi terbesar di Eropa dengan banyak perusahaan multinasional.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon-gold mb-3">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Peluang Karir</h5>
                    <p class="text-muted small mb-0">Peluang kerja di bidang teknik, kesehatan, penelitian, dan perusahaan Jerman.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon-gold mb-3">
                        <i class="bi bi-airplane"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Pendidikan Gratis</h5>
                    <p class="text-muted small mb-0">Biaya kuliah gratis atau terjangkau di universitas-universitas Jerman.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon-gold mb-3">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Beasiswa Lengkap</h5>
                    <p class="text-muted small mb-0">Banyak pilihan beasiswa dari pemerintah dan universitas Jerman.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 7 Reasons Section -->
<div class="py-5" style="background: linear-gradient(180deg, #fffbf0 0%, #fff 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: #ffc107;">Mengapa Harus SOS Course German?</h2>
        </div>

        <div class="row g-4">
            <!-- Reason 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Ramah Pemula</h5>
                        <p class="text-muted small mb-0">Siswa diajarkan dari dasar sehingga tidak perlu khawatir jika belum pernah belajar bahasa Jerman.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Tutor Profesional</h5>
                        <p class="text-muted small mb-0">Tutor berpengalaman dengan metode pengajaran yang terbukti efektif.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-book"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Kelas Intensif</h5>
                        <p class="text-muted small mb-0">Program padat sampai 5x pertemuan/hari sehingga progres lebih cepat.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-journal-check"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Goethe-Zertifikat</h5>
                        <p class="text-muted small mb-0">Persiapan ujian Goethe-Zertifikat dari A1 sampai C2.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 5 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Bantuan Studi Jerman</h5>
                        <p class="text-muted small mb-0">Bantuan informasi dan bimbingan untuk studi ke Jerman.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 6 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Relasi Luas</h5>
                        <p class="text-muted small mb-0">Kerja sama dengan berbagai institusi dan perusahaan Jerman.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 7 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Garansi Seumur Hidup</h5>
                        <p class="text-muted small mb-0">Siswa dapat mengulang kelas tanpa biaya tambahan berlaku seumur hidup.</p>
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
            <h2 class="display-5 fw-bold mb-4" style="color: #ffc107;">Tentang Kursus Bahasa Jerman SOS</h2>
            <p class="text-muted mb-4">
                SOS Course & Training adalah lembaga kursus bahasa Jerman terbaik yang berlokasi di <strong>Kampung Inggris Pare, Kediri</strong>, Jawa Timur.
            </p>
            <p class="text-muted mb-4">
                Kami membantu Anda mewujudkan mimpi untuk belajar atau bekerja di Jerman. Metode pengajaran kami fokus pada pendekatan komunikatif dan praktis.
            </p>
            <p class="text-muted mb-4">
                Kami menawarkan berbagai pilihan program: Reguler, Semi-Privat, dan Privat dengan fokus pada persiapan Goethe-Zertifikat.
            </p>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="bg-warning text-dark rounded-4 px-4 py-3 text-center">
                    <div class="h4 fw-bold mb-0">Goethe</div>
                    <small>A1-C2</small>
                </div>
                <div class="bg-warning text-dark rounded-4 px-4 py-3 text-center">
                    <div class="h4 fw-bold mb-0">TestDaF</div>
                    <small>Preparation</small>
                </div>
                <div class="bg-warning text-dark rounded-4 px-4 py-3 text-center">
                    <div class="h4 fw-bold mb-0">Conversation</div>
                    <small>Class</small>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="bg-gradient-warning rounded-5 p-5 text-dark shadow-lg">
                <h3 class="fw-bold mb-4">Program Dimulai</h3>
                <div class="display-4 fw-bold mb-3">Tanggal 10</div>
                <p class="mb-0 opacity-75">Tiap bulannya</p>
                <hr class="my-4 opacity-25">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-award fs-1 opacity-50"></i>
                    <div>
                        <div class="fw-bold">Garansi Seumur Hidup</div>
                        <small class="opacity-75">Berlaku S&K</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="py-5 bg-gradient-warning text-dark">
    <div class="container text-center py-5">
        <h2 class="display-5 fw-bold mb-3">Wujudkan Mimpi Studi ke Jerman!</h2>
        <p class="lead mb-4 opacity-75">Jerman menanti Anda. Mulai perjalanan bahasa Jerman Anda hari ini dan raih masa depan cerah!</p>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="https://wa.me/6282240781299?text=Hai,%20saya%20tertarik%20dengan%20kursus%20Bahasa%20Jerman"
                target="_blank"
                class="btn btn-dark btn-lg px-5 shadow fw-bold">
                <i class="bi bi-whatsapp me-2"></i>Konsultasi Gratis via WhatsApp
            </a>
            <a href="<?= base_url('apply') ?>" class="btn btn-outline-dark btn-lg px-5">
                <i class="bi bi-pencil-square me-2"></i>Daftar Sekarang
            </a>
        </div>
    </div>
</div>



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
