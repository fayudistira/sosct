<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <h4><i class="bi bi-bar-chart me-2"></i>Ringkasan Inventaris</h4>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5><?= $totalItems ?? 0 ?></h5>
                        <p class="mb-0">Total Barang</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5><?= $totalStock ?? 0 ?></h5>
                        <p class="mb-0">Total Stok</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5><?= $lowStockCount ?? 0 ?></h5>
                        <p class="mb-0">Stok Rendah</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5><?= number_format($totalValue ?? 0, 0) ?></h5>
                        <p class="mb-0">Total Nilai</p>
                    </div>
                </div>
            </div>
        </div>
    <?= $this->endSection() ?>
