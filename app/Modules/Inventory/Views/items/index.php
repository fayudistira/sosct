<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-box-seam me-2"></i>Barang Inventaris</h4>
            <div class="d-flex gap-2 flex-wrap">
                <a href="/inventory/items/upload" class="btn btn-success btn-sm d-none d-md-inline-flex">
                    <i class="bi bi-upload me-1"></i> Unggah Massal
                </a>
                <a href="/inventory/items/upload" class="btn btn-success btn-sm d-md-none">
                    <i class="bi bi-upload"></i>
                </a>
                <a href="/inventory/items/create" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1 d-none d-md-inline"></i>
                    <i class="bi bi-plus d-md-none"></i>
                    <span class="d-none d-md-inline">Tambah Barang</span>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" class="row g-3">
                    <div class="col-12 col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Cari barang..." value="<?= $search ?? '' ?>">
                    </div>
                    <div class="col-6 col-md-2">
                        <select name="category" class="form-select">
                            <option value="">Kategori</option>
                            <?php foreach($categoryList ?? [] as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($selectedCategory ?? '') == $cat['id'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <select name="location" class="form-select">
                            <option value="">Lokasi</option>
                            <?php foreach($locationList ?? [] as $loc): ?>
                            <option value="<?= $loc['id'] ?>" <?= ($selectedLocation ?? '') == $loc['id'] ? 'selected' : '' ?>><?= $loc['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Status</option>
                            <option value="active" <?= ($selectedStatus ?? '') == 'active' ? 'selected' : '' ?>>Aktif</option>
                            <option value="inactive" <?= ($selectedStatus ?? '') == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                            <option value="discontinued" <?= ($selectedStatus ?? '') == 'discontinued' ? 'selected' : '' ?>>Discontinue</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="bi bi-filter me-1 d-none d-md-inline"></i>
                            <i class="bi bi-filter d-md-none"></i>
                            <span class="d-none d-md-inline">Filter</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Items Table -->
        <div class="card">
            <div class="card-body p-0">
                <!-- Mobile View -->
                <div class="d-md-none">
                    <?php if(empty($items)): ?>
                        <div class="text-center py-4 text-muted">Tidak ada barang ditemukan</div>
                    <?php else: ?>
                        <?php foreach($items as $item): ?>
                            <div class="border-bottom p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1"><code><?= $item['item_code'] ?></code></h6>
                                        <p class="mb-1 fw-medium"><?= $item['name'] ?></p>
                                        <small class="text-muted">
                                            <?= $item['category_id'] ? $categories[$item['category_id']]['name'] ?? '-' : '-' ?> |
                                            <?= $item['location_id'] ? $locations[$item['location_id']]['name'] ?? '-' : '-' ?>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <div class="mb-1">
                                            <?php if($item['current_stock'] <= $item['minimum_stock']): ?>
                                            <span class="badge badge-low-stock fs-6">Stok: <?= $item['current_stock'] ?></span>
                                            <?php else: ?>
                                            <span class="badge badge-ok fs-6">Stok: <?= $item['current_stock'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-muted d-block">Min: <?= $item['minimum_stock'] ?></small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">Harga: Rp <?= number_format($item['selling_price'], 0, ',', '.') ?></small>
                                        <?php if($item['status'] == 'active'): ?>
                                        <span class="badge bg-success ms-2">Aktif</span>
                                        <?php elseif($item['status'] == 'inactive'): ?>
                                        <span class="badge bg-secondary ms-2">Tidak Aktif</span>
                                        <?php else: ?>
                                        <span class="badge bg-danger ms-2">Discontinue</span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <a href="/inventory/items/view/<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/inventory/items/edit/<?= $item['id'] ?>" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/inventory/items/barcode/<?= $item['id'] ?>" class="btn btn-sm btn-outline-info" target="_blank">
                                            <i class="bi bi-qr-code"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Desktop Table View -->
                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <?php
                                    $currentSort = $sort ?? 'created_at';
                                    $currentOrder = $order ?? 'desc';
                                    $newOrder = ($currentSort === 'item_code' && $currentOrder === 'asc') ? 'desc' : 'asc';
                                    ?>
                                    <th><a href="?sort=item_code&order=<?= $currentSort === 'item_code' ? $newOrder : 'asc' ?>&search=<?= $search ?? '' ?>&category=<?= $selectedCategory ?? '' ?>&location=<?= $selectedLocation ?? '' ?>&status=<?= $selectedStatus ?? '' ?>" class="text-decoration-none <?= $currentSort === 'item_code' ? 'text-primary' : 'text-muted' ?>">Kode <?= $currentSort === 'item_code' ? ($currentOrder === 'asc' ? '↑' : '↓') : '' ?></a></th>
                                    <th><a href="?sort=name&order=<?= $currentSort === 'name' ? $newOrder : 'asc' ?>&search=<?= $search ?? '' ?>&category=<?= $selectedCategory ?? '' ?>&location=<?= $selectedLocation ?? '' ?>&status=<?= $selectedStatus ?? '' ?>" class="text-decoration-none <?= $currentSort === 'name' ? 'text-primary' : 'text-muted' ?>">Nama <?= $currentSort === 'name' ? ($currentOrder === 'asc' ? '↑' : '↓') : '' ?></a></th>
                                    <th>Kategori</th>
                                    <th>Lokasi</th>
                                    <th class="text-end"><a href="?sort=current_stock&order=<?= $currentSort === 'current_stock' ? $newOrder : 'asc' ?>&search=<?= $search ?? '' ?>&category=<?= $selectedCategory ?? '' ?>&location=<?= $selectedLocation ?? '' ?>&status=<?= $selectedStatus ?? '' ?>" class="text-decoration-none <?= $currentSort === 'current_stock' ? 'text-primary' : 'text-muted' ?>">Stok <?= $currentSort === 'current_stock' ? ($currentOrder === 'asc' ? '↑' : '↓') : '' ?></a></th>
                                    <th class="text-end">Min Stok</th>
                                    <th class="text-end"><a href="?sort=selling_price&order=<?= $currentSort === 'selling_price' ? $newOrder : 'asc' ?>&search=<?= $search ?? '' ?>&category=<?= $selectedCategory ?? '' ?>&location=<?= $selectedLocation ?? '' ?>&status=<?= $selectedStatus ?? '' ?>" class="text-decoration-none <?= $currentSort === 'selling_price' ? 'text-primary' : 'text-muted' ?>">Harga <?= $currentSort === 'selling_price' ? ($currentOrder === 'asc' ? '↑' : '↓') : '' ?></a></th>
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
                                    <td class="text-end">Rp <?= number_format($item['selling_price'], 0, ',', '.') ?></td>
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
                                        <div class="btn-group" role="group">
                                            <a href="/inventory/items/view/<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="/inventory/items/edit/<?= $item['id'] ?>" class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="/inventory/items/barcode/<?= $item['id'] ?>" class="btn btn-sm btn-outline-info" target="_blank">
                                                <i class="bi bi-qr-code"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>

        <?php if(isset($pager) && $pager->getPageCount() > 1): ?>
        <div class="mt-4">
            <?= $pager->links() ?>
        </div>
        <?php endif; ?>
    <?= $this->endSection() ?>
