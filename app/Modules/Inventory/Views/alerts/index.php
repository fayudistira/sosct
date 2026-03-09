<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <h4><i class="bi bi-exclamation-triangle me-2"></i>Inventory Alerts</h4>
        <div class="card">
            <div class="card-body">
                <p class="text-muted">Active alerts will appear here</p>
            </div>
        </div>
    <?= $this->endSection() ?>
