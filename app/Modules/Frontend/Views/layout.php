<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'ERP System') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --dark-red: #8B0000;
            --medium-red: #B22222;
            --light-red: #F5E8E8;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        
        /* Navbar */
        .navbar-custom {
            background: linear-gradient(90deg, var(--dark-red) 0%, #6B0000 100%);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .navbar-custom .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }
        
        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.2s;
        }
        
        .navbar-custom .nav-link:hover {
            color: white !important;
            background-color: rgba(255,255,255,0.1);
            border-radius: 4px;
        }
        
        .navbar-custom .nav-link.btn-apply {
            color: var(--dark-red) !important;
        }
        
        .navbar-custom .nav-link.btn-apply:hover {
            color: white !important;
            background-color: var(--dark-red) !important;
        }
        
        .btn-apply {
            background-color: white !important;
            color: var(--dark-red) !important;
            font-weight: 600;
            padding: 0.5rem 1.5rem !important;
            border-radius: 25px;
            border: 2px solid white !important;
            transition: all 0.3s;
        }
        
        .btn-apply i {
            color: var(--dark-red) !important;
        }
        
        .btn-apply:hover {
            background-color: var(--dark-red) !important;
            color: white !important;
            border-color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255,255,255,0.3);
        }
        
        .btn-apply:hover i {
            color: white !important;
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.3s;
            height: 100%;
        }
        
        .card-custom:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
            color: rgba(255,255,255,0.8);
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
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="bi bi-mortarboard me-2"></i>FEEC
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border-color: rgba(255,255,255,0.5);">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/') ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('programs') ?>">Programs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('about') ?>">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('apply') ?>">Apply</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('contact') ?>">Contact</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="nav-link btn-apply" href="<?= base_url('dashboard') ?>">
                            <i class="bi bi-speedometer2 me-1"></i>MyDashboard
                        </a>
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
                    <p class="mb-0">&copy; <?= date('Y') ?> Future English Education Center. All rights reserved.</p>
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
</body>
</html>
