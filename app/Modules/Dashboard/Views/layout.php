<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?> - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <?php
    // Get current user
    $user = auth()->user();

    // Load module menus
    if (!isset($menuItems)) {
        $menuItems = [];
        $modulesPath = APPPATH . 'Modules/';

        if (is_dir($modulesPath)) {
            foreach (scandir($modulesPath) as $module) {
                if ($module === '.' || $module === '..') {
                    continue;
                }

                $menuFile = $modulesPath . $module . '/Config/Menu.php';

                if (file_exists($menuFile)) {
                    $menuConfig = include $menuFile;

                    if (is_array($menuConfig)) {
                        foreach ($menuConfig as $item) {
                            // Check if user has required permission
                            $hasPermission = false;

                            if (empty($item['permission'])) {
                                // No permission required
                                $hasPermission = true;
                            } elseif (is_string($item['permission'])) {
                                // Single permission
                                $hasPermission = $user->can($item['permission']);
                            } elseif (is_array($item['permission'])) {
                                // Multiple permissions (any)
                                foreach ($item['permission'] as $permission) {
                                    if ($user->can($permission)) {
                                        $hasPermission = true;
                                        break;
                                    }
                                }
                            }

                            if ($hasPermission) {
                                $menuItems[] = $item;
                            }
                        }
                    }
                }
            }

            // Sort by order
            usort($menuItems, function ($a, $b) {
                return ($a['order'] ?? 999) <=> ($b['order'] ?? 999);
            });
        }
    }
    ?>
    <style>
        :root {
            --dark-red: #8B0000;
            --medium-red: #B22222;
            --light-red: #F5E8E8;
            --dark-text: #333333;
            --light-text: #5A5A5A;
            --border-color: #E0E0E0;
            --card-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
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
            border-right: 1px solid rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            height: 100vh;
            position: fixed;
            z-index: 100;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
        }

        #sidebar.compact {
            width: var(--compact-sidebar-width);
        }

        /* Sidebar Scrollable Area */
        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.4) transparent;
        }

        /* Custom Scrollbar for Webkit browsers */
        .sidebar-content::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-content::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-content::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.4);
            border-radius: 3px;
        }

        .sidebar-content::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.6);
        }

        .sidebar-header {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
            color: rgba(255, 255, 255, 0.8);
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
            background-color: rgba(255, 255, 255, 0.1);
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

        /* Menu Category Styles */
        .menu-category {
            margin-bottom: 0.25rem;
        }

        .menu-category-header {
            color: rgba(255, 255, 255, 0.7);
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.8125rem;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: none;
            letter-spacing: 0;
        }

        .menu-category-header:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .menu-category-header .category-toggle-icon {
            font-size: 0.75rem;
            transition: transform 0.2s ease;
        }

        .menu-category-header.collapsed .category-toggle-icon {
            transform: rotate(-90deg);
        }

        .menu-category-items {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .menu-category-items.show {
            max-height: 1000px; /* Large enough to show all items */
        }

        /* Submenu Items Styles */
        .submenu-items {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            padding: 0 0.5rem;
        }

        .submenu-items.show {
            max-height: 500px;
        }

        /* Submenu Styles - Rounded button style */
        .submenu {
            padding-left: 0;
            margin-left: 0.5rem;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .submenu .nav-item {
            margin-bottom: 0.25rem;
        }

        .submenu .nav-link {
            padding: 0.5rem 0.75rem;
            padding-left: 1rem;
            font-size: 0.8125rem;
            color: rgba(255, 255, 255, 0.8);
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 0.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        .submenu .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateX(3px);
        }

        .submenu .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .submenu .nav-link i {
            font-size: 0.875rem;
            margin-right: 0.625rem;
        }

        /* Has Submenu Toggle */
        .has-submenu .submenu-toggle-icon {
            font-size: 0.75rem;
            transition: transform 0.2s ease;
        }

        .has-submenu.collapsed .submenu-toggle-icon {
            transform: rotate(-90deg);
        }

        /* Parent menu with submenu styling */
        .has-submenu {
            font-weight: 500;
        }

        .has-submenu.active {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Compact Sidebar Category Styles */
        #sidebar.compact .menu-category-header span,
        #sidebar.compact .menu-category-header .category-toggle-icon {
            display: none;
        }

        #sidebar.compact .menu-category-items {
            display: none;
        }

        #sidebar.compact .submenu {
            display: none;
        }

        #sidebar.compact .has-submenu .submenu-toggle-icon {
            display: none;
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
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
            flex-shrink: 0;
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background-color: rgba(0, 0, 0, 0.2);
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
            color: rgba(255, 255, 255, 0.6) !important;
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
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 99;
            }

            .mobile-overlay.show {
                display: block;
            }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>

