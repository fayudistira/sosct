<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?> - ERP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --dark-red: #8B0000;
            --medium-red: #B22222;
            --light-red: #F5E8E8;
            --dark-text: #333333;
            --light-text: #5A5A5A;
            --border-color: #E0E0E0;
            --card-shadow: 0 2px 4px rgba(0,0,0,0.05);
            --sidebar-width: 240px;
            --compact-sidebar-width: 70px;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background-color: #FFFFFF;
            color: var(--dark-text);
            font-size: 0.875rem;
            overflow-x: hidden;
        }
        
        /* Compact Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--dark-red) 0%, #6B0000 100%);
            border-right: 1px solid rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            height: 100vh;
            position: fixed;
            z-index: 100;
            box-shadow: 2px 0 8px rgba(0,0,0,0.15);
        }
        
        #sidebar.compact {
            width: var(--compact-sidebar-width);
        }
        
        .sidebar-header {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background-color: transparent;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .sidebar-header h3 {
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
            margin: 0;
            display: flex;
            align-items: center;
            overflow: hidden;
        }
        
        .sidebar-header h3 i {
            min-width: 24px;
            flex-shrink: 0;
        }
        
        .sidebar-header button {
            color: white !important;
        }
        
        #sidebar.compact .sidebar-header {
            justify-content: center;
            padding: 1.25rem 0.5rem;
        }
        
        #sidebar.compact .sidebar-header h3 {
            justify-content: center;
        }
        
        #sidebar.compact .sidebar-header h3 span {
            display: none;
        }
        
        #sidebar.compact .sidebar-header h3 i {
            margin-right: 0 !important;
        }
        
        #sidebar.compact .sidebar-header button#sidebarToggle {
            position: absolute;
            right: 0.5rem;
            top: 1.25rem;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
            overflow-x: hidden;
        }
        
        .sidebar-menu .nav-item {
            margin-bottom: 0.125rem;
        }
        
        .sidebar-menu .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.625rem 1rem;
            border-radius: 0;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: all 0.2s;
            white-space: nowrap;
            overflow: hidden;
            text-decoration: none;
        }
        
        .sidebar-menu .nav-link:hover, 
        .sidebar-menu .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-left: 3px solid white;
        }
        
        .sidebar-menu .nav-link i {
            font-size: 1.125rem;
            margin-right: 0.75rem;
            min-width: 24px;
            text-align: center;
            flex-shrink: 0;
        }
        
        .sidebar-menu .nav-link span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        #sidebar.compact .sidebar-menu .nav-link {
            justify-content: center;
            padding: 0.625rem 0.5rem;
        }
        
        #sidebar.compact .sidebar-menu .nav-link span {
            display: none !important;
        }
        
        #sidebar.compact .sidebar-menu .nav-link i {
            margin-right: 0;
        }
        
        #sidebar.compact .sidebar-footer .user-info {
            display: none;
        }
        
        /* Main Content */
        #content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            padding: 1.25rem;
            min-height: 100vh;
        }
        
        #content.expanded {
            margin-left: var(--compact-sidebar-width);
        }
        
        /* Top Navbar */
        .top-navbar {
            background-color: white;
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 0;
            margin-bottom: 1.5rem;
            position: sticky;
            top: 0;
            z-index: 90;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--medium-red);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Cards */
        .dashboard-card {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            background-color: white;
            transition: all 0.2s ease;
            margin-bottom: 1.25rem;
            overflow: hidden;
        }
        
        .dashboard-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--border-color);
            padding: 0.875rem 1.25rem;
            font-weight: 600;
            color: var(--dark-text);
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        .card-body.compact {
            padding: 1rem;
        }
        
        /* Stats Cards */
        .stat-card {
            border-left: 3px solid var(--dark-red);
            height: 100%;
        }
        
        .stat-number {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-red);
            line-height: 1;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: var(--light-text);
            margin-bottom: 0.25rem;
        }
        
        /* Buttons */
        .btn-dark-red {
            background-color: var(--dark-red);
            color: white;
            border: 1px solid var(--dark-red);
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
        }
        
        .btn-outline-dark-red:hover {
            background-color: var(--dark-red);
            color: white;
        }
        
        /* Tables */
        .compact-table th {
            background-color: var(--light-red);
            color: var(--dark-red);
            border-top: none;
            font-weight: 600;
            padding: 0.75rem;
            font-size: 0.8125rem;
        }
        
        .compact-table td {
            padding: 0.625rem 0.75rem;
            font-size: 0.8125rem;
            vertical-align: middle;
        }
        
        .table-actions .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            margin-right: 0.25rem;
        }
        
        /* Sidebar Footer */
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            background-color: rgba(0,0,0,0.2);
        }
        
        #sidebar.compact .sidebar-footer {
            padding: 0.75rem 0.5rem;
        }
        
        #sidebar.compact .sidebar-footer .d-flex {
            justify-content: center;
        }
        
        #sidebar.compact .sidebar-footer .user-info {
            display: none;
        }
        
        #sidebar.compact .sidebar-footer .avatar-sm {
            margin-right: 0 !important;
        }
        
        .sidebar-footer .user-info .fw-medium {
            color: white;
        }
        
        .sidebar-footer .user-info .text-muted {
            color: rgba(255,255,255,0.6) !important;
        }
        
        .avatar-sm {
            width: 32px;
            height: 32px;
        }
        
        .fs-sm {
            font-size: 0.8125rem;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            
            #sidebar.mobile-show {
                margin-left: 0;
            }
            
            #content {
                margin-left: 0;
            }
            
            #content.expanded {
                margin-left: 0;
            }
            
            .mobile-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0,0,0,0.5);
                z-index: 99;
            }
            
            .mobile-overlay.show {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
    
    <!-- Sidebar -->
    <div id="sidebar">
        <div class="sidebar-header d-flex justify-content-between align-items-center">
            <h3>
                <i class="bi bi-speedometer2 me-2"></i>
                <span>SOSCT</span>
            </h3>
            <button class="btn btn-link d-none d-lg-block p-0" id="sidebarToggle" style="color: var(--dark-red);">
                <i class="bi bi-chevron-left" id="toggleIcon"></i>
            </button>
            <button class="btn btn-link d-lg-none p-0" id="mobileSidebarClose" style="color: var(--dark-red);">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="sidebar-menu">
            <?php if (isset($menuItems) && !empty($menuItems)): ?>
                <?= render_menu($menuItems) ?>
            <?php endif ?>
        </div>
        
        <div class="sidebar-footer">
            <div class="d-flex align-items-center">
                <?php if (isset($user)): ?>
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user->username ?? $user->email) ?>&background=8B0000&color=fff&size=32" 
                         class="avatar-sm rounded-circle me-2" alt="User">
                    <div class="user-info">
                        <div class="fs-sm fw-medium"><?= esc($user->username ?? $user->email) ?></div>
                        <div class="fs-sm text-muted">User</div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div id="content">
        <!-- Top Navbar -->
        <nav class="navbar top-navbar navbar-expand-lg">
            <div class="container-fluid px-0">
                <button class="btn btn-link d-lg-none" id="mobileSidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="navbar-nav ms-auto align-items-center">
                    <div class="nav-item dropdown me-3">
                        <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="notification-badge">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Notifications</h6>
                            <div class="dropdown-item text-center text-muted">
                                <small>No new notifications</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <?php if (isset($user)): ?>
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user->username ?? $user->email) ?>&background=8B0000&color=fff&size=32" 
                                     class="avatar-sm rounded-circle me-2" alt="User">
                                <span class="d-none d-md-inline"><?= esc($user->username ?? $user->email) ?></span>
                            <?php endif ?>
                            <i class="bi bi-chevron-down ms-1"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="dropdown-header">
                                <strong><?= esc($user->username ?? $user->email) ?></strong>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('account') ?>"><i class="bi bi-person me-2"></i>My Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Flash Messages -->
        <?php if (session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif ?>
        
        <?php if (session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif ?>

        <!-- Page Content -->
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const toggleIcon = document.getElementById('toggleIcon');
        const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
        const mobileSidebarClose = document.getElementById('mobileSidebarClose');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('compact');
                content.classList.toggle('expanded');
                
                // Toggle icon
                if (sidebar.classList.contains('compact')) {
                    toggleIcon.classList.remove('bi-chevron-left');
                    toggleIcon.classList.add('bi-chevron-right');
                } else {
                    toggleIcon.classList.remove('bi-chevron-right');
                    toggleIcon.classList.add('bi-chevron-left');
                }
            });
        }
        
        if (mobileSidebarToggle) {
            mobileSidebarToggle.addEventListener('click', () => {
                sidebar.classList.add('mobile-show');
                mobileOverlay.classList.add('show');
            });
        }
        
        if (mobileSidebarClose) {
            mobileSidebarClose.addEventListener('click', () => {
                sidebar.classList.remove('mobile-show');
                mobileOverlay.classList.remove('show');
            });
        }
        
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', () => {
                sidebar.classList.remove('mobile-show');
                mobileOverlay.classList.remove('show');
            });
        }
    </script>
</body>
</html>
