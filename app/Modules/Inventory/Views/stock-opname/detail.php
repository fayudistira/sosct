<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Detail Stock Opname</h4>
    <a href="/inventory/stock-opname" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<!-- Opname Header -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <strong>Nomor Opname:</strong><br>
                <?= esc($opname['opname_number']) ?>
            </div>
            <div class="col-md-3">
                <strong>Lokasi:</strong><br>
                <?= esc($location['name'] ?? 'Semua Lokasi') ?>
            </div>
            <div class="col-md-3">
                <strong>Status:</strong><br>
                <?php 
                $statusClass = '';
                switch($opname['status']) {
                    case 'draft': $statusClass = 'badge bg-secondary'; break;
                    case 'in_progress': $statusClass = 'badge bg-primary'; break;
                    case 'completed': $statusClass = 'badge bg-success'; break;
                    case 'cancelled': $statusClass = 'badge bg-danger'; break;
                }
                ?>
                <span class="<?= $statusClass ?>"><?= ucfirst(str_replace('_', ' ', $opname['status'])) ?></span>
            </div>
            <div class="col-md-3">
                <strong>Dilakukan Oleh:</strong><br>
                <?= esc($opname['performed_by'] ?? '-') ?>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Total Items</h5>
                <h2><?= $summary['total'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-success">
            <div class="card-body">
                <h5 class="card-title text-success">Sesuai</h5>
                <h2 class="text-success"><?= $summary['matched'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-warning">
            <div class="card-body">
                <h5 class="card-title text-warning">Menunggu</h5>
                <h2 class="text-warning"><?= $summary['pending'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-danger">
            <div class="card-body">
                <h5 class="card-title text-danger">Selisih</h5>
                <h2 class="text-danger"><?= $summary['discrepancy'] ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<?php if ($opname['status'] === 'draft' || $opname['status'] === 'in_progress'): ?>
<div class="mb-3">
    <form method="post" action="/inventory/stock-opname/start/<?= $opname['id'] ?>" style="display:inline;">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-play-fill me-1"></i> Mulai Opname
        </button>
    </form>
    <form method="post" action="/inventory/stock-opname/complete/<?= $opname['id'] ?>" style="display:inline;">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-success">
            <i class="bi bi-check-circle me-1"></i> Selesaikan Opname
        </button>
    </form>
</div>
<?php endif; ?>

<!-- Details Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Kode Item</th>
                        <th>Nama Item</th>
                        <th>Kategori</th>
                        <th>Stok Sistem</th>
                        <th>Stok Fisik</th>
                        <th>Selisih</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($details)): ?>
                        <?php foreach ($details as $detail): ?>
                            <tr>
                                <td><?= esc($items[$detail['item_id']]['item_code'] ?? '-') ?></td>
                                <td><?= esc($items[$detail['item_id']]['name'] ?? 'Tidak Diketahui') ?></td>
                                <td><?= esc($items[$detail['item_id']]['category_id'] ?? '-') ?></td>
                                <td><?= $detail['system_stock'] ?></td>
                                <td>
                                    <?php if ($opname['status'] !== 'completed'): ?>
                                        <form method="post" action="/inventory/stock-opname/update-detail/<?= $detail['id'] ?>" class="d-flex">
                                            <?= csrf_field() ?>
                                            <input type="number" name="physical_stock" class="form-control form-control-sm" value="<?= $detail['physical_stock'] ?>" min="0" style="width: 80px;">
                                            <button type="submit" class="btn btn-sm btn-outline-primary ms-1">Update</button>
                                        </form>
                                    <?php else: ?>
                                        <?= $detail['physical_stock'] ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($detail['difference'] > 0): ?>
                                        <span class="text-success">+<?= $detail['difference'] ?></span>
                                    <?php elseif ($detail['difference'] < 0): ?>
                                        <span class="text-danger"><?= $detail['difference'] ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">0</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                    $detailStatusClass = '';
                                    switch($detail['status']) {
                                        case 'matched': $detailStatusClass = 'badge bg-success'; break;
                                        case 'discrepancy': $detailStatusClass = 'badge bg-danger'; break;
                                        case 'pending': $detailStatusClass = 'badge bg-warning'; break;
                                    }
                                    ?>
                                    <span class="<?= $detailStatusClass ?>"><?= ucfirst($detail['status']) ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($detail['notes'])): ?>
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="<?= esc($detail['notes']) ?>">
                                            <i class="bi bi-info-circle"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tidak ada item untuk stock opname ini</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