<body>
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="sidebar-header d-flex justify-content-between align-items-center">
            <a href="<?= base_url('/') ?>" class="text-decoration-none" style="color: inherit;">
                <h3>
                    <i class="bi bi-speedometer2 me-2"></i>
                    <span>SOSCT</span>
                </h3>
            </a>
            <button class="btn btn-link d-none d-lg-block p-0" id="sidebarToggle" style="color: var(--dark-red);">
                <i class="bi bi-chevron-left" id="toggleIcon"></i>
            </button>
            <button class="btn btn-link d-lg-none p-0" id="mobileSidebarClose" style="color: var(--dark-red);">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="sidebar-content">
            <div class="sidebar-menu">
                <?php if (isset($menuItems) && !empty($menuItems)): ?>
                    <?= render_menu($menuItems) ?>
                <?php endif ?>
            </div>
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
                        <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" id="notificationDropdown">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="notification-badge" id="notification-badge" style="display: none;">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                            <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                <span>Notifications</span>
                                <button class="btn btn-sm btn-link text-decoration-none p-0" id="mark-all-read" style="display: none;">
                                    <small>Mark all as read</small>
                                </button>
                            </h6>
                            <div id="notification-list">
                                <div class="dropdown-item text-center text-muted py-3">
                                    <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                                    <small>No new notifications</small>
                                </div>
                            </div>
                            <div class="dropdown-divider" id="notification-footer-divider" style="display: none;"></div>
                            <a class="dropdown-item text-center text-decoration-none" href="<?= base_url('notifications') ?>" id="notification-footer-link" style="display: none;">
                                <small>View all notifications</small>
                            </a>
                        </div>
                    </div>

                    <!-- Messaging Icon -->
                    <div class="nav-item dropdown me-3">
                        <a class="nav-link position-relative" href="<?= base_url('messages') ?>">
                            <i class="bi bi-chat-dots fs-5"></i>
                            <span class="notification-badge" id="message-badge" style="display: none;">0</span>
                        </a>
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
    <script>
        // Menu category accordion behavior and state persistence
        document.addEventListener('DOMContentLoaded', () => {
            // Get the last active category from localStorage
            const lastActiveCategory = localStorage.getItem('menu_active_category');
            
            // Initialize categories - only show the last active one
            document.querySelectorAll('.menu-category').forEach(category => {
                const categoryKey = category.dataset.category;
                const collapseEl = category.querySelector('.menu-category-items');
                const headerEl = category.querySelector('.menu-category-header');
                
                if (collapseEl && headerEl) {
                    if (categoryKey === lastActiveCategory) {
                        // Show this category
                        collapseEl.classList.add('show');
                        headerEl.classList.remove('collapsed');
                        headerEl.setAttribute('aria-expanded', 'true');
                    } else {
                        // Hide all other categories
                        collapseEl.classList.remove('show');
                        headerEl.classList.add('collapsed');
                        headerEl.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            // Restore collapsed state for submenus
            document.querySelectorAll('.has-submenu').forEach(submenu => {
                const targetId = submenu.getAttribute('data-bs-target');
                if (targetId) {
                    const isCollapsed = localStorage.getItem(`submenu_${targetId}`);
                    const collapseEl = document.querySelector(targetId);
                    
                    if (isCollapsed === 'true' && collapseEl) {
                        collapseEl.classList.remove('show');
                        submenu.classList.add('collapsed');
                        submenu.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            // Accordion behavior - only one category open at a time
            document.querySelectorAll('.menu-category-header').forEach(header => {
                header.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    const category = header.closest('.menu-category');
                    const categoryKey = category.dataset.category;
                    const collapseEl = category.querySelector('.menu-category-items');
                    const isCurrentlyOpen = collapseEl.classList.contains('show');
                    
                    // Close all categories first
                    document.querySelectorAll('.menu-category').forEach(cat => {
                        const catCollapse = cat.querySelector('.menu-category-items');
                        const catHeader = cat.querySelector('.menu-category-header');
                        if (catCollapse && catHeader) {
                            catCollapse.classList.remove('show');
                            catHeader.classList.add('collapsed');
                            catHeader.setAttribute('aria-expanded', 'false');
                        }
                    });
                    
                    // If the clicked category was closed, open it
                    if (!isCurrentlyOpen) {
                        collapseEl.classList.add('show');
                        header.classList.remove('collapsed');
                        header.setAttribute('aria-expanded', 'true');
                        localStorage.setItem('menu_active_category', categoryKey);
                    } else {
                        // If it was open and we closed it, clear the active category
                        localStorage.removeItem('menu_active_category');
                    }
                });
            });

            // Submenu toggle behavior
            document.querySelectorAll('.has-submenu').forEach(submenu => {
                submenu.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    const targetId = submenu.getAttribute('data-target');
                    const collapseEl = document.querySelector(targetId);
                    const isCurrentlyOpen = collapseEl.classList.contains('show');
                    
                    if (isCurrentlyOpen) {
                        collapseEl.classList.remove('show');
                        submenu.classList.add('collapsed');
                        submenu.setAttribute('aria-expanded', 'false');
                    } else {
                        collapseEl.classList.add('show');
                        submenu.classList.remove('collapsed');
                        submenu.setAttribute('aria-expanded', 'true');
                    }
                });
            });
        });
    </script>
    <script>
        // Poll for unread messages
        document.addEventListener('DOMContentLoaded', () => {
            const updateUnreadCount = () => {
                fetch('<?= base_url('messages/api/unread-count') ?>')
                    .then(res => {
                        if (res.ok) return res.json();
                        throw new Error('Network response was not ok');
                    })
                    .then(data => {
                        const badge = document.getElementById('message-badge');
                        if (badge) {
                            if (data.count > 0) {
                                badge.textContent = data.count > 99 ? '99+' : data.count;
                                badge.style.display = 'flex';
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                    })
                    .catch(e => console.error('Error fetching unread count:', e));
            };

            // Initial check
            updateUnreadCount();

            // Poll every 30 seconds
            setInterval(updateUnreadCount, 30000);
        });
    </script>
    <script>
        // Notification system
        document.addEventListener('DOMContentLoaded', () => {
            const notificationBadge = document.getElementById('notification-badge');
            const notificationList = document.getElementById('notification-list');
            const markAllReadBtn = document.getElementById('mark-all-read');
            const notificationFooterDivider = document.getElementById('notification-footer-divider');
            const notificationFooterLink = document.getElementById('notification-footer-link');
            const notificationDropdown = document.getElementById('notificationDropdown');

            // Update notification badge count
            const updateNotificationCount = () => {
                fetch('<?= base_url('notifications/api/unread-count') ?>')
                    .then(res => {
                        if (res.ok) return res.json();
                        throw new Error('Network response was not ok');
                    })
                    .then(data => {
                        if (notificationBadge) {
                            if (data.count > 0) {
                                notificationBadge.textContent = data.count > 99 ? '99+' : data.count;
                                notificationBadge.style.display = 'flex';
                                if (markAllReadBtn) markAllReadBtn.style.display = 'inline';
                            } else {
                                notificationBadge.style.display = 'none';
                                if (markAllReadBtn) markAllReadBtn.style.display = 'none';
                            }
                        }
                    })
                    .catch(e => console.error('Error fetching notification count:', e));
            };

            // Load notifications into dropdown
            const loadNotifications = () => {
                fetch('<?= base_url('notifications/api/list') ?>')
                    .then(res => {
                        if (res.ok) return res.json();
                        throw new Error('Network response was not ok');
                    })
                    .then(data => {
                        if (notificationList && data.notifications) {
                            if (data.notifications.length === 0) {
                                notificationList.innerHTML = `
                                    <div class="dropdown-item text-center text-muted py-3">
                                        <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                                        <small>No new notifications</small>
                                    </div>
                                `;
                                if (notificationFooterDivider) notificationFooterDivider.style.display = 'none';
                                if (notificationFooterLink) notificationFooterLink.style.display = 'none';
                            } else {
                                notificationList.innerHTML = data.notifications.map(n => `
                                    <div class="dropdown-item notification-item py-2 border-bottom" data-id="${n.id}" data-applicant-name="${n.data?.applicant_name || ''}" style="cursor: pointer;">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                <i class="bi ${n.type === 'new_admission' ? 'bi-person-plus-fill' : 'bi-bell-fill'} fs-5 text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="fw-semibold small">${escapeHtml(n.title)}</div>
                                                <div class="text-muted small">${escapeHtml(n.message)}</div>
                                                <small class="text-muted">${formatTimeAgo(n.created_at)}</small>
                                            </div>
                                        </div>
                                    </div>
                                `).join('');
                                if (notificationFooterDivider) notificationFooterDivider.style.display = 'block';
                                if (notificationFooterLink) notificationFooterLink.style.display = 'block';

                                // Add click handlers for each notification
                                document.querySelectorAll('.notification-item').forEach(item => {
                                    item.addEventListener('click', function() {
                                        const id = this.dataset.id;
                                        const applicantName = this.dataset.applicantName;
                                        markAsRead(id);
                                        // Navigate to admission search with applicant name
                                        if (applicantName) {
                                            window.location.href = '<?= base_url('admission/search') ?>?keyword=' + encodeURIComponent(applicantName);
                                        }
                                    });
                                });
                            }
                        }
                    })
                    .catch(e => console.error('Error loading notifications:', e));
            };

            // Mark a single notification as read
            const markAsRead = (id) => {
                fetch(`<?= base_url('notifications/api/mark-read/') ?>${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    updateNotificationCount();
                    loadNotifications();
                })
                .catch(e => console.error('Error marking notification as read:', e));
            };

            // Mark all notifications as read
            const markAllAsRead = () => {
                fetch('<?= base_url('notifications/api/mark-all-read') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    updateNotificationCount();
                    loadNotifications();
                })
                .catch(e => console.error('Error marking all notifications as read:', e));
            };

            // Helper function to escape HTML
            const escapeHtml = (text) => {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            };

            // Helper function to format time ago
            const formatTimeAgo = (dateString) => {
                // The database stores timestamps in UTC format (YYYY-MM-DD HH:MM:SS)
                // We need to treat it as UTC by appending 'Z' so JavaScript parses it correctly
                const date = new Date(dateString + 'Z'); // Append 'Z' to indicate UTC
                const now = new Date();
                const diffMs = now - date; // Difference in milliseconds
                const seconds = Math.floor(diffMs / 1000);

                if (seconds < 60) return 'Just now';
                if (seconds < 3600) return Math.floor(seconds / 60) + ' minutes ago';
                if (seconds < 86400) return Math.floor(seconds / 3600) + ' hours ago';
                if (seconds < 604800) return Math.floor(seconds / 86400) + ' days ago';
                return date.toLocaleDateString();
            };

            // Initial load
            updateNotificationCount();

            // Load notifications when dropdown is opened
            if (notificationDropdown) {
                notificationDropdown.addEventListener('click', () => {
                    loadNotifications();
                });
            }

            // Mark all as read button handler
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    markAllAsRead();
                });
            }

            // Poll every 30 seconds
            setInterval(updateNotificationCount, 30000);
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>