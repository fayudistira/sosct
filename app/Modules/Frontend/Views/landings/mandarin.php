<?php
/**
 * Mandarin Landing Page
 * 
 * Page-specific SEO and content for Mandarin language course
 */

// Page-specific variables for SEO
$pageTitle = 'Xihuan Mandarin Pare - Kursus Mandarin Kampung Inggris Pare | SOS Course';
$pageDescription = 'Xihuan Mandarin Pare adalah kursus Mandarin terbaik di Kampung Inggris Pare, Kediri. Spesialis pemula dengan Camp Mandarin Area Pertama di Indonesia. Program HSK 1-6, HSKK, Translator.';
$pageKeywords = 'kursus mandarin pare, kampung mandarin pare, les mandarin kediri, kampung inggris pare, belajar mandarin di pare, kursus bahasa mandarin bersertifikat, program HSK pare, translator mandarin, beasiswa china, camp mandarin, Xihuan Mandarin';
$ogImage = 'https://images.pexels.com/photos/3769021/pexels-photo-3769021.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2';
?>

<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('extra_head') ?>
<!-- Page-specific styles for Mandarin landing -->
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
        border-color: var(--dark-red);
        background: #fef9f5;
    }

    .table-tab-btn.active {
        background: var(--dark-red);
        color: white;
        border-color: var(--dark-red);
        box-shadow: 0 4px 12px rgba(139, 0, 0, 0.25);
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
        border-color: var(--dark-red);
        background: #fef9f5;
    }

    .table-cat-btn.active {
        background: var(--dark-red);
        color: white;
        border-color: var(--dark-red);
        box-shadow: 0 2px 8px rgba(139, 0, 0, 0.2);
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
    .feature-icon-red {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #B22222 0%, #8B0000 100%);
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
        background: linear-gradient(135deg, var(--dark-red) 0%, var(--medium-red) 100%);
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
        background: #fef0ef;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dark-red);
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
<div class="hero-section position-relative overflow-hidden py-5" style="min-height: 65vh;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 10% 20%, rgba(220, 20, 60, 0.25) 0%, transparent 50%); pointer-events: none;"></div>
    <div class="position-absolute bottom-0 end-0 w-100 h-100" style="background: radial-gradient(circle at 90% 80%, rgba(220, 20, 60, 0.2) 0%, transparent 50%); pointer-events: none;"></div>

    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="https://flagcdn.com/w80/cn.png" alt="China Flag" class="rounded shadow-sm" width="60" height="40">
                    <div class="badge bg-white text-danger p-2 px-3 rounded-pill shadow-sm fw-bold">
                        Xihuan Mandarin Indonesia
                    </div>
                </div>
                <h1 class="display-3 fw-bold mb-3 text-white">
                    Kursus Mandarin Terbaik di Pare
                </h1>
                <!-- <h2 class="h3 text-white-50 mb-4">Xihuan Mandarin Pare</h2> -->
                <p class="lead mb-4 text-white-90" style="font-size: 1.15rem;">
                    Spesialis Pemula dengan Camp Mandarin Area Pertama di Indonesia. Program HSK 1-6, HSKK, dan Translator.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="https://wa.me/6282240781299?text=Hai,%20saya%20tertarik%20dengan%20kursus%20Bahasa%20Mandarin"
                        target="_blank"
                        class="btn btn-warning btn-lg px-4 shadow fw-bold">
                        <i class="bi bi-whatsapp me-2"></i>Konsultasi Gratis
                    </a>
                    <a href="<?= base_url('apply') ?>" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-pencil-square me-2"></i>Daftar Sekarang
                    </a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="bg-gradient-danger rounded-5 p-5 text-white shadow-lg">
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
</div>

<!-- All Programs Table Section -->
<?php if (!empty($programsByMode)): ?>
<div class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: var(--dark-red);">Daftar Lengkap Program Mandarin</h2>
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
                                                
                                                <!-- Program Table -->
                                                <div class="card border-0 shadow-sm overflow-hidden">
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
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <div class="d-flex gap-2 justify-content-center">
                                                                                    <a href="<?= base_url('programs/' . $program['id']) ?>" 
                                                                                        class="btn btn-outline-secondary btn-sm rounded px-3" 
                                                                                        title="Detail">
                                                                                        <i class="bi bi-eye"></i>
                                                                                    </a>
                                                                                    <a href="<?= base_url('apply/' . $program['id']) ?>" 
                                                                                        class="btn btn-dark-red btn-sm rounded px-3 fw-bold">
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
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                <?php else: ?>
                                    <!-- Single sub-category, show table directly -->
                                    <?php 
                                    $subCatKey = $subCatKeys[0] ?? 'Standard';
                                    $progs = $subCategories[$subCatKey];
                                    ?>
                                    
                                    <div class="card border-0 shadow-sm overflow-hidden">
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
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="d-flex gap-2 justify-content-center">
                                                                        <a href="<?= base_url('programs/' . $program['id']) ?>" 
                                                                            class="btn btn-outline-secondary btn-sm rounded px-3" 
                                                                            title="Detail">
                                                                            <i class="bi bi-eye"></i>
                                                                        </a>
                                                                        <a href="<?= base_url('apply/' . $program['id']) ?>" 
                                                                            class="btn btn-dark-red btn-sm rounded px-3 fw-bold">
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

<!-- Why Learn Mandarin Section -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold mb-3" style="color: var(--dark-red);">Mengapa Belajar Bahasa Mandarin?</h2>
        <p class="lead text-muted">China kini menguasai ekonomi global dan menjadi salah satu negara dengan pertumbuhan tercepat di dunia.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon-red mb-3">
                        <i class="bi bi-globe"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Bahasa Internasional</h5>
                    <p class="text-muted small mb-0">Bahasa dengan lebih dari 1,1 miliar penutur asli di seluruh dunia.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon-red mb-3">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Peluang Karir</h5>
                    <p class="text-muted small mb-0">Banyak peluang bisnis, studi maupun karir yang terbuka luas untuk penutur Mandarin.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon-red mb-3">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Beasiswa China</h5>
                    <p class="text-muted small mb-0">Banyak beasiswa dari Pemerintah China jika mampu menguasai bahasa Mandarin.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon-red mb-3">
                        <i class="bi bi-translate"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Translator</h5>
                    <p class="text-muted small mb-0">Peluang menjadi translator profesional dengan penghasilan menjanjikan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 8 Reasons Section -->
<div class="py-5" style="background: linear-gradient(180deg, #fef9f5 0%, #fff 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: var(--dark-red);">Mengapa Harus Xihuan Mandarin?</h2>
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
                        <p class="text-muted small mb-0">Di sini siswa akan diajarkan dari dasar, sehingga tidak perlu khawatir jika belum pernah belajar bahasa Mandarin.</p>
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
                        <h5 class="fw-bold mb-2">Instruktur Muda & Profesional</h5>
                        <p class="text-muted small mb-0">Walaupun masih relatif muda dan Instruktur berpengalaman mengajar bahasa Mandarin dengan jam terbang tinggi.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-house"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Camp Mandarin Area</h5>
                        <p class="text-muted small mb-0">Di camp siswa diwajibkan berbahasa Mandarin sehingga lebih cepat terbiasa dengan bahasa Mandarin.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-book"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Kelas Intensif</h5>
                        <p class="text-muted small mb-0">Program kami cukup padat sampai 5x pertemuan/hari sehingga progres lebih cepat.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 5 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-journal-check"></i>
                        </div>
                        <h5 class="fw-bold mb-2">HSK-HSKK dan TOCFL</h5>
                        <p class="text-muted small mb-0">Program kami lengkap mulai dari HSK-HSKK sampai TOCFL dan sesuai dengan standar kurikulum masing-masing.</p>
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
                        <h5 class="fw-bold mb-2">Relasi kuat</h5>
                        <p class="text-muted small mb-0">Kami bekerjasama dengan berbagai Perusahaan yang merekrut tenaga kerja baik sebagai Translator Mandarin maupun Staff.</p>
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
                        <p class="text-muted small mb-0">Siswa dapat mengulang kelas yang ingin diikuti kembali tanpa biaya tambahan dan berlaku seumur hidup.</p>
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
            <h2 class="display-5 fw-bold mb-4" style="color: var(--dark-red);">Tentang Xihuan Mandarin</h2>
            <p class="text-muted mb-4">
                Xihuan Mandarin Indonesia adalah lembaga kursus bahasa Mandarin Spesialis Pemula dengan <strong>Camp Mandarin Area Pertama di Indonesia</strong> yang terletak di Pare – Kediri Jawa Timur.
            </p>
            <p class="text-muted mb-4">
                Xihuan Mandarin Indonesia adalahBerada di bawah manajemen SOS Course & Training yang telah berdiri sejak tahun 2013 sehingga memiliki kredibilitas tinggi karena resmi terdaftar di Dinas Pendidikan.
            </p>
            <p class="text-muted mb-4">
                Kami memiliki program yang cukup lengkap mencakup HSK-HSKK hingga TOCFL dengan kurikulum terstandar sesuai penyelenggara ujian masing-masing.
            </p>
            <p class="text-muted mb-4">
                Salah satu indikator kesuksesan kami adalah jika alumni kami berhasil dalam studi dan karirnya.
            </p>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="bg-danger text-white rounded-4 px-4 py-3 text-center">
                    <div class="h4 fw-bold mb-0">HSK</div>
                    <small>HSK 1-6</small>
                </div>
                <div class="bg-danger text-white rounded-4 px-4 py-3 text-center">
                    <div class="h4 fw-bold mb-0">HSKK</div>
                    <small>Beginner-Advance</small>
                </div>
                <div class="bg-danger text-white rounded-4 px-4 py-3 text-center">
                    <div class="h4 fw-bold mb-0">TOCFL</div>
                    <small>Beginner-Advance</small>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="bg-gradient-danger rounded-5 p-5 text-white shadow-lg">
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
<div class="py-5 bg-gradient-danger text-white">
    <div class="container text-center py-5">
        <h2 class="display-5 fw-bold mb-3">Jangan Tunda Kesuksesan Anda Sendiri!</h2>
        <p class="lead mb-4 opacity-75">Lowongan kerja dan beasiswa Mandarin semakin banyak dan semakin kompetitif. Jangan sampai tertinggal. Belajar Mandarin hari ini, sukses menanti besok! </p>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="https://wa.me/6282240781299?text=Hai,%20saya%20tertarik%20dengan%20kursus%20Bahasa%20Mandarin"
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
