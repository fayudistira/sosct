<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Hero Showcase Section -->
<div class="hero-section position-relative overflow-hidden py-5 d-flex align-items-center" style="min-height: 85vh;">
    <!-- Abstract background elements -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 10% 20%, rgba(139, 0, 0, 0.15) 0%, transparent 50%); pointer-events: none;"></div>
    <div class="position-absolute bottom-0 end-0 w-100 h-100" style="background: radial-gradient(circle at 90% 80%, rgba(139, 0, 0, 0.15) 0%, transparent 50%); pointer-events: none;"></div>
    
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="badge bg-white text-dark mb-3 p-2 px-3 rounded-pill shadow-sm animate-fade-in">
                    <span class="text-danger fw-bold">🌏</span> KAMPUNG INGGRIS PARE
                </div>
                <h1 class="display-3 fw-bold mb-4 animate-slide-up text-white">
                    Ingin Kursus Bahasa <span class="text-warning language-switch" id="languageSwitch">Mandarin</span> di Pare?
                </h1>
                <p class="lead mb-4 text-white-50 animate-slide-up-delay-1" style="font-size: 1.25rem;">
                    Bergabunglah dengan SOS Course and Training! Metode Kampung Inggris Pare yang terbukti efektif, pengajar bersertifikat internasional, dan garansi bisa. Wujudkan impian Anda menguasai bahasa asing!
                </p>
                <div class="d-flex flex-wrap gap-3 animate-slide-up-delay-2">
                    <a href="https://wa.me/6285810310950?text=Hai,%20saya%20mau%20konsultasi%20mengenai%20program%20kursus%20di%20SOS%20Course%20and%20Training" 
                       target="_blank"
                       class="btn btn-warning btn-lg px-4 shadow fw-bold">
                        <i class="bi bi-whatsapp me-2"></i>Konsultasi Gratis
                    </a>
                    <a href="<?= base_url('programs') ?>" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-book me-2"></i>Lihat Program
                    </a>
                </div>
                
                <div class="mt-5 d-flex align-items-center gap-4 animate-fade-in-delay-3">
                    <div class="d-flex flex-column">
                        <span class="h4 fw-bold mb-0 text-white">2013</span>
                        <span class="small text-white-50">Berdiri Sejak</span>
                    </div>
                    <div class="vr bg-white opacity-25" style="height: 40px;"></div>
                    <div class="d-flex flex-column">
                        <span class="h4 fw-bold mb-0 text-white">5</span>
                        <span class="small text-white-50">Bahasa Asing</span>
                    </div>
                    <div class="vr bg-white opacity-25" style="height: 40px;"></div>
                    <div class="d-flex flex-column">
                        <span class="h4 fw-bold mb-0 text-white">1000+</span>
                        <span class="small text-white-50">Alumni Sukses</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 hero-image-container">
                <!-- Animasi 3D Oblique Logo Orbit -->
                <div class="animation-3d-system">
                    <!-- Center Logo dengan efek mengapung -->
                    <div class="animation-logo-container">
                        <img
                            src="https://kursusbahasa.org/assets/img/logo-sos.webp"
                            alt="Logo KursusBahasa.org - Kursus Bahasa Asing di Kampung Inggris Pare"
                            class="animation-logo"
                            width="200"
                            height="200"
                        />
                    </div>

                    <!-- Orbiting Flags -->
                    <div class="animation-orbit" style="--angle-offset: 0deg">
                        <img
                            src="https://flagcdn.com/w80/cn.png"
                            alt="Bendera China - Kursus Bahasa Mandarin di Kampung Inggris"
                            class="animation-flag"
                            width="50"
                            height="35"
                            loading="lazy"
                        />
                    </div>
                    <div class="animation-orbit" style="--angle-offset: 72deg">
                        <img
                            src="https://flagcdn.com/w80/jp.png"
                            alt="Bendera Jepang - Kursus Bahasa Jepang di Kampung Inggris"
                            class="animation-flag"
                            width="50"
                            height="35"
                            loading="lazy"
                        />
                    </div>
                    <div class="animation-orbit" style="--angle-offset: 144deg">
                        <img
                            src="https://flagcdn.com/w80/kr.png"
                            alt="Bendera Korea - Kursus Bahasa Korea di Kampung Inggris"
                            class="animation-flag"
                            width="50"
                            height="35"
                            loading="lazy"
                        />
                    </div>
                    <div class="animation-orbit" style="--angle-offset: 216deg">
                        <img
                            src="https://flagcdn.com/w80/gb.png"
                            alt="Bendera Inggris - Kursus Bahasa Inggris di Kampung Inggris"
                            class="animation-flag"
                            width="50"
                            height="35"
                            loading="lazy"
                        />
                    </div>
                    <div class="animation-orbit" style="--angle-offset: 288deg">
                        <img
                            src="https://flagcdn.com/w80/de.png"
                            alt="Bendera Jerman - Kursus Bahasa Jerman di Kampung Inggris"
                            class="animation-flag"
                            width="50"
                            height="35"
                            loading="lazy"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Language Categories Section -->
