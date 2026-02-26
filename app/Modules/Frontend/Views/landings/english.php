<?php
/**
 * English Landing Page
 * 
 * Page-specific SEO and content for English language course
 */

// Page-specific variables for SEO
$pageTitle = 'Kursus Bahasa Inggris Kampung Inggris Pare - SOS Course | TOEFL/IELTS';
$pageDescription = 'Kursus Bahasa Inggris terbaik di Kampung Inggris Pare, Kediri. Spesialis TOEFL, IELTS, dan English for Business. Program fleksibel dengan native speaker.';
$pageKeywords = 'kursus bahasa inggris pare, Kampung Inggris Pare, les inggris kediri, kursus toefl pare, kursus ielts pare, belajar inggris di pare, kursus bahasa inggris bersertifikat, sos course, english course, toefl, ielts';
$ogImage = 'https://images.pexels.com/photos/6324702/pexels-photo-6324702.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2';
?>

<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('extra_head') ?>
<!-- Page-specific styles for English landing -->
<style>
    /* Custom Scrollbar for mobile navigation */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Language Header Tabs - Blue Background */
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
        color: #457b9d;
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
        background: #a8dadc;
        color: #457b9d;
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
        background: #457b9d;
        color: white;
        border-color: #457b9d;
        box-shadow: 0 2px 8px rgba(69, 123, 157, 0.25);
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
        border-color: #457b9d;
        background: #f0f9ff;
    }

    .table-tab-btn.active {
        background: #457b9d;
        color: white;
        border-color: #457b9d;
        box-shadow: 0 4px 12px rgba(69, 123, 157, 0.25);
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
        border-color: #457b9d;
        background: #f0f9ff;
    }

    .table-cat-btn.active {
        background: #457b9d;
        color: white;
        border-color: #457b9d;
        box-shadow: 0 2px 8px rgba(69, 123, 157, 0.2);
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
        background: linear-gradient(135deg, #457b9d 0%, #1d3557 100%);
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
    .feature-icon-blue {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #457b9d 0%, #1d3557 100%);
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
        background: linear-gradient(135deg, #457b9d 0%, #1d3557 100%);
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
        background: #e8f4f8;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #457b9d;
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
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 10% 20%, rgba(69, 123, 157, 0.2) 0%, transparent 50%); pointer-events: none;"></div>
    <div class="position-absolute bottom-0 end-0 w-100 h-100" style="background: radial-gradient(circle at 90% 80%, rgba(69, 123, 157, 0.2) 0%, transparent 50%); pointer-events: none;"></div>

    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="https://flagcdn.com/w80/gb.png" alt="UK Flag" class="rounded shadow" width="60" height="40">
                    <div class="badge bg-white text-primary p-2 px-3 rounded-pill shadow-sm">
                        <span class="fw-bold">TOEFL/IELTS Ready</span>
                    </div>
                </div>
                <h1 class="display-3 fw-bold mb-4 text-white">
                    Kursus Bahasa Inggris
                </h1>
                <h2 class="h3 text-white-50 mb-4">English Course</h2>
                <!-- Meta Tags -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <span class="badge bg-white bg-opacity-30 text-white px-3 py-2 rounded-pill border border-white border-opacity-25">
                        <i class="bi bi-clock me-1"></i> Fleksibel
                    </span>
                    <span class="badge bg-white bg-opacity-30 text-white px-3 py-2 rounded-pill border border-white border-opacity-25">
                        <i class="bi bi-people me-1"></i> Kelas Kecil
                    </span>
                    <span class="badge bg-white bg-opacity-30 text-white px-3 py-2 rounded-pill border border-white border-opacity-25">
                        <i class="bi bi-award me-1"></i> Sertifikat Resmi
                    </span>
                    <span class="badge bg-white bg-opacity-30 text-white px-3 py-2 rounded-pill border border-white border-opacity-25">
                        <i class="bi bi-translate me-1"></i> Native Speaker
                    </span>
                </div>
                <p class="lead mb-4 text-white-50" style="font-size: 1.2rem;">
                    Metode Kampung Inggris Pare yang legendaris! Bergabunglah dengan SOS Course and Training dan persiapkan diri untuk TOEFL, IELTS, dan English for Business.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="https://wa.me/6285810310950?text=Hai,%20saya%20tertarik%20dengan%20kursus%20Bahasa%20Inggris"
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
                    <div class="display-1 mb-3">📚</div>
                    <h3 class="text-white fw-bold mb-3">English</h3>
                    <p class="text-white-50">Bahasa Inggris</p>
                    <div class="mt-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                    <div class="h4 fw-bold text-white mb-0">A1-C2</div>
                                    <small class="text-white-50">Level CEFR</small>
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

<!-- Why Learn English Section -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold mb-3" style="color: var(--dark-red);">Mengapa Belajar Bahasa Inggris?</h2>
        <p class="lead text-muted">Bahasa internasional yang membuka peluang di seluruh dunia</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-globe"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Bahasa Global</h5>
                    <p class="text-muted small mb-0">Bahasa Inggris adalah bahasa internasional yang digunakan di seluruh dunia untuk bisnis dan komunikasi.</p>
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
                    <p class="text-muted small mb-0">Banyak perusahaan multinasional mensyaratkan kemampuan bahasa Inggris untuk posisi strategis.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Studi Luar Negeri</h5>
                    <p class="text-muted small mb-0">Kuliah di universitas luar negeri dengan beasiswa yang mensyaratkan skor TOEFL/IELTS.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Sertifikasi TOEFL/IELTS</h5>
                    <p class="text-muted small mb-0">Dapatkan sertifikasi TOEFL/IELTS yang diakui internasional untuk keperluan akademik dan karir.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 7 Reasons Section -->
<div class="py-5" style="background: linear-gradient(180deg, #f0f9ff 0%, #fff 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: #457b9d;">Mengapa Harus SOS Course?</h2>
        </div>

        <div class="row g-4">
            <!-- Reason 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-globe"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Bahasa Global</h5>
                        <p class="text-muted small mb-0">Bahasa Inggris adalah bahasa internasional yang digunakan di seluruh dunia untuk bisnis, akademisi, dan komunikasi.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Peluang Karir</h5>
                        <p class="text-muted small mb-0">Menguasai bahasa Inggris membuka pintu karir yang lebih luas baik di dalam maupun luar negeri.</p>
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
                        <h5 class="fw-bold mb-2">Kuliah Abroad</h5>
                        <p class="text-muted small mb-0">Persiapan TOEFL/IELTS untuk masuk universitas ternama di luar negeri.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Native Speaker</h5>
                        <p class="text-muted small mb-0">Pengajar native speaker yang berpengalaman dalam mengajar bahasa Inggris.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 5 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-book"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Kelas Intensif</h5>
                        <p class="text-muted small mb-0">Program intensif dengan hingga 5x pertemuan per hari untuk progres lebih cepat.</p>
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
                        <h5 class="fw-bold mb-2">Sertifikat Resmi</h5>
                        <p class="text-muted small mb-0">Sertifikat yang diakui secara nasional dan internasional.</p>
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
                        <p class="text-muted small mb-0">Terletak di lokasi strategis di Kampung Inggris Pare, Kediri.</p>
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
            <p class="text-muted">Belum ada program Inggris yang tersedia saat ini. Silakan hubungi kami untuk informasi lebih lanjut.</p>
            <a href="https://wa.me/6285810310950?text=Hai,%20saya%20mau%20tanya%20tentang%20program%20Inggris"
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
                        <h5 class="fw-bold mb-0">A1-A2 (Beginner)</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Pengenalan dasar Inggris</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Kosakata dasar (1500-3000 kata)</li>
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
                        <h5 class="fw-bold mb-0">B1-B2 (Intermediate)</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Percakapan bisnis dasar</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Kosakata lanjutan (4000-6000 kata)</li>
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
                        <h5 class="fw-bold mb-0">C1-C2 (Advanced)</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Fluensi tingkat tinggi</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Kosakata mahir (8000+ kata)</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>English for Business</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Preparasi TOEFL/IELTS</li>
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
                    <p class="text-muted small mb-0">Belajar langsung dari pengajar asli Inggris/Amerika dengan pengalaman mengajar profesional.</p>
                </div>
            </div>

            <div class="d-flex gap-4 mb-4">
                <div class="bg-light p-3 rounded-4">
                    <i class="bi bi-book-half text-danger fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold">Metode Kampung Inggris Pare</h5>
                    <p class="text-muted small mb-0">Metode pembelajaran intensif yang telah terbukti efektif sejak 1977 di Kampung Inggris Pare.</p>
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
                    <p class="text-muted small mb-0">Kami menjamin kemampuan Inggris Anda meningkat dengan metode yang terbukti efektif.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="bg-gradient-danger rounded-5 p-5 text-white shadow-lg">
                <h3 class="fw-bold mb-4">Program Unggulan</h3>
                <div class="bg-white bg-opacity-20 rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-2">Kampung Inggris Pare Method</h5>
                    <p class="small mb-0 opacity-75">Metode pembelajaran intensif dengan lingkungan English area, speaking practice setiap hari, dan kurikulum komprehensif.</p>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-white bg-opacity-20 rounded-3 p-3 text-center">
                            <div class="h4 fw-bold mb-0">A1-C2</div>
                            <small class="opacity-75">Level CEFR</small>
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
            <h2 class="display-5 fw-bold mb-4" style="color: #457b9d;">Tentang Kursus Bahasa Inggris SOS Course</h2>
            <p class="text-muted mb-4">
                SOS Course and Training adalah lembaga kursus bahasa Inggris terbaik di <strong>Kampung Inggris Pare</strong>, Kediri Jawa Timur yang telah berdiri sejak tahun 2013.
            </p>
            <p class="text-muted mb-4">
                Kami adalah pusat pembelajaran bahasa Inggris dengan metode Kampung Inggris Pare yang telah terbukti efektif. Programs kami mencakup TOEFL, IELTS, dan English for Business dengan kurikulum yang disesuaikan dengan kebutuhan Anda.
            </p>
            <p class="text-muted mb-4">
                Dengan pengajar native speaker dan berpengalaman, kami siap membantu Anda mencapai target bahasa Inggris dalam waktu singkat.
            </p>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="bg-primary text-white rounded-4 px-4 py-3 text-center">
                    <div class="h4 fw-bold mb-0">TOEFL</div>
                    <small>Preparation</small>
                </div>
                <div class="bg-primary text-white rounded-4 px-4 py-3 text-center">
                    <div class="h4 fw-bold mb-0">IELTS</div>
                    <small>Academic</small>
                </div>
                <div class="bg-primary text-white rounded-4 px-4 py-3 text-center">
                    <div class="h4 fw-bold mb-0">Business</div>
                    <small>English</small>
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
                    <i class="bi bi-award fs-1 opacity-50"></i>
                    <div>
                        <div class="fw-bold">Sertifikat Resmi</div>
                        <small class="opacity-75">Berlaku Nasional</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="py-5 bg-gradient-danger text-white">
    <div class="container text-center py-5">
        <h2 class="display-5 fw-bold mb-3">Siap Menguasai Bahasa Inggris?</h2>
        <p class="lead mb-4 opacity-75">Daftar sekarang dan dapatkan konsultasi gratis dengan pengajar kami.<br>Mulai perjalanan bahasa Inggris Anda bersama SOS Course and Training!</p>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="https://wa.me/6285810310950?text=Hai,%20saya%20tertarik%20dengan%20kursus%20Bahasa%20Inggris"
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
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .btn-lang-header {
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
        }
        
        .pill-tab-btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }
        
        .pill-tab-btn-sm {
            padding: 0.3rem 0.7rem;
            font-size: 0.75rem;
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