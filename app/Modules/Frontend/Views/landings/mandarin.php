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
        background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.7) 50%, transparent 100%);
    }
    .tab-pane {
        animation: fadeIn 0.4s ease-out;
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
                        Camp Mandarin Area Pertama di Indonesia
                    </div>
                </div>
                <h1 class="display-3 fw-bold mb-3 text-white">
                    Kursus Mandarin Pare Terbaik
                </h1>
                <h2 class="h3 text-white-50 mb-4">Xihuan Mandarin Pare</h2>
                <p class="lead mb-4 text-white-90" style="font-size: 1.15rem;">
                    Spesialis Pemula dengan Camp Mandarin Area Pertama di Indonesia. Program HSK 1-6, HSKK, dan Translator.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="https://wa.me/6285810310950?text=Hai,%20saya%20tertarik%20dengan%20kursus%20Bahasa%20Mandarin"
                        target="_blank"
                        class="btn btn-warning btn-lg px-4 shadow fw-bold">
                        <i class="bi bi-whatsapp me-2"></i>Konsultasi Gratis
                    </a>
                    <a href="<?= base_url('apply') ?>" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-pencil-square me-2"></i>Daftar Sekarang
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Available Programs Section -->
<div class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: var(--dark-red);">Program Mandarin Tersedia</h2>
            <p class="lead text-muted">Pilih program yang sesuai dengan kebutuhan dan tujuan Anda</p>
        </div>

        <?php if (!empty($programsBySubCategory)): ?>
            <!-- Sub-Category Tabs -->
            <div class="d-flex align-items-center mb-4 pb-2 border-bottom flex-wrap gap-2">
                <h4 class="fw-bold mb-0 me-4">Kategori Program</h4>
                <div class="nav nav-pills sub-category-pills d-flex gap-2" role="tablist">
                    <?php foreach ($subCategories as $subIndex => $subCategory): ?>
                        <button class="nav-link btn btn-sm rounded-pill btn-sub-cat <?= ($subIndex === 0) ? 'active' : '' ?>"
                            id="sub-tab-<?= $subIndex ?>"
                            data-bs-toggle="pill"
                            data-bs-target="#sub-category-<?= $subIndex ?>"
                            type="button"
                            role="tab"
                            aria-controls="sub-category-<?= $subIndex ?>"
                            aria-selected="<?= ($subIndex === 0) ? 'true' : 'false' ?>">
                            <?= esc($subCategory) ?>
                            <span class="ms-1 opacity-50 small">(<?= count($programsBySubCategory[$subCategory]) ?>)</span>
                        </button>
                    <?php endforeach ?>
                </div>
            </div>

            <!-- Sub-Tab Content -->
            <div class="tab-content">
                <?php foreach ($subCategories as $subIndex => $subCategory): ?>
                    <div class="tab-pane fade <?= ($subIndex === 0) ? 'show active' : '' ?>"
                        id="sub-category-<?= $subIndex ?>"
                        role="tabpanel">

                        <!-- Programs Grid -->
                        <div class="row g-4">
                            <?php foreach ($programsBySubCategory[$subCategory] as $program): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card border-0 shadow-sm h-100 hover-lift overflow-hidden program-card-modern">
                                        <!-- Image Container -->
                                        <div class="position-relative overflow-hidden" style="height: 200px;">
                                            <?php if (!empty($program['thumbnail'])): ?>
                                                <img src="<?= base_url('uploads/programs/thumbs/' . $program['thumbnail']) ?>"
                                                    alt="<?= esc($program['title']) ?>"
                                                    class="w-100 h-100 object-fit-cover program-img-zoom">
                                            <?php else: ?>
                                                <?php
                                                $seed = crc32($program['id']);
                                                $randomId = ($seed % 1000) + 1;
                                                ?>
                                                <img src="https://picsum.photos/seed/<?= $randomId ?>/800/600"
                                                    alt="<?= esc($program['title']) ?>"
                                                    class="w-100 h-100 object-fit-cover program-img-zoom"
                                                    loading="lazy">
                                            <?php endif ?>

                                            <!-- Category Overlay -->
                                            <div class="position-absolute top-0 end-0 m-3 d-flex gap-2">
                                                <?php if (auth()->loggedIn() && auth()->user()->inGroup('superadmin')): ?>
                                                    <a href="<?= base_url('admin/programs/' . $program['id'] . '/edit') ?>" 
                                                        class="badge bg-warning text-dark shadow-sm py-2 px-3 rounded-pill fw-bold text-decoration-none" 
                                                        style="font-size: 0.7rem;"
                                                        title="Edit Program">
                                                        <i class="bi bi-pencil-square me-1"></i>Edit
                                                    </a>
                                                <?php endif ?>
                                                <span class="badge bg-white text-dark shadow-sm py-2 px-3 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                                    <?= strtoupper(esc((string)($program['sub_category'] ?? 'Standard'))) ?>
                                                </span>
                                            </div>

                                            <!-- Price Overlay -->
                                            <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark text-white">
                                                <?php
                                                $tuitionFee = $program['tuition_fee'] ?? 0;
                                                $registrationFee = $program['registration_fee'] ?? 0;
                                                $discount = $program['discount'] ?? 0;
                                                $discountedPrice = $tuitionFee * (1 - $discount / 100);
                                                ?>
                                                <div class="d-flex align-items-baseline flex-wrap">
                                                    <span class="h4 fw-bold mb-0">Rp <?= number_format($discountedPrice, 0, ',', '.') ?></span>
                                                    <?php if (!empty($discount) && $discount > 0): ?>
                                                        <span class="ms-2 text-white-50 text-decoration-line-through small">Rp <?= number_format($tuitionFee, 0, ',', '.') ?></span>
                                                        <span class="ms-auto badge bg-danger rounded-pill">-<?= $discount ?>%</span>
                                                    <?php endif ?>
                                                </div>
                                                <?php if ($registrationFee > 0): ?>
                                                    <div class="small text-white-50 mt-1">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        + Biaya pendaftaran: Rp <?= number_format($registrationFee, 0, ',', '.') ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>

                                        <div class="card-body d-flex flex-column p-4">
                                            <h5 class="fw-bold mb-2 text-dark"><?= esc($program['title']) ?></h5>
                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                <?php if (!empty($program['language'])): ?>
                                                    <span class="badge bg-info text-white small">
                                                        <i class="bi bi-translate me-1"></i>
                                                        <?= esc($program['language']) ?>
                                                    </span>
                                                <?php endif ?>
                                                <?php if (!empty($program['language_level'])): ?>
                                                    <span class="badge bg-secondary small">
                                                        <?= esc($program['language_level']) ?>
                                                    </span>
                                                <?php endif ?>
                                                <?php if (!empty($program['mode'])): ?>
                                                    <span class="badge bg-light text-muted border small">
                                                        <i class="bi bi-<?= (string)($program['mode'] ?? '') === 'online' ? 'wifi' : 'building' ?> me-1"></i>
                                                        <?= ucfirst(esc((string)($program['mode'] ?? ''))) ?>
                                                    </span>
                                                <?php endif ?>
                                                <?php if (!empty($program['sub_category'])): ?>
                                                    <span class="badge bg-light text-muted border small">
                                                        <i class="bi bi-tag me-1"></i>
                                                        <?= esc((string)($program['sub_category'] ?? '')) ?>
                                                    </span>
                                                <?php endif ?>
                                            </div>
                                            <p class="text-muted small flex-grow-1 mb-4">
                                                <?= esc(strlen($program['description'] ?? '') > 100 ? substr($program['description'], 0, 100) . '...' : ($program['description'] ?? 'Program berkualitas untuk menguasai bahasa Mandarin.')) ?>
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
        <?php else: ?>
            <div class="text-center py-5">
                <div class="feature-icon mb-4 mx-auto" style="width: 100px; height: 100px; font-size: 3rem; background: var(--light-red); color: var(--dark-red);">
                    <i class="bi bi-search"></i>
                </div>
                <h3 class="fw-bold">Program Akan Segera Hadir</h3>
                <p class="text-muted">Program Mandarin sedang dalam persiapan. Silakan hubungi kami untuk informasi lebih lanjut.</p>
                <a href="https://wa.me/6285810310950?text=Hai,%20saya%20mau%20tanya%20tentang%20program%20Mandarin"
                    target="_blank"
                    class="btn btn-dark-red rounded-pill mt-3">
                    <i class="bi bi-whatsapp me-2"></i>Hubungi Kami
                </a>
            </div>
        <?php endif ?>
    </div>
</div>

<!-- Why Learn Mandarin Section -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold mb-3" style="color: var(--dark-red);">Mengapa Belajar Bahasa Mandarin?</h2>
        <p class="lead text-muted">China telah menjadi negara adidaya, sudah semestinya kita mempelajari bahasa dan budaya dari negara maju</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon-red mb-3">
                        <i class="bi bi-globe-asia-australia"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Bahasa Internasional</h5>
                    <p class="text-muted small mb-0">Bahasa Mandarin adalah bahasa Internasional yang sering digunakan dalam dunia bisnis.</p>
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
                    <p class="text-muted small mb-0">Banyak kesempatan emas untuk mendapatkan promosi jabatan dan transaksi internasional.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon-red mb-3">
                        <i class="bi bi-currency-yen"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Nilai Tinggi</h5>
                    <p class="text-muted small mb-0">Menguasai Mandarin akan meningkatkan kualitas nilai dan dihargai di dunia kerja.</p>
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
    </div>
</div>

<!-- 8 Reasons Section -->
<div class="py-5" style="background: linear-gradient(180deg, #fef9f5 0%, #fff 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: var(--dark-red);">8 Alasan Mengapa Harus Xihuan Mandarin Pare?</h2>
        </div>

        <div class="row g-4">
            <!-- Reason 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Spesialis Pemula</h5>
                        <p class="text-muted small mb-0">Kursusan Mandarin terfokus pada pengembangan skill bahasa mandarin untuk pemula hingga mampu menjadi Translator Mandarin.</p>
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
                        <h5 class="fw-bold mb-2">Pengajar Pro & Kekinian</h5>
                        <p class="text-muted small mb-0">Pengajar adalah pengajar muda namun Profesional dan berpengalaman, juga semua sudah TERSERTIFIKASI.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-camp"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Camp Mandarin Area</h5>
                        <p class="text-muted small mb-0">Di Camp terdapat mandarin area yang artinya siswa hampir setiap hari harus berbahasa mandarin.</p>
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
                        <h5 class="fw-bold mb-2">Kelas Intensive</h5>
                        <p class="text-muted small mb-0">Kelas reguler maksimal 15 Siswa/kelas. Kelas HSK maksimal 10 Orang. INTENSIVE DAN KONDUSIF.</p>
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
                        <h5 class="fw-bold mb-2">Kelas HSK - HSKK</h5>
                        <p class="text-muted small mb-0">Program lengkap dari Basic hingga HSK level 1-6, HSKK Basic dan Intermediate, serta kelas Translator.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 6 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-network"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Super Networking</h5>
                        <p class="text-muted small mb-0">Bekerja sama dengan 27+ Perusahaan untuk persaingan tenaga kerja sebagai Translator Mandarin.</p>
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
                        <h5 class="fw-bold mb-2">Mengulang Kelas Gratis</h5>
                        <p class="text-muted small mb-0">Murid diperbolehkan mengulang kelas gratis jika nilai ujian tidak memenuhi standar.</p>
                    </div>
                </div>
            </div>

            <!-- Reason 8 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-lift bg-white">
                    <div class="card-body p-4">
                        <div class="reason-icon">
                            <i class="bi bi-heart-pulse"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Fasilitas Kesehatan</h5>
                        <p class="text-muted small mb-0">Bekerja sama dengan RS untuk konsultasi kesehatan dan pengantaran ke klinik.</p>
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
            <h2 class="display-5 fw-bold mb-4" style="color: var(--dark-red);">Tentang Xihuan Mandarin Pare</h2>
            <p class="text-muted mb-4">
                Xihuan Mandarin Pare adalah kursus bahasa Mandarin Spesialis Pemula dengan <strong>Camp Mandarin Area Pertama di Indonesia</strong> yang terletak di Pare – Kediri Jawa Timur.
            </p>
            <p class="text-muted mb-4">
                Xihuan Mandarin Pare juga mempunyai Program HSK dari Level 1 – 6, cocok untuk kamu yang mau mencoba apply beasiswa atau bekerja di Perusahaan Internasional.
            </p>
            <p class="text-muted mb-4">
                Salah satu indikator kesuksesan kami adalah jika murid mampu kuliah di luar negeri atau karirnya cemerlang di masa depan.
            </p>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="bg-danger text-white rounded-4 px-4 py-3 text-center">
                    <div class="h4 fw-bold mb-0">27+</div>
                    <small>Partner Perusahaan</small>
                </div>
                <div class="bg-danger text-white rounded-4 px-4 py-3 text-center">
                    <div class="h4 fw-bold mb-0">HSK 1-6</div>
                    <small>Program Tersedia</small>
                </div>
                <div class="bg-danger text-white rounded-4 px-4 py-3 text-center">
                    <div class="h4 fw-bold mb-0">Gratis</div>
                    <small>Mengulang Kelas</small>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="bg-gradient-danger rounded-5 p-5 text-white shadow-lg">
                <h3 class="fw-bold mb-4">Mulai Tiap Periode</h3>
                <div class="display-4 fw-bold mb-3">10 & 25</div>
                <p class="mb-0 opacity-75">Tiap bulannya</p>
                <hr class="my-4 opacity-25">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                    <div>
                        <div class="fw-bold">Tanggal 10 & 25</div>
                        <small class="opacity-75">Setiap bulan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="py-5 bg-gradient-danger text-white">
    <div class="container text-center py-5">
        <h2 class="display-5 fw-bold mb-3">Siap Menguasai Bahasa Mandarin?</h2>
        <p class="lead mb-4 opacity-75">Daftar sekarang dan mulai perjalanan bahasa Mandarin Anda bersama Xihuan Mandarin Pare!<br>Mulai tiap tanggal 10 & 25 setiap bulan.</p>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="https://wa.me/6285810310950?text=Hai,%20saya%20tertarik%20dengan%20kursus%20Bahasa%20Mandarin"
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

<?= $this->endSection() ?>