<div class="container py-5 pt-5 mt-5 position-relative" style="z-index: 5;">
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold mb-3" style="color: var(--dark-red);">Pilih Bahasa yang Ingin Anda Kuasai</h2>
        <p class="lead text-muted">Kami menawarkan kursus lima bahasa asing dengan pengajar berpengalaman dan metode pembelajaran terbaik</p>
    </div>
    
    <div class="row g-4 justify-content-center">
        <!-- Mandarin -->
        <div class="col-md-6 col-lg-4">
            <a href="<?= base_url('mandarin') ?>" class="text-decoration-none">
                <div class="card-language border-0 shadow-lg p-4 hover-lift overflow-hidden position-relative" style="background: linear-gradient(135deg, #dc143c 0%, #b91226 100%);">
                    <div class="position-absolute top-0 end-0 opacity-10" style="font-size: 8rem; margin-top: -2rem; margin-right: -2rem;">
                        🇨🇳
                    </div>
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="language-icon me-3" style="background: rgba(255,255,255,0.2);">
                                <span style="font-size: 2rem;">🀄</span>
                            </div>
                            <div>
                                <h4 class="fw-bold text-white mb-1">Bahasa Mandarin</h4>
                                <small class="text-white-50">Chinese Language</small>
                            </div>
                        </div>
                        <p class="text-white-50 small mb-3">Kuasai bahasa dengan penutur terbanyak di dunia. Buka peluang karir di perusahaan multinasional.</p>
                        
                        <!-- Featured Program with Flip Effect -->
                        <div class="featured-program-container mb-3" data-language="mandarin">
                            <div class="featured-program-flipper">
                                <div class="featured-program-front">
                                    <?php 
                                    $mandarinProgs = $programsByLanguage['mandarin'] ?? [];
                                    if (!empty($mandarinProgs)): 
                                        $randomProg = $mandarinProgs[array_rand($mandarinProgs)];
                                        $finalPrice = $randomProg['tuition_fee'] * (1 - ($randomProg['discount'] ?? 0) / 100);
                                    ?>
                                        <div class="bg-white bg-opacity-10 rounded-3 p-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="flex-shrink-0">
                                                    <?php if (!empty($randomProg['thumbnail'])): ?>
                                                        <img src="<?= base_url('uploads/programs/thumbs/' . $randomProg['thumbnail']) ?>" 
                                                            alt="<?= esc($randomProg['title']) ?>" 
                                                            class="rounded" 
                                                            style="width: 40px; height: 30px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-white bg-opacity-20 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 30px;">
                                                            <i class="bi bi-journal-text text-white small"></i>
                                                        </div>
                                                    <?php endif ?>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <div class="text-white small fw-semibold text-truncate"><?= esc($randomProg['title']) ?></div>
                                                    <div class="text-white-50 small">Rp <?= number_format($finalPrice, 0, ',', '.') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="bg-white bg-opacity-10 rounded-3 p-2 text-center">
                                            <span class="text-white-50 small">Program belum tersedia</span>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-white text-danger px-3 py-2">HSK/HSKK - TOCFL</span>
                            <i class="bi bi-arrow-right-circle-fill text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Japanese -->
        <div class="col-md-6 col-lg-4">
            <a href="<?= base_url('japanese') ?>" class="text-decoration-none">
                <div class="card-language border-0 shadow-lg p-4 hover-lift overflow-hidden position-relative" style="background: linear-gradient(135deg, #e63946 0%, #d62828 100%);">
                    <div class="position-absolute top-0 end-0 opacity-10" style="font-size: 8rem; margin-top: -2rem; margin-right: -2rem;">
                        🇯🇵
                    </div>
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="language-icon me-3" style="background: rgba(255,255,255,0.2);">
                                <span style="font-size: 2rem;">🗾</span>
                            </div>
                            <div>
                                <h4 class="fw-bold text-white mb-1">Bahasa Jepang</h4>
                                <small class="text-white-50">Japanese Language</small>
                            </div>
                        </div>
                        <p class="text-white-50 small mb-3">Raih kesempatan bekerja atau kuliah di Jepang. Program persiapan JLPT tersedia.</p>

                        <!-- Featured Program with Flip Effect -->
                        <div class="featured-program-container mb-3" data-language="japanese">
                            <div class="featured-program-flipper">
                                <div class="featured-program-front">
                                    <?php 
                                    $japaneseProgs = $programsByLanguage['japanese'] ?? [];
                                    if (!empty($japaneseProgs)): 
                                        $randomProg = $japaneseProgs[array_rand($japaneseProgs)];
                                        $finalPrice = $randomProg['tuition_fee'] * (1 - ($randomProg['discount'] ?? 0) / 100);
                                    ?>
                                        <div class="bg-white bg-opacity-10 rounded-3 p-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="flex-shrink-0">
                                                    <?php if (!empty($randomProg['thumbnail'])): ?>
                                                        <img src="<?= base_url('uploads/programs/thumbs/' . $randomProg['thumbnail']) ?>" 
                                                            alt="<?= esc($randomProg['title']) ?>" 
                                                            class="rounded" 
                                                            style="width: 40px; height: 30px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-white bg-opacity-20 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 30px;">
                                                            <i class="bi bi-journal-text text-white small"></i>
                                                        </div>
                                                    <?php endif ?>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <div class="text-white small fw-semibold text-truncate"><?= esc($randomProg['title']) ?></div>
                                                    <div class="text-white-50 small">Rp <?= number_format($finalPrice, 0, ',', '.') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="bg-white bg-opacity-10 rounded-3 p-2 text-center">
                                            <span class="text-white-50 small">Program belum tersedia</span>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-white text-danger px-3 py-2">JLPT - JFT</span>
                            <i class="bi bi-arrow-right-circle-fill text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Korean -->
        <div class="col-md-6 col-lg-4">
            <a href="<?= base_url('korean') ?>" class="text-decoration-none">
                <div class="card-language border-0 shadow-lg p-4 hover-lift overflow-hidden position-relative" style="background: linear-gradient(135deg, #f77f00 0%, #d62828 100%);">
                    <div class="position-absolute top-0 end-0 opacity-10" style="font-size: 8rem; margin-top: -2rem; margin-right: -2rem;">
                        🇰🇷
                    </div>
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="language-icon me-3" style="background: rgba(255,255,255,0.2);">
                                <span style="font-size: 2rem;">🎎</span>
                            </div>
                            <div>
                                <h4 class="fw-bold text-white mb-1">Bahasa Korea</h4>
                                <small class="text-white-50">Korean Language</small>
                            </div>
                        </div>
                        <p class="text-white-50 small mb-3">Ikuti tren K-Culture! Persiapan TOPIK untuk beasiswa dan karir di Korea Selatan.</p>
                        
                        <!-- Featured Program with Flip Effect -->
                        <div class="featured-program-container mb-3" data-language="korean">
                            <div class="featured-program-flipper">
                                <div class="featured-program-front">
                                    <?php 
                                    $koreanProgs = $programsByLanguage['korean'] ?? [];
                                    if (!empty($koreanProgs)): 
                                        $randomProg = $koreanProgs[array_rand($koreanProgs)];
                                        $finalPrice = $randomProg['tuition_fee'] * (1 - ($randomProg['discount'] ?? 0) / 100);
                                    ?>
                                        <div class="bg-white bg-opacity-10 rounded-3 p-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="flex-shrink-0">
                                                    <?php if (!empty($randomProg['thumbnail'])): ?>
                                                        <img src="<?= base_url('uploads/programs/thumbs/' . $randomProg['thumbnail']) ?>" 
                                                            alt="<?= esc($randomProg['title']) ?>" 
                                                            class="rounded" 
                                                            style="width: 40px; height: 30px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-white bg-opacity-20 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 30px;">
                                                            <i class="bi bi-journal-text text-white small"></i>
                                                        </div>
                                                    <?php endif ?>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <div class="text-white small fw-semibold text-truncate"><?= esc($randomProg['title']) ?></div>
                                                    <div class="text-white-50 small">Rp <?= number_format($finalPrice, 0, ',', '.') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="bg-white bg-opacity-10 rounded-3 p-2 text-center">
                                            <span class="text-white-50 small">Program belum tersedia</span>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-white text-danger px-3 py-2">EPS - TOPIK</span>
                            <i class="bi bi-arrow-right-circle-fill text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- German -->
        <div class="col-md-6 col-lg-4">
            <a href="<?= base_url('german') ?>" class="text-decoration-none">
                <div class="card-language border-0 shadow-lg p-4 hover-lift overflow-hidden position-relative" style="background: linear-gradient(135deg, #2a9d8f 0%, #264653 100%);">
                    <div class="position-absolute top-0 end-0 opacity-10" style="font-size: 8rem; margin-top: -2rem; margin-right: -2rem;">
                        🇩🇪
                    </div>
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="language-icon me-3" style="background: rgba(255,255,255,0.2);">
                                <span style="font-size: 2rem;">🏰</span>
                            </div>
                            <div>
                                <h4 class="fw-bold text-white mb-1">Bahasa Jerman</h4>
                                <small class="text-white-50">German Language</small>
                            </div>
                        </div>
                        <p class="text-white-50 small mb-3">Persiapan kuliah atau Ausbildung di Jerman. Sertifikasi Goethe Institut tersedia.</p>
                        
                        <!-- Featured Program with Flip Effect -->
                        <div class="featured-program-container mb-3" data-language="german">
                            <div class="featured-program-flipper">
                                <div class="featured-program-front">
                                    <?php 
                                    $germanProgs = $programsByLanguage['german'] ?? [];
                                    if (!empty($germanProgs)): 
                                        $randomProg = $germanProgs[array_rand($germanProgs)];
                                        $finalPrice = $randomProg['tuition_fee'] * (1 - ($randomProg['discount'] ?? 0) / 100);
                                    ?>
                                        <div class="bg-white bg-opacity-10 rounded-3 p-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="flex-shrink-0">
                                                    <?php if (!empty($randomProg['thumbnail'])): ?>
                                                        <img src="<?= base_url('uploads/programs/thumbs/' . $randomProg['thumbnail']) ?>" 
                                                            alt="<?= esc($randomProg['title']) ?>" 
                                                            class="rounded" 
                                                            style="width: 40px; height: 30px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-white bg-opacity-20 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 30px;">
                                                            <i class="bi bi-journal-text text-white small"></i>
                                                        </div>
                                                    <?php endif ?>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <div class="text-white small fw-semibold text-truncate"><?= esc($randomProg['title']) ?></div>
                                                    <div class="text-white-50 small">Rp <?= number_format($finalPrice, 0, ',', '.') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="bg-white bg-opacity-10 rounded-3 p-2 text-center">
                                            <span class="text-white-50 small">Program belum tersedia</span>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-white text-success px-3 py-2">Goethe</span>
                            <i class="bi bi-arrow-right-circle-fill text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- English -->
        <div class="col-md-6 col-lg-4">
            <a href="<?= base_url('english') ?>" class="text-decoration-none">
                <div class="card-language border-0 shadow-lg p-4 hover-lift overflow-hidden position-relative" style="background: linear-gradient(135deg, #457b9d 0%, #1d3557 100%);">
                    <div class="position-absolute top-0 end-0 opacity-10" style="font-size: 8rem; margin-top: -2rem; margin-right: -2rem;">
                        🇬🇧
                    </div>
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="language-icon me-3" style="background: rgba(255,255,255,0.2);">
                                <span style="font-size: 2rem;">📚</span>
                            </div>
                            <div>
                                <h4 class="fw-bold text-white mb-1">Bahasa Inggris</h4>
                                <small class="text-white-50">English Language</small>
                            </div>
                        </div>
                        <p class="text-white-50 small mb-3">Metode Kampung Inggris Pare yang legendaris. Persiapan TOEFL, IELTS, dan English for Business.</p>
                        
                        <!-- Featured Program with Flip Effect -->
                        <div class="featured-program-container mb-3" data-language="english">
                            <div class="featured-program-flipper">
                                <div class="featured-program-front">
                                    <?php 
                                    $englishProgs = $programsByLanguage['english'] ?? [];
                                    if (!empty($englishProgs)): 
                                        $randomProg = $englishProgs[array_rand($englishProgs)];
                                        $finalPrice = $randomProg['tuition_fee'] * (1 - ($randomProg['discount'] ?? 0) / 100);
                                    ?>
                                        <div class="bg-white bg-opacity-10 rounded-3 p-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="flex-shrink-0">
                                                    <?php if (!empty($randomProg['thumbnail'])): ?>
                                                        <img src="<?= base_url('uploads/programs/thumbs/' . $randomProg['thumbnail']) ?>" 
                                                            alt="<?= esc($randomProg['title']) ?>" 
                                                            class="rounded" 
                                                            style="width: 40px; height: 30px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-white bg-opacity-20 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 30px;">
                                                            <i class="bi bi-journal-text text-white small"></i>
                                                        </div>
                                                    <?php endif ?>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <div class="text-white small fw-semibold text-truncate"><?= esc($randomProg['title']) ?></div>
                                                    <div class="text-white-50 small">Rp <?= number_format($finalPrice, 0, ',', '.') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="bg-white bg-opacity-10 rounded-3 p-2 text-center">
                                            <span class="text-white-50 small">Program belum tersedia</span>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-white text-primary px-3 py-2">TOEFL/IELTS</span>
                            <i class="bi bi-arrow-right-circle-fill text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Why Choose Us Section -->
