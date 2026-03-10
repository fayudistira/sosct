<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-box-seam display-4 text-success"></i>
                        <h5 class="mt-3">Barang</h5>
                        <p class="text-muted">Kelola barang inventaris, lacak tingkat stok, dan detail barang</p>
                        <a href="/inventory/items" class="btn btn-outline-success">Lihat Barang</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-folder display-4 text-primary"></i>
                        <h5 class="mt-3">Kategori</h5>
                        <p class="text-muted">Kelola barang ke dalam kategori untuk manajemen yang lebih baik</p>
                        <a href="/inventory/categories" class="btn btn-outline-primary">Lihat Kategori</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-geo-alt display-4 text-info"></i>
                        <h5 class="mt-3">Lokasi</h5>
                        <p class="text-muted">Kelola lokasi penyimpanan dan gudang</p>
                        <a href="/inventory/locations" class="btn btn-outline-info">Lihat Lokasi</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-arrow-left-right display-4 text-warning"></i>
                        <h5 class="mt-3">Mutasi</h5>
                        <p class="text-muted">Lacak pergerakan stok dan perubahan inventaris</p>
                        <a href="/inventory/movements" class="btn btn-outline-warning">Lihat Mutasi</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-clipboard-data display-4 text-secondary"></i>
                        <h5 class="mt-3">Stock Opname</h5>
                        <p class="text-muted">Lakukan pengecekan fisik inventaris dan audit</p>
                        <a href="/inventory/stock-opname" class="btn btn-outline-secondary">Lihat Opname</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-exclamation-triangle display-4 text-danger"></i>
                        <h5 class="mt-3">Peringatan</h5>
                        <p class="text-muted">Pantau stok rendah dan peringatan inventaris lainnya</p>
                        <a href="/inventory/alerts" class="btn btn-outline-danger">Lihat Peringatan</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-bar-chart display-4 text-dark"></i>
                        <h5 class="mt-3">Laporan</h5>
                        <p class="text-muted">Lihat ringkasan inventaris dan laporan analitik</p>
                        <a href="/inventory/reports/summary" class="btn btn-outline-dark">Lihat Laporan</a>
                    </div>
                </div>
            </div>
        </div>
    <?= $this->endSection() ?>
