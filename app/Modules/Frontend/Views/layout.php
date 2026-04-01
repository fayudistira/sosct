<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- === Pageview Tracking === -->
    <?php
    use App\Models\PageviewModel;
    
    // Get visitor IP address
    function getVisitorIP() {
        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        }
        // Handle multiple IPs in X-Forwarded-For
        if (strpos($ip, ',') !== false) {
            $ip = trim(explode(',', $ip)[0]);
        }
        return $ip;
    }
    
    // Get visitor location from IP using free API
    function getVisitorLocation($ip) {
        $country = null;
        $city = null;
        
        // Skip localhost/private IPs
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost']) || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
            return ['country' => 'Local', 'city' => 'Local'];
        }
        
        try {
            // Using ip-api.com free API (100 requests/minute limit)
            $response = @file_get_contents('http://ip-api.com/json/' . $ip . '?fields=status,country,city');
            if ($response) {
                $data = json_decode($response, true);
                if ($data && $data['status'] === 'success') {
                    $country = $data['country'] ?? null;
                    $city = $data['city'] ?? null;
                }
            }
        } catch (\Exception $e) {
            // Silently fail if API is unavailable
        }
        
        return ['country' => $country, 'city' => $city];
    }
    
    // Get IP and location
    $visitorIp = getVisitorIP();
    $location = getVisitorLocation($visitorIp);
    
    // Record page view for this page
    $pageviewModel = new PageviewModel();
    $currentUrl = current_url();
    $pageviewData = $pageviewModel->recordPageView($currentUrl, $title ?? 'Unknown', $visitorIp, $location['country'], $location['city']);
    $pageviewCount = $pageviewData['views'];
    $uniqueVisitorCount = $pageviewData['unique'];
    
    // Check if user is admin
    $isAdmin = false;
    if (auth()->loggedIn()) {
        $user = auth()->user();
        if ($user) {
            $isAdmin = $user->inGroup('superadmin', 'admin');
        }
    }
    ?>
    
    <!-- === SEO Meta Tags === -->
    <?php
    // Default values
    $title = $title ?? 'SOS Course and Training - Kursus Bahasa Asing Terbaik di Kampung Inggris Pare';
    $description = $metaDescription ?? $description ?? 'SOS Course and Training menyediakan kursus bahasa asing berkualitas tinggi termasuk Mandarin, Inggris, Jepang, Korea, dan Jerman. Program bersertifikat dengan pengajar native speaker.';
    $keywords = $metaKeywords ?? $keywords ?? 'kursus bahasa asing, kampung inggris pare, les bahasa, mandarin, inggris, jepang, korea, jerman, HSK, TOEFL, kursus bersertifikat';
    $author = 'SOS Course and Training';
    $canonical = base_url(current_url());
    
    $og_title = $ogTitle ?? $title;
    $og_description = $ogDescription ?? $description;
    $og_image = $ogImage ?? 'https://kursusbahasa.org/assets/img/logo-sos.webp';
    $og_url = base_url();
    $og_type = 'website';
    $og_locale = 'id_ID';
    $og_site_name = 'SOS Course and Training';
    
    $twitter_card = 'summary_large_image';
    $twitter_title = $twitterTitle ?? $title;
    $twitter_description = $twitterDescription ?? $description;
    $twitter_image = $twitterImage ?? $og_image;
    ?>
    
    <title><?= esc($title) ?></title>
    <meta name="description" content="<?= esc($description) ?>">
    <meta name="keywords" content="<?= esc($keywords) ?>">
    <meta name="author" content="<?= esc($author) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= esc($canonical) ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?= esc($og_type) ?>">
    <meta property="og:url" content="<?= esc($og_url) ?>">
    <meta property="og:title" content="<?= esc($og_title) ?>">
    <meta property="og:description" content="<?= esc($og_description) ?>">
    <?php if (!empty($og_image)): ?>
    <meta property="og:image" content="<?= esc($og_image) ?>">
    <?php endif; ?>
    <meta property="og:locale" content="<?= esc($og_locale) ?>">
    <meta property="og:site_name" content="<?= esc($og_site_name) ?>">

    <!-- Twitter -->
    <meta name="twitter:card" content="<?= esc($twitter_card) ?>">
    <meta name="twitter:title" content="<?= esc($twitter_title) ?>">
    <meta name="twitter:description" content="<?= esc($twitter_description) ?>">
    <?php if (!empty($twitter_image)): ?>
    <meta name="twitter:image" content="<?= esc($twitter_image) ?>">
    <?php endif; ?>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/images/sos-logo.png') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/images/sos-logo.png') ?>">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --dark-red: #8B0000;
            --medium-red: #B22222;
            --light-red: #F5E8E8;
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        }
        
        /* Navbar */
        .navbar-custom {
            background: linear-gradient(90deg, var(--dark-red) 0%, #6B0000 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        .navbar-custom .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }
        
        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.2s;
        }
        
        .navbar-custom .nav-link:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        
        /* Dropdown */
        .dropdown-menu {
            background-color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            margin-top: 0.5rem;
        }
        
        .dropdown-item {
            color: #333 !important;
            padding: 0.5rem 1.5rem;
            transition: all 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: var(--light-red);
            color: var(--dark-red) !important;
        }
        
        .dropdown-toggle::after {
            margin-left: 0.5rem;
        }
        
        /* Login Button */
        .btn-login {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            border: 2px solid rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }
        
        .btn-login i {
            color: #ffffff;
        }
        
        .btn-login:hover,
        .btn-login:focus {
            background-color: #ffffff !important;
            color: #8B0000 !important;
            border-color: #ffffff !important;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3);
        }
        
        .btn-login:hover i,
        .btn-login:focus i {
            color: #8B0000 !important;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--dark-red) 0%, #6B0000 100%);
            color: white;
            padding: 4rem 0;
        }
        
        .img-landscape {
            width: 100%;
            aspect-ratio: 16 / 9;
            object-fit: cover;
        }
        
        /* Cards */
        .card-custom {
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            height: 100%;
        }
        
        .card-custom:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px);
        }
        
        .card-custom .card-header {
            background: linear-gradient(90deg, var(--dark-red) 0%, var(--medium-red) 100%);
            color: white;
            border-bottom: none;
            font-weight: 600;
        }
        
        /* Buttons */
        .btn-dark-red {
            background-color: var(--dark-red);
            color: white;
            border: 1px solid var(--dark-red);
            font-weight: 500;
        }
        
        .btn-dark-red:hover {
            background-color: #7a0000;
            border-color: #7a0000;
            color: white;
        }
        
        .btn-outline-dark-red {
            background-color: white;
            color: var(--dark-red);
            border: 1px solid var(--dark-red);
            font-weight: 500;
        }
        
        .btn-outline-dark-red:hover {
            background-color: var(--dark-red);
            color: white;
        }
        
        /* Footer */
        .footer-custom {
            background: linear-gradient(90deg, #1a1a1a 0%, #2d2d2d 100%);
            color: rgba(255, 255, 255, 0.8);
            padding: 2rem 0;
            margin-top: 4rem;
        }
        
        /* Form Styling */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--dark-red);
            box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.25);
        }
        
        /* Feature Icons */
        .feature-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--dark-red) 0%, var(--medium-red) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        /* Admission Popup Toast */
        .admission-popup {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 10000;
            max-width: 320px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            border-left: 4px solid var(--dark-red);
            padding: 16px;
            display: none;
            animation: slideInUp 0.4s ease-out;
        }

        .admission-popup.show {
            display: block;
        }

        .admission-popup.hide {
            animation: slideOutDown 0.3s ease-in forwards;
        }

        @keyframes slideInUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideOutDown {
            from {
                transform: translateY(0);
                opacity: 1;
            }
            to {
                transform: translateY(100%);
                opacity: 0;
            }
        }

        .admission-popup-header {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .admission-popup-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--dark-red) 0%, var(--medium-red) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 10px;
            font-size: 1rem;
        }

        .admission-popup-title {
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }

        .admission-popup-body {
            font-size: 0.85rem;
            color: #666;
        }

        .admission-popup-body strong {
            color: var(--dark-red);
        }

        .admission-popup-time {
            font-size: 0.75rem;
            color: #999;
            margin-top: 6px;
        }

        .admission-popup-close {
            position: absolute;
            top: 8px;
            right: 8px;
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 4px;
            font-size: 1rem;
            line-height: 1;
        }

        .admission-popup-close:hover {
            color: #333;
        }

        /* Program Popup - Bottom Right */
        @media (min-width: 992px) {
            .program-popup {
                position: fixed;
                bottom: 20px;
                right: 20px;
                left: auto;
                z-index: 10000;
                max-width: 350px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
                border: 1px solid #e0e0e0;
                padding: 0;
                display: none;
                animation: slideInUp 0.4s ease-out;
                overflow: hidden;
            }

            .program-popup.show {
                display: block;
            }

            .program-popup.hide {
                animation: slideOutRight 0.3s ease-in forwards;
            }

            @keyframes slideInUp {
                from {
                    transform: translateY(100%);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }

            .program-popup-header {
                background: linear-gradient(135deg, var(--dark-red) 0%, var(--medium-red) 100%);
                color: white;
                padding: 12px 16px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .program-popup-title {
                font-weight: 600;
                font-size: 0.95rem;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .program-popup-close {
                background: none;
                border: none;
                color: white;
                cursor: pointer;
                padding: 4px;
                font-size: 1.2rem;
                line-height: 1;
                opacity: 0.8;
            }

            .program-popup-close:hover {
                opacity: 1;
            }

            .program-popup-image {
                width: 100%;
                height: 120px;
                object-fit: cover;
            }

            .program-popup-body {
                padding: 14px;
            }

            .program-popup-name {
                font-weight: 600;
                color: #333;
                font-size: 0.95rem;
                margin-bottom: 6px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .program-popup-meta {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 10px;
                font-size: 0.8rem;
            }

            .program-popup-language {
                background: #f0f0f0;
                padding: 2px 8px;
                border-radius: 4px;
                color: #555;
            }

            .program-popup-price {
                font-size: 0.9rem;
            }

            .program-popup-original {
                text-decoration: line-through;
                color: #999;
                font-size: 0.8rem;
            }

            .program-popup-discount {
                background: var(--dark-red);
                color: white;
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 0.7rem;
                margin-left: 6px;
            }

            .program-popup-final {
                color: var(--dark-red);
                font-weight: 700;
                font-size: 1.1rem;
            }

            .program-popup-cta {
                display: block;
                width: 100%;
                padding: 8px 12px;
                background: var(--dark-red);
                color: white;
                text-align: center;
                border-radius: 6px;
                text-decoration: none;
                font-size: 0.85rem;
                font-weight: 500;
                margin-top: 10px;
                transition: background 0.2s;
            }

            .program-popup-cta:hover {
                background: var(--medium-red);
                color: white;
            }
        }

        /* Hide on mobile */
        @media (max-width: 991.98px) {
            .program-popup {
                display: none !important;
            }
        }
    </style>
    
    <?= $this->renderSection('extra_head') ?>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/') ?>">
                <img src="<?= base_url('assets/images/sos-logo.png') ?>" alt="SOS Course" height="36" class="me-2" style="filter: invert(1);">
                <span class="d-none d-lg-inline" style="font-size: 1.1rem; font-weight: 600; white-space: nowrap; color: white;">SOS Course & Training</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border-color: rgba(255,255,255,0.5);">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/') ?>">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="bahasaDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Bahasa
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="bahasaDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('english') ?>">English</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('mandarin') ?>">Mandarin</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('japanese') ?>">Japanese</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('korean') ?>">Korean</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('german') ?>">German</a></li>
                        </ul>
                    </li>
                    <?php if (auth()->loggedIn() && auth()->user()->inGroup('superadmin', 'admin')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="testDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Test
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="testDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('test/hsk') ?>">
                                <i class="bi bi-translate me-2"></i>HSK Simulation
                            </a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('about') ?>">Tentang SOS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('karir') ?>">Karir</a>
                    </li>
                    <li class="nav-item ms-2">
                        <?php if (auth()->loggedIn()): ?>
                            <a class="nav-link btn-login" href="<?= base_url('dashboard') ?>">
                                <i class="bi bi-speedometer2 me-1"></i>MyDashboard
                            </a>
                        <?php else: ?>
                            <a class="nav-link btn-login" href="<?= base_url('login') ?>">
                                <i class="bi bi-person me-1"></i>Login
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Pageview Counter (Admin Only) - Footer -->
    <?php if ($isAdmin): ?>
    <div class="bg-light py-2 border-bottom">
        <div class="container d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-eye-fill text-primary me-2"></i>
                <strong>Views:</strong> 
                <span class="badge bg-primary"><?= number_format($pageviewCount) ?></span>
                <span class="text-muted small mx-2">|</span>
                <i class="bi bi-people-fill text-success me-2"></i>
                <strong>Unique:</strong>
                <span class="badge bg-success"><?= number_format($uniqueVisitorCount) ?></span>
                <span class="text-muted small mx-2">|</span>
                <span class="text-muted small">(<?= esc($title ?? 'Current Page') ?>)</span>
                <span class="text-muted small mx-2">|</span>
                <i class="bi bi-geo-alt text-danger me-1"></i>
                <span class="text-muted small"><?= esc($location['city'] ?? 'N/A') ?>, <?= esc($location['country'] ?? 'N/A') ?></span>
                <span class="text-muted small mx-2">|</span>
                <i class="bi bi-router text-secondary me-1"></i>
                <span class="text-muted small"><?= esc($visitorIp) ?></span>
            </div>
            <small class="text-muted">Admin view</small>
        </div>
    </div>
    <?php endif; ?>

    <!-- Footer -->
         <!-- Footer -->
    <footer class="footer bg-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row g-4">
                <!-- Company Info -->
                <div class="col-lg-4 col-md-6">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">
                            <img src="<?= base_url('assets/images/sos-logo.png') ?>" alt="SOS Course & Training" height="40" class="me-2">
                            SOS Course & Training
                        </h5>
                        <ul class="list-unstyled mb-0">
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-geo-alt-fill text-danger me-2 mt-1"></i>
                            <span class="text-white-50 small">
                                Perum Kirana CLuster Blok A No 19 Jl. Camelia, Tulungrejo, Pare- Kab.Kediri
                            </span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-whatsapp text-danger me-2"></i>
                            <a href="https://wa.me/6285810310950" class="text-white-50 text-decoration-none small" target="_blank">+62 858-1031-0950(Inggris-Jerman-Korea)</a>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-whatsapp text-danger me-2"></i>
                            <a href="https://wa.me/6282240781299" class="text-white-50 text-decoration-none small" target="_blank">+62 822-4078-1299(Mandarin)</a>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-whatsapp text-danger me-2"></i>
                            <a href="https://wa.me/6285607454939" class="text-white-50 text-decoration-none small" target="_blank">+62 856-0745-4939(Jepang)</a>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="bi bi-clock text-danger me-2"></i>
                            <span class="text-white-50 small">Senin - Jumat: 08:00 - 16:00 WIB</span>
                        </li>
                    </ul>
                        
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold text-uppercase mb-3">Quick Links</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="<?= base_url() ?>" class="text-white-50 text-decoration-none small">Beranda</a></li>
                        <li class="mb-2"><a href="<?= base_url('about') ?>" class="text-white-50 text-decoration-none small">Tentang Kami</a></li>
                        <li class="mb-2"><a href="<?= base_url('programs') ?>" class="text-white-50 text-decoration-none small">Program</a></li>
                        <li class="mb-2"><a href="<?= base_url('contact') ?>" class="text-white-50 text-decoration-none small">Kontak</a></li>
                        <li class="mb-2"><a href="<?= base_url('apply') ?>" class="text-white-50 text-decoration-none small">Pendaftaran</a></li>
                    </ul>
                </div>
                
                <!-- Programs -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold text-uppercase mb-3">Program Bahasa</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="<?= base_url('mandarin') ?>" class="text-white-50 text-decoration-none small">Kursus Bahasa Mandarin</a></li>
                        <li class="mb-2"><a href="<?= base_url('japanese') ?>" class="text-white-50 text-decoration-none small">Kursus Bahasa Jepang</a></li>
                        <li class="mb-2"><a href="<?= base_url('korean') ?>" class="text-white-50 text-decoration-none small">Kursus Bahasa Korea</a></li>
                        <li class="mb-2"><a href="<?= base_url('english') ?>" class="text-white-50 text-decoration-none small">Kursus Bahasa Inggris</a></li>
                        <li class="mb-2"><a href="<?= base_url('german') ?>" class="text-white-50 text-decoration-none small">Kursus Bahasa Jerman</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold text-uppercase mb-3">Get Connected</h6>
                    <div class="d-flex gap-3">
                            <a href="https://wa.me/6285810310950" target="_blank" class="text-white-50 fs-5" title="WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <a href="https://www.instagram.com/soscoursepare" target="_blank" class="text-white-50 fs-5" title="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="https://www.facebook.com/soscoursepare" target="_blank" class="text-white-50 fs-5" title="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="https://www.youtube.com/@soscoursepare" target="_blank" class="text-white-50 fs-5" title="YouTube">
                                <i class="bi bi-youtube"></i>
                            </a>
                            <a href="https://www.tiktok.com/@soscoursepare" target="_blank" class="text-white-50 fs-5" title="TikTok">
                                <i class="bi bi-tiktok"></i>
                            </a>
                        </div>
                </div>
            </div>
            
            <hr class="my-4 border-secondary">
            
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="text-white-50 small mb-0">&copy; <?= date('Y') ?> SOS Course & Training. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white-50 small text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-white-50 small text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('extra_scripts') ?>

    <!-- Admission Popup Notification -->
    <div class="admission-popup" id="admissionPopup">
        <button class="admission-popup-close" onclick="closeAdmissionPopup()">
            <i class="bi bi-x"></i>
        </button>
        <div class="admission-popup-header">
            <div class="admission-popup-icon">
                <i class="bi bi-person-check"></i>
            </div>
            <div class="admission-popup-title">Pendaftaran Baru!</div>
        </div>
        <div class="admission-popup-body" id="admissionPopupBody">
            <!-- Content will be injected by JavaScript -->
        </div>
    </div>

    <!-- Program Popup Notification (Desktop Only) -->
    <div class="program-popup" id="programPopup">
        <div class="program-popup-header">
            <div class="program-popup-title">
                <i class="bi bi-mortarboard"></i>
                Program Pilihan
            </div>
            <button class="program-popup-close" onclick="closeProgramPopup()">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <img src="" alt="Program" class="program-popup-image" id="programPopupImage" style="display: none;">
        <div class="program-popup-body" id="programPopupBody">
            <!-- Content will be injected by JavaScript -->
        </div>
    </div>

    <script>
        // Escape HTML to prevent XSS (global function)
        window.escapeHtml = (text) => {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        };

        // Admission Popup Notification System
        (function() {
            const popup = document.getElementById('admissionPopup');
            const popupBody = document.getElementById('admissionPopupBody');
            let lastShownIndex = -1;
            let admissionsData = [];
            let popupInterval;

            // Fetch recent admissions
            const fetchRecentAdmissions = async () => {
                try {
                    const response = await fetch('<?= base_url('frontend/api/recent-admissions') ?>');
                    const data = await response.json();
                    
                    if (data.success && data.admissions && data.admissions.length > 0) {
                        admissionsData = data.admissions;
                        showRandomAdmission();
                    }
                } catch (error) {
                    console.error('Error fetching recent admissions:', error);
                }
            };

            // Show random admission popup
            const showRandomAdmission = () => {
                if (admissionsData.length === 0) return;

                // Pick a random admission (different from last shown)
                let randomIndex;
                if (admissionsData.length > 1) {
                    do {
                        randomIndex = Math.floor(Math.random() * admissionsData.length);
                    } while (randomIndex === lastShownIndex);
                } else {
                    randomIndex = 0;
                }

                lastShownIndex = randomIndex;
                const admission = admissionsData[randomIndex];

                // Update popup content
                popupBody.innerHTML = `
                    <strong>${escapeHtml(admission.name)}</strong> baru saja mendaftar untuk program 
                    <strong>${escapeHtml(admission.program)}</strong>
                    <div class="admission-popup-time">
                        <i class="bi bi-clock me-1"></i>${escapeHtml(admission.time_ago)}
                    </div>
                `;

                // Show popup
                popup.classList.remove('hide');
                popup.classList.add('show');

                // Auto-hide after 5 seconds
                setTimeout(() => {
                    hidePopup();
                }, 5000);
            };

            // Hide popup with animation
            const hidePopup = () => {
                popup.classList.remove('show');
                popup.classList.add('hide');
                setTimeout(() => {
                    popup.classList.remove('hide');
                }, 300);
            };

            // Close popup manually
            window.closeAdmissionPopup = function() {
                hidePopup();
            };

            // Initial fetch after page load
            setTimeout(() => {
                fetchRecentAdmissions();
            }, 3000); // Wait 3 seconds after page load

            // Poll every 30 seconds
            setInterval(fetchRecentAdmissions, 30000);

            // Show popup periodically (every 45-60 seconds)
            setInterval(() => {
                if (admissionsData.length > 0) {
                    showRandomAdmission();
                }
            }, 45000 + Math.random() * 15000);
        })();

        // Program Popup Notification System
        (function() {
            const popup = document.getElementById('programPopup');
            const popupBody = document.getElementById('programPopupBody');
            const popupImage = document.getElementById('programPopupImage');
            let lastShownIndex = -1;
            let programsData = [];

            // Fetch random programs
            const fetchRandomPrograms = async () => {
                try {
                    console.log('Fetching random programs...');
                    const response = await fetch('<?= base_url('frontend/api/random-programs') ?>?limit=3');
                    const data = await response.json();
                    console.log('Programs API response:', data);
                    
                    if (data.success && data.programs && data.programs.length > 0) {
                        programsData = data.programs;
                        console.log('Programs loaded:', programsData.length);
                        // Show popup after 3 seconds on page load
                        setTimeout(() => {
                            showRandomProgram();
                        }, 3000);
                        
                        // Show popup periodically (every 60-90 seconds)
                        setInterval(() => {
                            if (programsData.length > 0) {
                                showRandomProgram();
                            }
                        }, 60000 + Math.random() * 30000);
                    } else {
                        console.log('No programs found or API error');
                    }
                } catch (error) {
                    console.error('Error fetching random programs:', error);
                }
            };

            // Show random program popup
            const showRandomProgram = () => {
                if (programsData.length === 0) {
                    console.log('No programs data to show');
                    return;
                }

                console.log('Showing program popup with data:', programsData);

                // Pick a random program (different from last shown)
                let randomIndex;
                if (programsData.length > 1) {
                    do {
                        randomIndex = Math.floor(Math.random() * programsData.length);
                    } while (randomIndex === lastShownIndex);
                } else {
                    randomIndex = 0;
                }

                lastShownIndex = randomIndex;
                const program = programsData[randomIndex];

                // Format price
                const formatPrice = (price) => {
                    return new Intl.NumberFormat('id-ID', { 
                        style: 'currency', 
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(price);
                };

                // Update popup content
                if (program.thumbnail) {
                    popupImage.src = program.thumbnail;
                    popupImage.style.display = 'block';
                } else {
                    popupImage.style.display = 'none';
                }

                let priceHtml = '';
                if (program.original_price > 0) {
                    if (program.discount > 0) {
                        priceHtml = `
                            <span class="program-popup-original">${formatPrice(program.original_price)}</span>
                            <span class="program-popup-discount">-${program.discount}%</span>
                            <span class="program-popup-final">${formatPrice(program.final_price)}</span>
                        `;
                    } else {
                        priceHtml = `<span class="program-popup-final">${formatPrice(program.original_price)}</span>`;
                    }
                }

                popupBody.innerHTML = `
                    <div class="program-popup-name">${escapeHtml(program.title)}</div>
                    <div class="program-popup-meta">
                        <span class="program-popup-language"><i class="bi bi-translate me-1"></i>${escapeHtml(program.language || 'General')}</span>
                    </div>
                    ${priceHtml ? `<div class="program-popup-price">${priceHtml}</div>` : ''}
                    <a href="${program.url}" class="program-popup-cta" target="_blank">
                        Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                `;

                // Show popup
                console.log('Adding show class to popup, current classes:', popup.className);
                popup.classList.remove('hide');
                popup.classList.add('show');
                console.log('Popup shown, classes now:', popup.className);

                // Auto-hide after 8 seconds
                setTimeout(() => {
                    hideProgramPopup();
                }, 8000);
            };

            // Hide popup with animation
            const hideProgramPopup = () => {
                popup.classList.remove('show');
                popup.classList.add('hide');
                setTimeout(() => {
                    popup.classList.remove('hide');
                }, 300);
            };

            // Close popup manually
            window.closeProgramPopup = function() {
                hideProgramPopup();
            };

            // Initial fetch
            fetchRandomPrograms();
        })();
    </script> 
  
    <style>
    .footer {
        background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);
    }
    .footer a:hover {
        color: #fff !important;
        transition: color 0.3s;
    }
    .footer .bi:hover {
        color: #fff !important;
        transform: scale(1.2);
        transition: all 0.3s;
    }
    </style>
</body>

</html>
