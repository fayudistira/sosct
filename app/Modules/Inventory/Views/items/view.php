<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-box-seam me-2"></i>Detail Barang</h4>
            <div class="d-flex gap-2 flex-wrap">
                <a href="/inventory/items/barcode/<?= $item['id'] ?>" class="btn btn-info btn-sm" target="_blank">
                    <i class="bi bi-qr-code me-1 d-none d-md-inline"></i>
                    <i class="bi bi-qr-code d-md-none"></i>
                    <span class="d-none d-md-inline">QR Code</span>
                </a>
                <a href="/inventory/items/edit/<?= $item['id'] ?>" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil me-1 d-none d-md-inline"></i>
                    <i class="bi bi-pencil d-md-none"></i>
                    <span class="d-none d-md-inline">Edit</span>
                </a>
                <a href="/inventory/items" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1 d-none d-md-inline"></i>
                    <i class="bi bi-arrow-left d-md-none"></i>
                    <span class="d-none d-md-inline">Kembali</span>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informasi Barang</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="200"><strong>Kode Barang</strong></td>
                                <td><?= $item['item_code'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Barcode</strong></td>
                                <td><?= $item['barcode'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nama Barang</strong></td>
                                <td><?= $item['name'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Deskripsi</strong></td>
                                <td><?= $item['description'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Kategori</strong></td>
                                <td><?= $category['name'] ?? 'Tidak ada' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Lokasi</strong></td>
                                <td><?= $location['name'] ?? 'Tidak ada' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Satuan</strong></td>
                                <td><?= $item['unit'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>
                                    <?php if(isset($item['status'])): ?>
                                        <?php if($item['status'] == 'active'): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php elseif($item['status'] == 'inactive'): ?>
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Discontinue</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php
                $hasPictures = !empty($item['pictures']);
                $hasThumbnail = !empty($item['thumbnail']);
                if($hasPictures || $hasThumbnail):
                ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Foto Barang</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 g-md-3">
                            <?php if($hasPictures): ?>
                                <?php foreach(json_decode($item['pictures'], true) as $picture): ?>
                                    <div class="col-6 col-md-3">
                                        <div class="position-relative">
                                            <img src="<?= base_url($picture) ?>"
                                                 class="img-fluid rounded shadow-sm"
                                                 style="width: 100%; height: 120px; object-fit: cover; cursor: pointer;"
                                                 onclick="openImageModal('<?= base_url($picture) ?>')"
                                                 alt="Item Picture">
                                            <button class="btn btn-sm btn-outline-primary position-absolute top-50 start-50 translate-middle d-none d-md-block"
                                                    onclick="openImageModal('<?= base_url($picture) ?>')"
                                                    style="background: rgba(255,255,255,0.8); border: none;">
                                                <i class="bi bi-zoom-in"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php elseif($hasThumbnail): ?>
                                <div class="col-6 col-md-3">
                                    <div class="position-relative">
                                        <img src="<?= base_url($item['thumbnail']) ?>"
                                             class="img-fluid rounded shadow-sm"
                                             style="width: 100%; height: 120px; object-fit: cover; cursor: pointer;"
                                             onclick="openImageModal('<?= base_url($item['thumbnail']) ?>')"
                                             alt="Item Thumbnail">
                                        <button class="btn btn-sm btn-outline-primary position-absolute top-50 start-50 translate-middle d-none d-md-block"
                                                onclick="openImageModal('<?= base_url($item['thumbnail']) ?>')"
                                                style="background: rgba(255,255,255,0.8); border: none;">
                                            <i class="bi bi-zoom-in"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Riwayat Mutasi</h5>
                    </div>
                    <div class="card-body">
                        <?php if(empty($movements)): ?>
                            <p class="text-muted">Belum ada riwayat mutasi untuk barang ini.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Tipe</th>
                                            <th>Jumlah</th>
                                            <th>Sebelum</th>
                                            <th>Sesudah</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($movements as $m): ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i', strtotime($m['movement_date'])) ?></td>
                                            <td><?= ucfirst($m['movement_type']) ?></td>
                                            <td class="<?= $m['quantity'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                <?= $m['quantity'] > 0 ? '+' : '' ?><?= $m['quantity'] ?>
                                            </td>
                                            <td><?= $m['quantity_before'] ?></td>
                                            <td><?= $m['quantity_after'] ?></td>
                                            <td><?= $m['description'] ?? '-' ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 mt-4 mt-lg-0">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Stok</h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="mb-0"><?= $item['current_stock'] ?? 0 ?></h2>
                        <p class="text-muted"><?= $item['unit'] ?? 'unit' ?></p>
                        <hr>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="text-muted small">Minimum</div>
                                <strong><?= $item['minimum_stock'] ?? 0 ?></strong>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small">Maksimum</div>
                                <strong><?= $item['maximum_stock'] ?? 0 ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Harga</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td>Harga Beli</td>
                                <td class="text-end">Rp <?= number_format($item['purchase_price'] ?? 0, 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td>Harga Jual</td>
                                <td class="text-end">Rp <?= number_format($item['selling_price'] ?? 0, 0, ',', '.') ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if(!empty($item['supplier_name'])): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Supplier</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong><?= $item['supplier_name'] ?></strong></p>
                        <p class="mb-0 text-muted"><?= $item['supplier_id'] ?? '' ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <img id="modalImage" src="" class="img-fluid w-100" alt="Full Size Image">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openImageModal(imageSrc) {
                document.getElementById('modalImage').src = imageSrc;
                const modal = new bootstrap.Modal(document.getElementById('imageModal'));
                modal.show();
            }
        </script>
    <?= $this->endSection() ?>
