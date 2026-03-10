<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-box-seam me-2"></i>Barang Inventaris</h4>
            <div class="d-flex gap-2">
                <div class="text-end">
                    <small class="text-muted d-block">Scan untuk Stok Opname</small>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= urlencode('/inventory/items/barcode/85502f0f-f604-465e-be65-f2367f59b6ec') ?>" alt="QR Code" class="border rounded">
                </div>
                <a href="/inventory/items/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Barang
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Cari barang..." value="<?= $search ?? '' ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            <?php foreach($categoryList ?? [] as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($selectedCategory ?? '') == $cat['id'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="location" class="form-select">
                            <option value="">Semua Lokasi</option>
                            <?php foreach($locationList ?? [] as $loc): ?>
                            <option value="<?= $loc['id'] ?>" <?= ($selectedLocation ?? '') == $loc['id'] ? 'selected' : '' ?>><?= $loc['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="active" <?= ($selectedStatus ?? '') == 'active' ? 'selected' : '' ?>>Aktif</option>
                            <option value="inactive" <?= ($selectedStatus ?? '') == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                            <option value="discontinued" <?= ($selectedStatus ?? '') == 'discontinued' ? 'selected' : '' ?>>Discontinue</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter me-1"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Items Table -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th class="text-end">Stok</th>
                                <th class="text-end">Min Stok</th>
                                <th class="text-end">Harga</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($items)): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">Tidak ada barang ditemukan</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach($items as $item): ?>
                            <tr>
                                <td><code><?= $item['item_code'] ?></code></td>
                                <td><?= $item['name'] ?></td>
                                <td><?= $item['category_id'] ? $categories[$item['category_id']]['name'] ?? '-' : '-' ?></td>
                                <td><?= $item['location_id'] ? $locations[$item['location_id']]['name'] ?? '-' : '-' ?></td>
                                <td class="text-end">
                                    <?php if($item['current_stock'] <= $item['minimum_stock']): ?>
                                    <span class="badge badge-low-stock"><?= $item['current_stock'] ?></span>
                                    <?php else: ?>
                                    <span class="badge badge-ok"><?= $item['current_stock'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end"><?= $item['minimum_stock'] ?></td>
                                <td class="text-end"><?= number_format($item['selling_price'], 2) ?></td>
                                <td>
                                    <?php if($item['status'] == 'active'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                    <?php elseif($item['status'] == 'inactive'): ?>
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                    <?php else: ?>
                                    <span class="badge bg-danger">Discontinue</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <a href="/inventory/items/view/<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                    <a href="/inventory/items/edit/<?= $item['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                    <a href="/inventory/items/barcode/<?= $item['id'] ?>" class="btn btn-sm btn-outline-info"><i class="bi bi-upc"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if(isset($pager) && $pager->getPageCount() > 1): ?>
        <div class="mt-4">
            <?= $pager->links() ?>
        </div>
        <?php endif; ?>
    <?= $this->endSection() ?>
