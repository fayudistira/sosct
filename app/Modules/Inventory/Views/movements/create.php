<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>Catat Mutasi Stok</h4>
            <a href="/inventory/movements" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="post" action="/inventory/movements/store">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Barang</label>
                                <select name="item_id" id="itemSelect" class="form-select" required onchange="updateCurrentStock()">
                                    <option value="">Pilih Barang</option>
                                    <?php foreach($items as $item): ?>
                                    <option value="<?= $item['id'] ?>" data-stock="<?= $item['current_stock'] ?>"><?= $item['item_code'] ?> - <?= $item['name'] ?> (Stock: <?= $item['current_stock'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipe Mutasi</label>
                                <select name="movement_type" id="movementType" class="form-select" onchange="toggleLocationFields()">
                                    <option value="purchase">Pembelian</option>
                                    <option value="return">Retur</option>
                                    <option value="sale">Penjualan</option>
                                    <option value="distributed">Distribusi</option>
                                    <option value="adjustment">Penyesuaian</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="damage">Kerusakan</option>
                                    <option value="expired">Kedaluwarsa</option>
                                    <option value="initial">Stok Awal</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Transfer Location Fields (hidden by default) -->
                    <div id="transferFields" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Lokasi Asal</label>
                                    <select name="source_location_id" class="form-select">
                                        <option value="">Pilih Lokasi Asal</option>
                                        <?php foreach($locations as $loc): ?>
                                        <option value="<?= $loc['id'] ?>"><?= $loc['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Lokasi Tujuan</label>
                                    <select name="to_location_id" class="form-select">
                                        <option value="">Pilih Lokasi Tujuan</option>
                                        <?php foreach($locations as $loc): ?>
                                        <option value="<?= $loc['id'] ?>"><?= $loc['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Single Location Field (shown for non-transfer types) -->
                    <div id="singleLocationField">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Lokasi</label>
                                    <select name="location_id" class="form-select">
                                        <option value="">Pilih Lokasi</option>
                                        <?php foreach($locations as $loc): ?>
                                        <option value="<?= $loc['id'] ?>"><?= $loc['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Jumlah</label>
                                    <input type="number" name="quantity_regular" id="quantityInput" class="form-control" required min="1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nomor Referensi</label>
                                    <input type="text" name="reference_number" class="form-control" placeholder="No. Invoice, PO, dll.">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transfer Quantity Field -->
                    <div id="transferQuantityField" style="display: none;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Jumlah yang Ditransfer</label>
                                    <input type="number" name="quantity_transfer" id="transferQuantity" class="form-control" required min="1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nomor Referensi</label>
                                    <input type="text" name="reference_number" class="form-control" placeholder="No. Transfer, dll.">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="text-end">
                        <a href="/inventory/movements" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Catat Mutasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function toggleLocationFields() {
                const movementType = document.getElementById('movementType').value;
                const transferFields = document.getElementById('transferFields');
                const singleLocationField = document.getElementById('singleLocationField');
                const transferQuantityField = document.getElementById('transferQuantityField');
                const quantityInput = document.getElementById('quantityInput');
                const transferQuantity = document.getElementById('transferQuantity');
                
                if (movementType === 'transfer') {
                    transferFields.style.display = 'block';
                    singleLocationField.style.display = 'none';
                    transferQuantityField.style.display = 'block';
                    
                    // Make transfer quantity required, regular quantity not
                    quantityInput.removeAttribute('required');
                    transferQuantity.setAttribute('required', 'required');
                } else {
                    transferFields.style.display = 'none';
                    singleLocationField.style.display = 'block';
                    transferQuantityField.style.display = 'none';
                    
                    // Make regular quantity required, transfer quantity not
                    quantityInput.setAttribute('required', 'required');
                    transferQuantity.removeAttribute('required');
                }
            }
            
            // Dummy function - can be expanded to show current stock
            function updateCurrentStock() {
                const itemSelect = document.getElementById('itemSelect');
                const selectedOption = itemSelect.options[itemSelect.selectedIndex];
                const stock = selectedOption.getAttribute('data-stock');
                // Could display stock somewhere if needed
                console.log('Selected item stock:', stock);
            }
        </script>
    <?= $this->endSection() ?>
