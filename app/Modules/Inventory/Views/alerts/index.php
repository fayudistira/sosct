<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <h4><i class="bi bi-exclamation-triangle me-2"></i>Peringatan Inventaris</h4>
        <div class="card">
            <div class="card-body">
                <p class="text-muted">Peringatan aktif akan muncul di sini</p>
            </div>
        </div>
    <?= $this->endSection() ?>
