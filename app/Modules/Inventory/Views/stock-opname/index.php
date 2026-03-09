<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <h4><i class="bi bi-clipboard-data me-2"></i>Stock Opname</h4>
        <a href="/inventory/stock-opname/create" class="btn btn-primary mb-3">New Opname</a>
        <div class="card">
            <div class="card-body">
                <p class="text-muted">Stock opname sessions will appear here</p>
            </div>
        </div>
    <?= $this->endSection() ?>
