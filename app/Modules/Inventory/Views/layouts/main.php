<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Inventory') ?> - Inventory Module</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <span>Inventory</span>
        </div>
        
        <nav class="nav-menu">
            <a href="<?= base_url('inventory/items') ?>" class="nav-item <?= strpos(current_url(), 'inventory/items') !== false && strpos(current_url(), 'create') === false ? 'active' : '' ?>">
                <i class="bi bi-list-ul"></i>
                <span>Items</span>
            </a>
            <a href="<?= base_url('inventory/items/create') ?>" class="nav-item <?= strpos(current_url(), 'inventory/items/create') !== false ? 'active' : '' ?>">
                <i class="bi bi-plus-circle"></i>
                <span>Add Item</span>
            </a>
            
            <div class="nav-section">Master Data</div>
            
            <a href="<?= base_url('inventory/categories') ?>" class="nav-item <?= strpos(current_url(), 'inventory/categories') !== false ? 'active' : '' ?>">
                <i class="bi bi-folder"></i>
                <span>Categories</span>
            </a>
            <a href="<?= base_url('inventory/locations') ?>" class="nav-item <?= strpos(current_url(), 'inventory/locations') !== false ? 'active' : '' ?>">
                <i class="bi bi-geo-alt"></i>
                <span>Locations</span>
            </a>
            
            <div class="nav-section">Operations</div>
            
            <a href="<?= base_url('inventory/movements') ?>" class="nav-item <?= strpos(current_url(), 'inventory/movements') !== false ? 'active' : '' ?>">
                <i class="bi bi-arrow-left-right"></i>
                <span>Movements</span>
            </a>
            <a href="<?= base_url('inventory/stock-opname') ?>" class="nav-item <?= strpos(current_url(), 'inventory/stock-opname') !== false ? 'active' : '' ?>">
                <i class="bi bi-clipboard-data"></i>
                <span>Stock Opname</span>
            </a>
            <a href="<?= base_url('inventory/alerts') ?>" class="nav-item <?= strpos(current_url(), 'inventory/alerts') !== false ? 'active' : '' ?>">
                <i class="bi bi-exclamation-triangle"></i>
                <span>Alerts</span>
            </a>
            
            <div class="nav-section">Reports</div>
            
            <a href="<?= base_url('inventory/reports/summary') ?>" class="nav-item <?= strpos(current_url(), 'inventory/reports') !== false ? 'active' : '' ?>">
                <i class="bi bi-bar-chart"></i>
                <span>Summary</span>
            </a>
            
            <div class="nav-section">System</div>
            
            <a href="<?= base_url('/') ?>" class="nav-item">
                <i class="bi bi-house"></i>
                <span>Back to Dashboard</span>
            </a>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="inventory-main">
        <!-- Header -->
        <div class="inventory-header">
            <h4>
                <i class="bi bi-<?= $icon ?? 'box-seam' ?>"></i>
                <?= $pageTitle ?? 'Inventory' ?>
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
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
