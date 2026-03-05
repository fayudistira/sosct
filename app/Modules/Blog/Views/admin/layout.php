<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Blog Management') ?> - Admin</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
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
            background-color: #F8F9FA;
            color: var(--dark-text);
            font-size: 0.875rem;
            overflow-x: hidden;
        }

        /* Blog Admin Sidebar */
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

        #sidebar.collapsed {
            width: var(--compact-sidebar-width);
        }

        #sidebar .brand {
            padding: 1.25rem 1rem;
            font-size: 1.125rem;
            font-weight: 600;
            color: #fff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        #sidebar.collapsed .brand span {
            display: none;
        }

        #sidebar .brand i {
            font-size: 1.5rem;
        }

        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            padding: 0.75rem 0;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .menu-section {
            padding: 0.5rem 1rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 0.5rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.625rem 1rem;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            gap: 0.75rem;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.15);
            border-left-color: #fff;
            color: #fff;
        }

        .menu-item i {
            font-size: 1.1rem;
            width: 1.5rem;
            text-align: center;
        }

        .menu-item span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #sidebar.collapsed .menu-item span {
            display: none;
        }

        #sidebar.collapsed .menu-section {
            display: none;
        }

        /* User section */
        .sidebar-user {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.2);
        }

        .sidebar-user .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-user .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--medium-red);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
        }

        .sidebar-user .user-details {
            flex: 1;
            overflow: hidden;
        }

        .sidebar-user .user-name {
            color: #fff;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user .user-role {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
        }

        #sidebar.collapsed .sidebar-user .user-details {
            display: none;
        }

        /* Main content */
        #main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        #main-content.expanded {
            margin-left: var(--compact-sidebar-width);
        }

        /* Header */
        .main-header {
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: var(--card-shadow);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .toggle-btn {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--dark-text);
            cursor: pointer;
            padding: 0.25rem;
            transition: color 0.2s;
        }

        .toggle-btn:hover {
            color: var(--dark-red);
        }

        .breadcrumb {
            margin: 0;
            font-size: 0.875rem;
        }

        .breadcrumb-item a {
            color: var(--dark-red);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--light-text);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-btn {
            background: none;
            border: none;
            font-size: 1.1rem;
            color: var(--light-text);
            cursor: pointer;
            position: relative;
            padding: 0.25rem 0.5rem;
            transition: color 0.2s;
        }

        .header-btn:hover {
            color: var(--dark-red);
        }

        .header-btn .badge {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 0.625rem;
            padding: 0.15rem 0.35rem;
        }

        /* Page content */
        .page-content {
            padding: 1.5rem;
        }

        /* Cards */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            box-shadow: var(--card-shadow);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            padding: 0.75rem 1rem;
        }

        /* Buttons */
        .btn-dark-red {
            background: var(--dark-red);
            border-color: var(--dark-red);
            color: #fff;
        }

        .btn-dark-red:hover {
            background: #6B0000;
            border-color: #6B0000;
            color: #fff;
        }

        .btn-outline-dark-red {
            border-color: var(--dark-red);
            color: var(--dark-red);
        }

        .btn-outline-dark-red:hover {
            background: var(--dark-red);
            border-color: var(--dark-red);
            color: #fff;
        }

        /* Forms */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--dark-red);
            box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.15);
        }

        .form-check-input:checked {
            background-color: var(--dark-red);
            border-color: var(--dark-red);
        }

        /* Tables */
        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            color: var(--dark-text);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr:hover {
            background: var(--light-red);
        }

        /* Status badges */
        .badge-published {
            background: #198754;
            color: #fff;
        }

        .badge-draft {
            background: #6c757d;
            color: #fff;
        }

        .badge-featured {
            background: var(--dark-red);
            color: #fff;
        }

        /* Dropdown */
        .dropdown-menu {
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item:hover {
            background: var(--light-red);
            color: var(--dark-red);
        }

        /* Alerts */
        .alert-dark-red {
            background: var(--dark-red);
            border-color: var(--dark-red);
            color: #fff;
        }

        /* Pagination */
        .page-item.active .page-link {
            background: var(--dark-red);
            border-color: var(--dark-red);
        }

        .page-link {
            color: var(--dark-red);
        }

        .page-link:hover {
            color: #6B0000;
        }

        /* Stats cards */
        .stat-card {
            background: #fff;
            border-radius: 0.5rem;
            padding: 1.25rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-text);
            line-height: 1;
        }

        .stat-card .stat-label {
            color: var(--light-text);
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        /* Utility */
        .text-dark-red {
            color: var(--dark-red) !important;
        }

        .bg-dark-red {
            background-color: var(--dark-red) !important;
        }

        .border-dark-red {
            border-color: var(--dark-red) !important;
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>

<body>
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="brand">
            <i class="bi bi-journal-text"></i>
            <span>Blog Admin</span>
        </div>

        <div class="sidebar-menu">
            <div class="menu-section">Content</div>
            
            <a href="<?= base_url('admin/blog') ?>" class="menu-item <?= url_is('admin/blog*') && !url_is('admin/blog/categories*') && !url_is('admin/blog/tags*') ? 'active' : '' ?>">
                <i class="bi bi-file-post"></i>
                <span>All Posts</span>
            </a>
            
            <a href="<?= base_url('admin/blog/create') ?>" class="menu-item <?= url_is('admin/blog/create') ? 'active' : '' ?>">
                <i class="bi bi-plus-circle"></i>
                <span>New Post</span>
            </a>
            
            <div class="menu-section">Organization</div>
            
            <a href="<?= base_url('admin/blog/categories') ?>" class="menu-item <?= url_is('admin/blog/categories*') ? 'active' : '' ?>">
                <i class="bi bi-folder"></i>
                <span>Categories</span>
            </a>
            
            <a href="<?= base_url('admin/blog/tags') ?>" class="menu-item <?= url_is('admin/blog/tags*') ? 'active' : '' ?>">
                <i class="bi bi-tags"></i>
                <span>Tags</span>
            </a>
            
            <div class="menu-section">Analytics</div>
            
            <a href="<?= base_url('admin/blog/stats') ?>" class="menu-item <?= url_is('admin/blog/stats*') ? 'active' : '' ?>">
                <i class="bi bi-graph-up"></i>
                <span>Statistics</span>
            </a>
            
            <div class="menu-section">Public Site</div>
            
            <a href="<?= base_url('blog') ?>" class="menu-item" target="_blank">
                <i class="bi bi-box-arrow-up-right"></i>
                <span>View Blog</span>
            </a>
        </div>

        <div class="sidebar-user">
            <?php $user = auth()->user(); ?>
            <div class="user-info">
                <div class="user-avatar">
                    <?= strtoupper(substr($user->username ?? 'U', 0, 1)) ?>
                </div>
                <div class="user-details">
                    <div class="user-name"><?= esc($user->username ?? 'User') ?></div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div id="main-content">
        <!-- Header -->
        <header class="main-header">
            <div class="header-left">
                <button class="toggle-btn" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Admin</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/blog') ?>">Blog</a></li>
                        <?= $this->renderSection('breadcrumb') ?>
                    </ol>
                </nav>
            </div>
            <div class="header-right">
                <a href="<?= base_url('blog') ?>" target="_blank" class="header-btn" title="View Blog">
                    <i class="bi bi-box-arrow-up-right"></i>
                </a>
                <button class="header-btn" title="Notifications">
                    <i class="bi bi-bell"></i>
                </button>
                <a href="<?= base_url('logout') ?>" class="header-btn" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </header>

        <!-- Page Content -->
        <main class="page-content">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('main-content').classList.toggle('expanded');
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>