<div class="container py-5">
    <div class="row align-items-center g-5 py-5">
        <div class="col-lg-6">
            <h2 class="display-5 fw-bold mb-4" style="color: var(--dark-red);">Mengapa Memilih Kami?</h2>
            <p class="lead text-muted mb-4">Kami memberikan pengalaman belajar terbaik dengan metode yang telah teruji sejak 2013.</p>
            
            <div class="d-flex gap-4 mb-4">
                <div class="bg-light p-3 rounded-4">
                    <i class="bi bi-award-fill text-warning fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold">Pengajar Bersertifikat</h5>
                    <p class="text-muted small mb-0">Instruktur profesional dengan sertifikasi internasional dan pengalaman mengajar bertahun-tahun.</p>
                </div>
            </div>

            <div class="d-flex gap-4 mb-4">
                <div class="bg-light p-3 rounded-4">
                    <i class="bi bi-lightning-fill text-danger fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold">Metode Kampung Inggris Pare</h5>
                    <p class="text-muted small mb-0">Pembelajaran intensif dengan lingkungan yang mendukung praktik bahasa setiap hari.</p>
                </div>
            </div>

            <div class="d-flex gap-4 mb-4">
                <div class="bg-light p-3 rounded-4">
                    <i class="bi bi-shield-fill-check text-success fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold">Garansi Bisa</h5>
                    <p class="text-muted small mb-0">Kami menjamin kemampuan bahasa Anda meningkat dengan metode pembelajaran yang terbukti efektif.</p>
                </div>
            </div>
            
            <div class="d-flex gap-4 mb-4">
                <div class="bg-light p-3 rounded-4">
                    <i class="bi bi-people-fill text-info fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold">Komunitas Solid</h5>
                    <p class="text-muted small mb-0">Bergabung dengan ribuan alumni yang telah sukses berkarir di perusahaan multinasional.</p>
                </div>
            </div>
            
            <a href="<?= base_url('programs') ?>" class="btn btn-dark-red btn-lg mt-3 px-5">Lihat Semua Program</a>
        </div>
        <div class="col-lg-6">
            <div class="bg-light rounded-5 p-5 border border-white shadow-sm overflow-hidden position-relative">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-white p-4 rounded-4 shadow-sm text-center">
                            <div class="h2 fw-bold text-danger">1000+</div>
                            <div class="text-muted small">Alumni Sukses</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white p-4 rounded-4 shadow-sm text-center">
                            <div class="h2 fw-bold text-success">95%</div>
                            <div class="text-muted small">Tingkat Kelulusan</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-white p-4 rounded-4 shadow-sm">
                            <div class="fw-bold mb-3 d-flex justify-content-between">
                                <span>Kepuasan Siswa</span>
                                <span class="text-success">Excellent</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: 98%" role="progressbar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-gradient-danger text-white p-4 rounded-4 shadow-sm">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-trophy-fill fs-1"></i>
                                <div>
                                    <div class="fw-bold">Sertifikasi Internasional</div>
                                    <small class="opacity-75">HSK, JLPT, TOPIK, Goethe, TOEFL/IELTS</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Decorative Circle -->
                <div class="position-absolute top-100 start-100 translate-middle bg-danger opacity-10 rounded-circle" style="width: 200px; height: 200px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Partnership Section -->
