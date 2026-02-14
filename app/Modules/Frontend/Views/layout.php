<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            z-index: 9999;
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
    </style>
    
    <?= $this->renderSection('extra_head') ?>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="bi bi-mortarboard me-2"></i>SOS Course
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
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('about') ?>">Tentang SOS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('apply') ?>">Pendaftaran</a>
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

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; <?= date('Y') ?> SOS Course and Training. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white-50 me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white-50 me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-white-50 me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white-50"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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

    <script>
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

            // Escape HTML to prevent XSS
            const escapeHtml = (text) => {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            };

            // Initial fetch after page load
            setTimeout(() => {
                fetchRecentAdmissions();
            }, 3000); // Wait 3 seconds after page load

            // Poll every 15 seconds
            setInterval(fetchRecentAdmissions, 15000);

            // Show popup periodically (every 45-60 seconds)
            setInterval(() => {
                if (admissionsData.length > 0) {
                    showRandomAdmission();
                }
            }, 45000 + Math.random() * 15000);
        })();
    </script>
</body>

</html>
