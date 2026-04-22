<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= esc($title ?? 'Inventaris') ?> - Modul Inventaris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --inventory-primary: #2E7D32;
            --inventory-secondary: #43A047;
            --inventory-light: #E8F5E9;
            --inventory-dark: #1B5E20;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background-color: #f5f5f5;
            width: 100% !important;
            overflow-x: hidden !important;
        }

        html, body {
            max-width: 100% !important;
        }

        /* Force full width layout on mobile */
        .inventory-container {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        /* Sidebar */
        .inventory-sidebar {
            width: 260px;
            background: linear-gradient(180deg, var(--inventory-primary) 0%, var(--inventory-dark) 100%);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 2px 0 8px rgba(0,0,0,0.15);
            transition: width 0.3s ease;
            z-index: 1000;
        }

        .inventory-sidebar.minimized {
            width: 70px;
        }

        .inventory-sidebar.minimized .nav-item span {
            display: none;
        }

        .inventory-sidebar.minimized .brand span {
            display: none;
        }

        .inventory-sidebar.minimized .nav-section {
            text-align: center;
            padding: 0.5rem 0;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .inventory-sidebar {
                width: 280px !important;
                transform: translateX(-100%) !important;
                transition: transform 0.3s ease;
            }

            .inventory-sidebar.show {
                transform: translateX(0) !important;
            }

            .inventory-main {
                margin-left: 0 !important;
                padding: 0.75rem !important;
                width: 100% !important;
            }

            .inventory-sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }

            .inventory-sidebar-overlay.show {
                display: block;
            }

            .inventory-header {
                padding: 0.75rem !important;
                margin-bottom: 1rem !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.75rem !important;
            }

            .inventory-header h4 {
                font-size: 1.1rem !important;
                flex-wrap: wrap !important;
                gap: 0.5rem !important;
                width: 100% !important;
            }

            .inventory-sidebar .brand {
                padding: 1rem !important;
                font-size: 1.1rem !important;
            }

            .inventory-sidebar .nav-item {
                padding: 0.75rem 1rem !important;
                font-size: 0.95rem !important;
            }

            .inventory-sidebar .nav-section {
                padding: 0.5rem 1rem !important;
                font-size: 0.75rem !important;
            }

            /* Ensure cards take full width on mobile */
            .card {
                width: 100% !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .row {
                --bs-gutter-x: 0.75rem !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                width: 100% !important;
            }

            /* Force all columns to stack on mobile */
            .col-12, .col-md-8, .col-md-4, .col-md-3, .col-md-6, .col-lg-8, .col-lg-4, .col-sm-6 {
                flex: 0 0 100% !important;
                max-width: 100% !important;
                padding-left: 0.375rem !important;
                padding-right: 0.375rem !important;
            }

            /* Specific overrides for common column classes */
            @media (max-width: 768px) {
                .col-md-3, .col-md-4, .col-md-6, .col-md-8, .col-lg-4, .col-lg-8 {
                    flex: 0 0 100% !important;
                    max-width: 100% !important;
                }
            }
        }

        /* Extra small screens */
        @media (max-width: 576px) {
            .inventory-main {
                padding: 0.5rem !important;
            }

            .inventory-header {
                padding: 0.5rem !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.5rem !important;
            }

            .inventory-header h4 {
                font-size: 1rem !important;
            }

            .inventory-sidebar {
                width: 260px !important;
            }

            .inventory-sidebar .nav-item {
                padding: 0.6rem 0.75rem !important;
                font-size: 0.9rem !important;
            }

            .inventory-sidebar .brand {
                padding: 0.75rem !important;
                font-size: 1rem !important;
            }

            /* Improve card spacing on mobile */
            .inventory-card {
                margin-bottom: 1rem !important;
            }

            .inventory-card .card-body {
                padding: 0.75rem !important;
            }

            .inventory-card .card-header {
                padding: 0.5rem !important;
                font-size: 0.95rem !important;
            }

            /* Better button spacing */
            .btn-group .btn {
                margin-right: 0.25rem !important;
            }

            .btn-group .btn:last-child {
                margin-right: 0 !important;
            }

            /* Ensure full width on very small screens */
            .container, .container-fluid {
                padding-left: 0.25rem !important;
                padding-right: 0.25rem !important;
            }
        }

        /* Mobile table improvements */
        @media (max-width: 768px) {
            .table-responsive {
                border: none;
            }

            .table th,
            .table td {
                padding: 0.5rem;
                font-size: 0.875rem;
            }

            /* Hide less important columns on mobile */
            .table th:nth-child(3),
            .table td:nth-child(3),
            .table th:nth-child(4),
            .table td:nth-child(4),
            .table th:nth-child(6),
            .table td:nth-child(6) {
                display: none;
            }
        }

        /* Desktop toggle button */
        @media (min-width: 769px) {
            .sidebar-toggle-mobile {
                display: none;
            }
        }

        /* Mobile toggle button */
        @media (max-width: 768px) {
            .sidebar-toggle-desktop {
                display: none;
            }
        }
        
        .inventory-sidebar .brand {
            padding: 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .inventory-sidebar .nav-menu {
            padding: 1rem 0;
        }
        
        .inventory-sidebar .nav-item {
            padding: 0.75rem 1.25rem;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .inventory-sidebar .nav-item:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        
        .inventory-sidebar .nav-item.active {
            background-color: rgba(255,255,255,0.15);
            color: white;
            border-left-color: white;
        }
        
        .inventory-sidebar .nav-item i {
            width: 20px;
            text-align: center;
        }
        
        .inventory-sidebar .nav-section {
            padding: 0.5rem 1.25rem;
            color: rgba(255,255,255,0.5);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.5rem;
        }
        
        /* Main Content */
        .inventory-main {
            margin-left: 260px;
            padding: 1.5rem;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .inventory-main.sidebar-minimized {
            margin-left: 70px;
        }
        
        /* Header */
        .inventory-header {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .inventory-header h4 {
            margin: 0;
            color: var(--inventory-dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Cards */
        .inventory-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: none;
        }
        
        .inventory-card .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }
        
        /* Buttons */
        .btn-inventory {
            background-color: var(--inventory-primary);
            border-color: var(--inventory-primary);
            color: white;
        }
        
        .btn-inventory:hover {
            background-color: var(--inventory-dark);
            border-color: var(--inventory-dark);
            color: white;
        }
        
        .btn-outline-inventory {
            color: var(--inventory-primary);
            border-color: var(--inventory-primary);
        }
        
        .btn-outline-inventory:hover {
            background-color: var(--inventory-primary);
            color: white;
        }
        
        /* Badges */
        .badge-inventory {
            background-color: var(--inventory-primary);
        }
        
        .badge-low-stock {
            background-color: #dc3545;
        }
        
        .badge-ok {
            background-color: #198754;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="inventory-sidebar">
        <div class="brand">
            <i class="bi bi-box-seam"></i>
            <span>Inventaris</span>
        </div>
        
        <nav class="nav-menu">
            <a href="<?= base_url('inventory/items') ?>" class="nav-item <?= strpos(current_url(), 'inventory/items') !== false && strpos(current_url(), 'create') === false ? 'active' : '' ?>">
                <i class="bi bi-list-ul"></i>
                <span>Barang</span>
            </a>
            <a href="<?= base_url('inventory/items/create') ?>" class="nav-item <?= strpos(current_url(), 'inventory/items/create') !== false ? 'active' : '' ?>">
                <i class="bi bi-plus-circle"></i>
                <span>Tambah Barang</span>
            </a>
            
            <div class="nav-section">Data Master</div>
            
            <a href="<?= base_url('inventory/categories') ?>" class="nav-item <?= strpos(current_url(), 'inventory/categories') !== false ? 'active' : '' ?>">
                <i class="bi bi-folder"></i>
                <span>Kategori</span>
            </a>
            <a href="<?= base_url('inventory/locations') ?>" class="nav-item <?= strpos(current_url(), 'inventory/locations') !== false ? 'active' : '' ?>">
                <i class="bi bi-geo-alt"></i>
                <span>Lokasi</span>
            </a>
            
            <div class="nav-section">Operasi</div>
            
            <a href="<?= base_url('inventory/movements') ?>" class="nav-item <?= strpos(current_url(), 'inventory/movements') !== false ? 'active' : '' ?>">
                <i class="bi bi-arrow-left-right"></i>
                <span>Mutasi</span>
            </a>
            <a href="<?= base_url('inventory/stock-opname') ?>" class="nav-item <?= strpos(current_url(), 'inventory/stock-opname') !== false ? 'active' : '' ?>">
                <i class="bi bi-clipboard-data"></i>
                <span>Stock Opname</span>
            </a>
            <a href="<?= base_url('inventory/alerts') ?>" class="nav-item <?= strpos(current_url(), 'inventory/alerts') !== false ? 'active' : '' ?>">
                <i class="bi bi-exclamation-triangle"></i>
                <span>Peringatan</span>
            </a>
            
            <div class="nav-section">Laporan</div>
            
            <a href="<?= base_url('inventory/reports/summary') ?>" class="nav-item <?= strpos(current_url(), 'inventory/reports') !== false ? 'active' : '' ?>">
                <i class="bi bi-bar-chart"></i>
                <span>Ringkasan</span>
            </a>
            
            <div class="nav-section">Sistem</div>
            
            <a href="<?= base_url('/') ?>" class="nav-item">
                <i class="bi bi-house"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </nav>
    </div>

    <!-- Sidebar Overlay for Mobile -->
    <div class="inventory-sidebar-overlay"></div>

    <!-- Main Content -->
    <div class="inventory-main">
        <div class="inventory-container">
        <!-- Header -->
        <div class="inventory-header">
            <h4>
                <button class="btn btn-sm btn-outline-secondary sidebar-toggle sidebar-toggle-desktop me-2" title="Minimize Sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <button class="btn btn-sm btn-outline-secondary sidebar-toggle sidebar-toggle-mobile me-2 d-md-none" title="Toggle Sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <i class="bi bi-<?= $icon ?? 'box-seam' ?>"></i>
                <?= $pageTitle ?? 'Inventaris' ?>
            </h4>
            <div>
                <?= $headerActions ?? '' ?>
            </div>
        </div>
        
        <!-- Content -->
        <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
        </div> <!-- Close inventory-container -->
    </div> <!-- Close inventory-main -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.inventory-sidebar');
            const main = document.querySelector('.inventory-main');
            const overlay = document.querySelector('.inventory-sidebar-overlay');
            const toggles = document.querySelectorAll('.sidebar-toggle');

            // Force layout recalculation on mobile
            function forceMobileLayout() {
                if (window.innerWidth <= 768) {
                    document.body.style.width = '100%';
                    document.body.style.maxWidth = '100%';
                    main.style.width = '100%';
                    main.style.marginLeft = '0';
                    main.style.padding = '0.75rem';
                }
            }

            // Apply mobile layout immediately
            forceMobileLayout();

            toggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        // Mobile behavior: toggle show class
                        sidebar.classList.toggle('show');
                        overlay.classList.toggle('show');
                    } else {
                        // Desktop behavior: toggle minimized class
                        sidebar.classList.toggle('minimized');
                        main.classList.toggle('sidebar-minimized');
                    }
                });
            });

            // Close sidebar when clicking overlay on mobile
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });

            // Close sidebar on window resize if switching to desktop
            window.addEventListener('resize', function() {
                forceMobileLayout();
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>