<div class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3" style="color: var(--dark-red);">Partner Terpercaya Kami</h2>
            <p class="lead text-muted">Berbagai Perusahaan yang telah Merekrut Alumni Kami</p>
        </div>
        
        <!-- Logo Carousel -->
        <div class="mb-5">
            <div class="partner-carousel-container overflow-hidden py-4">
                <div class="partner-carousel-track d-flex gap-4" style="animation: scroll 20s linear infinite;">
                    <!-- Logo 1: CCEPC -->
                    <div class="partner-logo flex-shrink-0 d-flex align-items-center justify-content-center bg-white rounded shadow-sm p-3" style="width: 180px; height: 90px;">
                        <img src="<?= base_url('assets/images/partners-logo/logo-ccepc.webp') ?>" alt="CCEPC" class="img-fluid" style="max-height: 60px; max-width: 160px;">
                    </div>
                    <!-- Logo 2: Golden Tekstil Indonesia -->
                    <div class="partner-logo flex-shrink-0 d-flex align-items-center justify-content-center bg-white rounded shadow-sm p-3" style="width: 180px; height: 90px;">
                        <img src="<?= base_url('assets/images/partners-logo/logo-golden-tekstil-indonesia.webp') ?>" alt="Golden Tekstil Indonesia" class="img-fluid" style="max-height: 60px; max-width: 160px;">
                    </div>
                    <!-- Logo 3: Harita Nickel -->
                    <div class="partner-logo flex-shrink-0 d-flex align-items-center justify-content-center bg-white rounded shadow-sm p-3" style="width: 180px; height: 90px;">
                        <img src="<?= base_url('assets/images/partners-logo/logo-harita-nickel.webp') ?>" alt="Harita Nickel" class="img-fluid" style="max-height: 60px; max-width: 160px;">
                    </div>
                    <!-- Logo 4: IMIP -->
                    <div class="partner-logo flex-shrink-0 d-flex align-items-center justify-content-center bg-white rounded shadow-sm p-3" style="width: 180px; height: 90px;">
                        <img src="<?= base_url('assets/images/partners-logo/logo-imip.webp') ?>" alt="IMIP" class="img-fluid" style="max-height: 60px; max-width: 160px;">
                    </div>
                    <!-- Logo 5: IWIP -->
                    <div class="partner-logo flex-shrink-0 d-flex align-items-center justify-content-center bg-white rounded shadow-sm p-3" style="width: 180px; height: 90px;">
                        <img src="<?= base_url('assets/images/partners-logo/logo-iwip.webp') ?>" alt="IWIP" class="img-fluid" style="max-height: 60px; max-width: 160px;">
                    </div>
                    <!-- Logo 6: OSS -->
                    <div class="partner-logo flex-shrink-0 d-flex align-items-center justify-content-center bg-white rounded shadow-sm p-3" style="width: 180px; height: 90px;">
                        <img src="<?= base_url('assets/images/partners-logo/logo-oss.webp') ?>" alt="OSS" class="img-fluid" style="max-height: 60px; max-width: 160px;">
                    </div>
                    <!-- Duplicate for seamless loop -->
                    <div class="partner-logo flex-shrink-0 d-flex align-items-center justify-content-center bg-white rounded shadow-sm p-3" style="width: 180px; height: 90px;">
                        <img src="<?= base_url('assets/images/partners-logo/logo-ccepc.webp') ?>" alt="CCEPC" class="img-fluid" style="max-height: 60px; max-width: 160px;">
                    </div>
                    <div class="partner-logo flex-shrink-0 d-flex align-items-center justify-content-center bg-white rounded shadow-sm p-3" style="width: 180px; height: 90px;">
                        <img src="<?= base_url('assets/images/partners-logo/logo-golden-tekstil-indonesia.webp') ?>" alt="Golden Tekstil Indonesia" class="img-fluid" style="max-height: 60px; max-width: 160px;">
                    </div>
                    <div class="partner-logo flex-shrink-0 d-flex align-items-center justify-content-center bg-white rounded shadow-sm p-3" style="width: 180px; height: 90px;">
                        <img src="<?= base_url('assets/images/partners-logo/logo-harita-nickel.webp') ?>" alt="Harita Nickel" class="img-fluid" style="max-height: 60px; max-width: 160px;">
                    </div>
                    <div class="partner-logo flex-shrink-0 d-flex align-items-center justify-content-center bg-white rounded shadow-sm p-3" style="width: 180px; height: 90px;">
                        <img src="<?= base_url('assets/images/partners-logo/logo-imip.webp') ?>" alt="IMIP" class="img-fluid" style="max-height: 60px; max-width: 160px;">
                    </div>
                    <div class="partner-logo flex-shrink-0 d-flex align-items-center justify-content-center bg-white rounded shadow-sm p-3" style="width: 180px; height: 90px;">
                        <img src="<?= base_url('assets/images/partners-logo/logo-iwip.webp') ?>" alt="IWIP" class="img-fluid" style="max-height: 60px; max-width: 160px;">
                    </div>
                    <div class="partner-logo flex-shrink-0 d-flex align-items-center justify-content-center bg-white rounded shadow-sm p-3" style="width: 180px; height: 90px;">
                        <img src="<?= base_url('assets/images/partners-logo/logo-oss.webp') ?>" alt="OSS" class="img-fluid" style="max-height: 60px; max-width: 160px;">
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .partner-carousel-container {
            mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        }
        </style>
        
        <div class="row g-4 justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm p-4 h-100 hover-lift">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <span style="font-size: 2.5rem;">🇯🇵</span>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-2">Tamashii Japanese Center</h4>
                        <p class="text-muted small mb-3">Partner resmi untuk program Bahasa Jepang. Menyediakan kurikulum standar Jepang dengan instruktur native speaker.</p>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <span class="badge bg-danger">Native Speaker</span>
                            <span class="badge bg-danger">JLPT Certified</span>
                            <span class="badge bg-danger">Cultural Program</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-5">
                <div class="card border-0 shadow-sm p-4 h-100 hover-lift">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <span style="font-size: 2.5rem;">🇨🇳</span>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-2">Xihuan Mandarin Indonesia</h4>
                        <p class="text-muted small mb-3">Partner resmi untuk program Bahasa Mandarin. Metode pembelajaran modern dengan fokus pada komunikasi praktis.</p>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <span class="badge bg-danger">HSK Preparation</span>
                            <span class="badge bg-danger">Business Chinese</span>
                            <span class="badge bg-danger">Native Teacher</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-5">
            <p class="text-muted mb-3">Alumni kami terbukti berdaya saing tinggi dengan peluang karir cemerlang di perusahaan asing dalam dan luar negeri. Terimah kasih atas kepercayaanya merekrut SDM berkualitas lulusan terbaik S.O.S Course & Training</p>
        </div>
    </div>
