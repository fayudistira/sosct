<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-box-seam display-4 text-success"></i>
                        <h5 class="mt-3">Items</h5>
                        <p class="text-muted">Manage inventory items, track stock levels, and item details</p>
                        <a href="/inventory/items" class="btn btn-outline-success">View Items</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-folder display-4 text-primary"></i>
                        <h5 class="mt-3">Categories</h5>
                        <p class="text-muted">Organize items into categories for better management</p>
                        <a href="/inventory/categories" class="btn btn-outline-primary">View Categories</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-geo-alt display-4 text-info"></i>
                        <h5 class="mt-3">Locations</h5>
                        <p class="text-muted">Manage storage locations and warehouses</p>
                        <a href="/inventory/locations" class="btn btn-outline-info">View Locations</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-arrow-left-right display-4 text-warning"></i>
                        <h5 class="mt-3">Movements</h5>
                        <p class="text-muted">Track stock movements and inventory changes</p>
                        <a href="/inventory/movements" class="btn btn-outline-warning">View Movements</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-clipboard-data display-4 text-secondary"></i>
                        <h5 class="mt-3">Stock Opname</h5>
                        <p class="text-muted">Conduct physical inventory checks and audits</p>
                        <a href="/inventory/stock-opname" class="btn btn-outline-secondary">View Opnames</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-exclamation-triangle display-4 text-danger"></i>
                        <h5 class="mt-3">Alerts</h5>
                        <p class="text-muted">Monitor low stock and other inventory alerts</p>
                        <a href="/inventory/alerts" class="btn btn-outline-danger">View Alerts</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-bar-chart display-4 text-dark"></i>
                        <h5 class="mt-3">Reports</h5>
                        <p class="text-muted">View inventory summary and analytics reports</p>
                        <a href="/inventory/reports/summary" class="btn btn-outline-dark">View Reports</a>
                    </div>
                </div>
            </div>
        </div>
    <?= $this->endSection() ?>
