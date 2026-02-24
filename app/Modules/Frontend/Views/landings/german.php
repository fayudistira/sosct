<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="hero-section position-relative overflow-hidden py-5" style="min-height: 70vh;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 10% 20%, rgba(42, 157, 143, 0.2) 0%, transparent 50%); pointer-events: none;"></div>
    <div class="position-absolute bottom-0 end-0 w-100 h-100" style="background: radial-gradient(circle at 90% 80%, rgba(42, 157, 143, 0.2) 0%, transparent 50%); pointer-events: none;"></div>

    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="https://flagcdn.com/w80/de.png" alt="Germany Flag" class="rounded shadow" width="60" height="40">
                    <div class="badge bg-white text-success p-2 px-3 rounded-pill shadow-sm">
                        <span class="fw-bold">Goethe Ready</span>
                    </div>
                </div>
                <h1 class="display-3 fw-bold mb-4 text-white">
                    Kursus Bahasa Jerman
                </h1>
                <h2 class="h3 text-white-50 mb-4">Deutschkurs</h2>
                <!-- Meta Tags -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <span class="badge bg-white bg-opacity-30 text-white px-3 py-2 rounded-pill border border-white border-opacity-25">
                        <i class="bi bi-clock me-1"></i> Fleksibel
                    </span>
                    <span class="badge bg-white bg-opacity-30 text-white px-3 py-2 rounded-pill border border-white border-opacity-25">
                        <i class="bi bi-people me-1"></i> Kelas Kecil
                    </span>
                    <span class="badge bg-white bg-opacity-30 text-white px-3 py-2 rounded-pill border border-white border-opacity-25">
                        <i class="bi bi-award me-1"></i> Sertifikat Goethe
                    </span>
                    <span class="badge bg-white bg-opacity-30 text-white px-3 py-2 rounded-pill border border-white border-opacity-25">
                        <i class="bi bi-translate me-1"></i> Native Speaker
                    </span>
                </div>
                <p class="lead mb-4 text-white-50" style="font-size: 1.2rem;">
                    Persiapan kuliah atau Ausbildung di Jerman! Bergabunglah dengan SOS Course and Training dan dapatkan sertifikasi Goethe Institut.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="https://wa.me/6285810310950?text=Hai,%20saya%20tertarik%20dengan%20kursus%20Bahasa%20Jerman"
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
                    <div class="display-1 mb-3">🏰</div>
                    <h3 class="text-white fw-bold mb-3">Deutsch</h3>
                    <p class="text-white-50">Bahasa Jerman</p>
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

<!-- Why Learn German Section -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold mb-3" style="color: var(--dark-red);">Mengapa Belajar Bahasa Jerman?</h2>
        <p class="lead text-muted">Buka peluang pendidikan gratis dan karir cemerlang di Eropa</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Kuliah Gratis</h5>
                    <p class="text-muted small mb-0">Kuliah di universitas Jerman dengan biaya pendidikan yang sangat terjangkau atau gratis.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Ausbildung</h5>
                    <p class="text-muted small mb-0">Ikuti program apprenticeship Jerman dengan gaji dan jaminan kerja setelah lulus.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-globe-europe-africa"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Ekonomi Eropa</h5>
                    <p class="text-muted small mb-0">Jerman adalah ekonomi terbesar di Eropa dengan banyak perusahaan multinasional.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Sertifikasi Goethe</h5>
                    <p class="text-muted small mb-0">Dapatkan sertifikasi Goethe Institut yang diakui internasional untuk visa dan karir.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Available Programs Section -->
<div class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: var(--dark-red);">Program Jerman Tersedia</h2>
            <p class="lead text-muted">Pilih program yang sesuai dengan kebutuhan dan tujuan Anda</p>
        </div>

        <?php if (!empty($programsBySubCategory)): ?>
            <!-- Sub-Category Tabs -->
            <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
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
                                                    <a href="<?= base_url('program/edit/' . $program['id']) ?>" 
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
                                                <div class="d-flex align-items-baseline">
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
                                            <!-- Program Meta Tags -->
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
        <?php else: ?>
            <div class="text-center py-5">
                <div class="feature-icon mb-4 mx-auto" style="width: 100px; height: 100px; font-size: 3rem; background: var(--light-red); color: var(--dark-red);">
                    <i class="bi bi-search"></i>
                </div>
                <h3 class="fw-bold">No Programs Available</h3>
                <p class="text-muted">Belum ada program Jerman yang tersedia saat ini. Silakan hubungi kami untuk informasi lebih lanjut.</p>
                <a href="https://wa.me/6285810310950?text=Hai,%20saya%20mau%20tanya%20tentang%20program%20Jerman"
                    target="_blank"
                    class="btn btn-dark-red rounded-pill mt-3">
                    <i class="bi bi-whatsapp me-2"></i>Hubungi Kami
                </a>
            </div>
        <?php endif ?>
    </div>
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
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Pengenalan dasar Jerman</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Kosakata dasar (1000-2000 kata)</li>
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
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Kosakata lanjutan (4000 kata)</li>
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
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Bisnis Jerman profesional</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Preparasi kuliah/Ausbildung di Jerman</li>
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
                    <p class="text-muted small mb-0">Belajar langsung dari pengajar asli Jerman dengan pengalaman mengajar profesional.</p>
                </div>
            </div>

            <div class="d-flex gap-4 mb-4">
                <div class="bg-light p-3 rounded-4">
                    <i class="bi bi-book-half text-danger fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold">Kurikulum Standar CEFR</h5>
                    <p class="text-muted small mb-0">Mengikuti standar internasional CEFR untuk memastikan kualitas pembelajaran.</p>
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
                    <p class="text-muted small mb-0">Kami menjamin kemampuan Jerman Anda meningkat dengan metode yang terbukti efektif.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="bg-gradient-danger rounded-5 p-5 text-white shadow-lg">
                <h3 class="fw-bold mb-4">Program Unggulan</h3>
                <div class="bg-white bg-opacity-20 rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-2">Studienkolleg Preparation</h5>
                    <p class="small mb-0 opacity-75">Program khusus persiapan Studienkolleg untuk kuliah di universitas Jerman tanpa harus mengikuti DSD.</p>
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

<!-- Call to Action Section -->
<div class="py-5 bg-gradient-danger text-white">
    <div class="container text-center py-5">
        <h2 class="display-5 fw-bold mb-3">Siap Menguasai Bahasa Jerman?</h2>
        <p class="lead mb-4 opacity-75">Daftar sekarang dan dapatkan konsultasi gratis dengan pengajar kami.<br>Mulai perjalanan bahasa Jerman Anda bersama SOS Course and Training!</p>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="https://wa.me/6285810310950?text=Hai,%20saya%20tertarik%20dengan%20kursus%20Bahasa%20Jerman"
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
</style>
<?= $this->endSection() ?>