</div>

<!-- About Kampung Inggris Section -->
<div class="container py-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-6 order-lg-2">
            <h2 class="display-6 fw-bold mb-4" style="color: var(--dark-red);">Sekilas Kampung Inggris Pare</h2>
            <p class="text-muted mb-3">
                Kampung Inggris di Pare, Kediri telah menjadi pusat pembelajaran bahasa Inggris terbesar di Indonesia sejak tahun 1977. Dengan metode pembelajaran yang intensif, ketat, dan lingkungan yang sangat mendukung, tempat ini telah melahirkan ribuan lulusan yang mahir berbahasa Inggris.
            </p>
            <p class="text-muted mb-3">
                Kini, konsep sukses ini berkembang! <strong>SOS Course and Training</strong> menghadirkan pengalaman belajar ala Kampung Inggris tidak hanya untuk bahasa Inggris, tetapi juga untuk lima bahasa asing populer: <strong>Mandarin, Jepang, Korea, Jerman, dan Inggris</strong>.
            </p>
            <p class="text-muted mb-4">
                Dengan metode yang telah teruji, kami menciptakan lingkungan belajar yang kondusif, intensif, dan dikelilingi oleh komunitas yang solid di mana semua orang memiliki tujuan yang sama: menguasai bahasa asing dengan cepat dan tepat.
            </p>
            
            <div class="bg-light p-4 rounded-4 mb-4">
                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-geo-alt-fill text-danger fs-3"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Lokasi Strategis</h6>
                        <p class="text-muted small mb-0">Desa Tulungrejo dan Desa Pelem, Kecamatan Pare, Kabupaten Kediri, Provinsi Jawa Timur</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-light p-4 rounded-4">
                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-calendar-check-fill text-success fs-3"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Berdiri Sejak 2013</h6>
                        <p class="text-muted small mb-0">SOS Course & Training telah melayani ribuan siswa dengan komitmen memberikan pendidikan bahasa berkualitas tinggi</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 order-lg-1">
            <div class="position-relative">
                <div class="bg-gradient-danger rounded-5 p-5 text-white shadow-lg">
                    <div class="mb-4">
                        <i class="bi bi-mortarboard-fill display-3 opacity-25"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Legalitas Resmi</h3>
                    <div class="mb-3">
                        <small class="opacity-75 d-block mb-1">SK Dinas Pendidikan</small>
                        <div class="fw-bold">Nomor 421.9/1885/418.20/2023</div>
                    </div>
                    <div>
                        <small class="opacity-75 d-block mb-1">SK KEMENKUMHAM</small>
                        <div class="fw-bold">Nomor AHU-0015725.AH.01.07.TAHUN 2018</div>
                    </div>
                </div>
                
                <!-- Decorative elements -->
                <div class="position-absolute top-0 start-0 translate-middle bg-warning rounded-circle" style="width: 60px; height: 60px; opacity: 0.3;"></div>
                <div class="position-absolute bottom-0 end-0 translate-middle bg-info rounded-circle" style="width: 80px; height: 80px; opacity: 0.2;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="py-5 bg-gradient-danger text-white">
    <div class="container text-center py-5">
        <h2 class="display-5 fw-bold mb-3">Siap Menguasai Bahasa Asing?</h2>
        <p class="lead mb-4 opacity-75">Investasi Terbaik adalah Investasi dalam Ilmu Pengetahuan dan Skill<br>Mulai perjalanan belajar bahasa Asing bersama SOS Course and Training!</p>
        
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="https://wa.me/6285810310950?text=Hai,%20saya%20mau%20konsultasi%20mengenai%20program%20kursus%20di%20SOS%20Course%20and%20Training" 
               target="_blank"
               class="btn btn-warning btn-lg px-5 shadow fw-bold">
                <i class="bi bi-whatsapp me-2"></i>Konsultasi Gratis via WhatsApp
            </a>
            <a href="<?= base_url('programs') ?>" class="btn btn-outline-light btn-lg px-5">
                <i class="bi bi-grid-3x3-gap me-2"></i>Jelajahi Semua Program
            </a>
        </div>

    </div>
</div>

<style>
    .hero-section {
        background: linear-gradient(135deg, var(--dark-red) 0%, #500000 100%);
        color: white;
    }
    
    .card-language {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        min-height: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .card-language:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2) !important;
    }
    
    .card-language .card-body {
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    
    .card-language .card-body p {
        flex: 1 0 auto;
    }
    
    .card-language .card-body .featured-program-container {
        flex: 0 0 auto;
    }
    
    .card-language .card-body > .d-flex:last-child {
        margin-top: auto;
    }
    
    .language-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .partnership-badge {
        backdrop-filter: blur(10px);
    }
    
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    
    /* Featured Program Flip Animation */
    .featured-program-container {
        perspective: 1000px;
        min-height: 46px;
    }
    
    .featured-program-flipper {
        position: relative;
        width: 100%;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        transform-style: preserve-3d;
    }
    
    .featured-program-flipper.flipping {
        transform: rotateX(180deg);
    }
    
    .featured-program-front,
    .featured-program-back {
        width: 100%;
        backface-visibility: hidden;
    }
    
    .featured-program-back {
        position: absolute;
        top: 0;
        left: 0;
        transform: rotateX(180deg);
    }
    
    @keyframes flipIn {
        0% {
            transform: rotateX(-90deg);
            opacity: 0;
        }
        100% {
            transform: rotateX(0);
            opacity: 1;
        }
    }
    
    .featured-program-flipper.animate-in {
        animation: flipIn 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    
    .bg-gradient-danger {
        background: linear-gradient(135deg, var(--dark-red) 0%, #b91226 100%);
    }
    
    /* Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes pulse-soft {
        0% { transform: scale(1); opacity: 0.2; }
        50% { transform: scale(1.05); opacity: 0.3; }
        100% { transform: scale(1); opacity: 0.2; }
    }
    
    .animate-slide-up { animation: fadeInUp 0.8s ease-out forwards; }
    .animate-slide-up-delay-1 { animation: fadeInUp 0.8s ease-out 0.2s forwards; opacity: 0; }
    .animate-slide-up-delay-2 { animation: fadeInUp 0.8s ease-out 0.4s forwards; opacity: 0; }
    .animate-fade-in { animation: fadeIn 1s ease-out forwards; }
    .animate-fade-in-delay-3 { animation: fadeIn 1s ease-out 0.6s forwards; opacity: 0; }
    
    .animate-pulse {
        animation: pulse-soft 3s infinite ease-in-out;
    }
    
    .mt-n5 {
        margin-top: -3rem !important;
    }
    
    .rounded-5 { border-radius: 2rem !important; }
    .rounded-4 { border-radius: 1.5rem !important; }
    
    /* Language Switch Animation */
    .language-switch {
        display: inline-block;
        min-width: 150px;
        text-align: left;
        transition: opacity 0.3s ease-in-out;
    }
    
    .language-switch.fade-out {
        opacity: 0;
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* ===== 3D ANIMATION SYSTEM - COMPLETE FROM KURSUSBAHASA.ORG ===== */
    
    /* 1. Animation Variable Definition */
    @property --orbit-angle {
        syntax: "<angle>";
        inherits: false;
        initial-value: 0deg;
    }
    
    /* 2. Hero Image Container */
    .hero-image-container {
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
    
    /* 3. Main System Container */
    .animation-3d-system {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        perspective: 1200px;
        transform-style: preserve-3d;
    }
    
    .animation-3d-system * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    /* 4. Center Logo with Floating Animation */
    .animation-logo-container {
        z-index: 10;
        position: relative;
        width: 200px;
        height: 200px;
    }
    
    .animation-logo {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 0 10px rgba(255, 215, 0, 0.5));
        transition: all 0.3s ease;
        animation: logoFloat 6s ease-in-out infinite;
        transform-origin: center;
    }
    
    @keyframes logoFloat {
        0% {
            transform: translateY(0px) scale(1) rotate(0deg);
        }
        25% {
            transform: translateY(-10px) scale(1.02) rotate(0.5deg);
        }
        50% {
            transform: translateY(-20px) scale(1.05) rotate(0deg);
        }
        75% {
            transform: translateY(-10px) scale(1.02) rotate(-0.5deg);
        }
        100% {
            transform: translateY(0px) scale(1) rotate(0deg);
        }
    }
    
    /* Logo Glow Effect */
    .animation-logo::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 120%;
        height: 120%;
        background: radial-gradient(circle,
                rgba(255, 215, 0, 0.2) 0%,
                rgba(255, 215, 0, 0) 70%);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        z-index: -1;
        animation: logoGlow 4s ease-in-out infinite alternate;
    }
    
    @keyframes logoGlow {
        0% {
            opacity: 0.3;
            transform: translate(-50%, -50%) scale(0.9);
        }
        100% {
            opacity: 0.7;
            transform: translate(-50%, -50%) scale(1.1);
        }
    }
    
    /* 5. Orbit Animation */
    @keyframes orbit {
        from {
            --orbit-angle: 0deg;
        }
        to {
            --orbit-angle: 360deg;
        }
    }
    
    /* 6. Individual Flag Orbit Containers */
    .animation-orbit {
        /* PERMANENT SETTINGS */
        --radius: 100px;
        --tilt: -0.3; /* Creates the oblique angle */
        --orbit-speed: 5s;
    
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        --current-angle: calc(var(--orbit-angle) + var(--angle-offset, 0deg));
    
        /* Calculated X and Y for Oblique/Tilted Path */
        --pos-x: calc(cos(var(--current-angle)) * var(--radius));
        --pos-y: calc(sin(var(--current-angle)) * var(--radius) * 0.4); /* Ellipse depth */
    
        /* Oblique adjustment: Shift Y based on X position */
        translate: var(--pos-x) calc(var(--pos-y) + (var(--pos-x) * var(--tilt)));
    
        /* Depth simulation: front vs back */
        z-index: calc(10 + (sin(var(--current-angle)) * 10));
    
        animation: orbit var(--orbit-speed) linear infinite;
        will-change: translate, z-index;
    }
    
    /* 7. Flag Styling */
    .animation-flag {
        position: absolute;
        width: 50px;
        height: 35px;
        object-fit: cover;
        border-radius: 4px;
    
        /* Scale simulates distance */
        transform: translate(-50%, -50%) scale(calc(0.85 + (sin(var(--current-angle)) * 0.35)));
    
        opacity: calc(0.75 + (sin(var(--current-angle)) * 0.25));
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.7);
        border: 2px solid rgba(255, 215, 0, 0.3);
        transition: all 0.3s ease;
        animation: flagFloat 3s ease-in-out infinite;
        animation-delay: calc(var(--angle-offset, 0deg) * 0.05s);
    }
    
    .animation-flag:hover {
        transform: translate(-50%, -50%) scale(1.1);
        box-shadow: 0 8px 25px rgba(255, 215, 0, 0.5);
        border-color: #ffd700;
        z-index: 20;
    }
    
    @keyframes flagFloat {
        0%, 100% {
            transform: translate(-50%, -50%) scale(calc(0.85 + (sin(var(--current-angle)) * 0.35)));
        }
        50% {
            transform: translate(-50%, calc(-50% - 3px)) scale(calc(0.85 + (sin(var(--current-angle)) * 0.35)));
        }
    }
    
    /* Responsive Adjustments for 3D Animation */
    @media (max-width: 992px) {
        .animation-3d-system {
            perspective: 800px;
        }
    
        .animation-logo-container {
            width: 180px;
            height: 180px;
        }
    
        .animation-orbit {
            --radius: 80px;
        }
    
        .animation-flag {
            width: 45px;
            height: 31px;
        }
    }
    
    @media (max-width: 768px) {
        .animation-3d-system {
            perspective: 600px;
        }
    
        .animation-logo-container {
            width: 150px;
            height: 150px;
        }
    
        .animation-orbit {
            --radius: 70px;
            --orbit-speed: 4s;
        }
    
        .animation-flag {
            width: 40px;
            height: 28px;
        }
    
        @keyframes logoFloat {
            0% {
                transform: translateY(0px) scale(1);
            }
            50% {
                transform: translateY(-15px) scale(1.03);
            }
            100% {
                transform: translateY(0px) scale(1);
            }
        }
    
        .hero-image-container {
            padding: 10px;
        }
    }
    
    @media (max-width: 576px) {
        .animation-3d-system {
            perspective: 500px;
        }
    
        .animation-logo-container {
            width: 120px;
            height: 120px;
        }
    
        .animation-orbit {
            --radius: 60px;
            --orbit-speed: 3.5s;
        }
    
        .animation-flag {
            width: 35px;
            height: 24px;
        }
    }
</style>

<script>
// Language switching animation
document.addEventListener('DOMContentLoaded', function() {
    const languages = ['Mandarin', 'Jepang', 'Korea', 'Jerman', 'Inggris'];
    const languageElement = document.getElementById('languageSwitch');
    let currentIndex = 0;
    
    function switchLanguage() {
        // Fade out
        languageElement.classList.add('fade-out');
        
        setTimeout(() => {
            // Change text
            currentIndex = (currentIndex + 1) % languages.length;
            languageElement.textContent = languages[currentIndex];
            
            // Fade in
            languageElement.classList.remove('fade-out');
        }, 300);
    }
    
    // Switch language every 2 seconds
    setInterval(switchLanguage, 2000);
});

// Featured Program Flip Animation
document.addEventListener('DOMContentLoaded', function() {
    // Program data passed from PHP
    const programsData = <?= json_encode($programsByLanguage ?? []) ?>;
    
    // Initialize flip animation for each language container
    const containers = document.querySelectorAll('.featured-program-container');
    
    containers.forEach(container => {
        const language = container.dataset.language;
        const programs = programsData[language] || [];
        
        if (programs.length <= 1) return; // No need to rotate if 0 or 1 program
        
        let currentIndex = 0;
        const flipper = container.querySelector('.featured-program-flipper');
        const frontCard = container.querySelector('.featured-program-front');
        
        setInterval(() => {
            // Get next random program (different from current)
            let nextIndex;
            do {
                nextIndex = Math.floor(Math.random() * programs.length);
            } while (nextIndex === currentIndex && programs.length > 1);
            currentIndex = nextIndex;
            
            const prog = programs[currentIndex];
            const finalPrice = prog.tuition_fee * (1 - (prog.discount || 0) / 100);
            
            // Build thumbnail HTML
            let thumbHtml = '';
            if (prog.thumbnail) {
                thumbHtml = `<img src="<?= base_url('uploads/programs/thumbs/') ?>${prog.thumbnail}" 
                    alt="${prog.title}" class="rounded" style="width: 40px; height: 30px; object-fit: cover;">`;
            } else {
                thumbHtml = `<div class="bg-white bg-opacity-20 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 30px;">
                    <i class="bi bi-journal-text text-white small"></i>
                </div>`;
            }
            
            // Build new content
            const newContent = `
                <div class="bg-white bg-opacity-10 rounded-3 p-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="flex-shrink-0">${thumbHtml}</div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="text-white small fw-semibold text-truncate">${prog.title}</div>
                            <div class="text-white-50 small">Rp ${finalPrice.toLocaleString('id-ID')}</div>
                        </div>
                    </div>
                </div>`;
            
            // Animate flip
            flipper.classList.add('animate-in');
            flipper.style.transform = 'rotateX(90deg)';
            
            setTimeout(() => {
                frontCard.innerHTML = newContent;
                flipper.style.transform = 'rotateX(0deg)';
            }, 300);
            
            setTimeout(() => {
                flipper.classList.remove('animate-in');
            }, 600);
            
        }, 3000);
    });
});
</script>

<?= $this->endSection() ?>
