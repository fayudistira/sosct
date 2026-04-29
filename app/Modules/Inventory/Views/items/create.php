<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Barang Baru</h4>
            <a href="/inventory/items" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1 d-none d-md-inline"></i>
                <i class="bi bi-arrow-left d-md-none"></i>
                <span class="d-none d-md-inline">Kembali</span>
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="post" action="/inventory/items/store" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" name="item_code" class="form-control" value="<?= $itemCode ?? '' ?>" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Barcode</label>
                                <input type="text" name="barcode" class="form-control" placeholder="Otomatis dibuat jika kosong">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="category_id" class="form-select">
                                    <option value="">Pilih Kategori</option>
                                    <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
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
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Program Terintegrasi</label>
                                <select name="program_id" class="form-select">
                                    <option value="">Tanpa Program</option>
                                    <?php foreach($programs as $prog): ?>
                                    <option value="<?= $prog['id'] ?>"><?= $prog['title'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Satuan</label>
                                <select name="unit" class="form-select">
                                    <?php foreach($units as $key => $val): ?>
                                    <option value="<?= $key ?>"><?= $val ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Stok Saat Ini</label>
                                <input type="number" name="current_stock" class="form-control" value="0" min="0">
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Stok Minimum</label>
                                <input type="number" name="minimum_stock" class="form-control" value="0" min="0">
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Stok Maksimum</label>
                                <input type="number" name="maximum_stock" class="form-control" value="0" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Harga Beli</label>
                                <input type="number" name="purchase_price" class="form-control" step="0.01" value="0">
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Harga Jual</label>
                                <input type="number" name="selling_price" class="form-control" step="0.01" value="0">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Tidak Aktif</option>
                                    <option value="discontinued">Discontinue</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">ID Supplier</label>
                                <input type="text" name="supplier_id" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Supplier</label>
                                <input type="text" name="supplier_name" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Barang</label>
                        <div class="d-flex gap-2 mb-2 flex-wrap">
                            <input type="file" name="pictures[]" id="camera-input" class="form-control" accept="image/*" capture="environment" style="display: none;">
                            <input type="file" name="pictures[]" id="gallery-input" class="form-control" multiple accept="image/*" style="display: none;">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('camera-input').click()">
                                <i class="bi bi-camera me-1"></i> Ambil Foto
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('gallery-input').click()">
                                <i class="bi bi-images me-1"></i> Pilih dari Galeri
                            </button>
                        </div>
                        <div class="form-text">
                            Ambil foto langsung dari kamera atau pilih multiple gambar dari galeri perangkat. Maksimal 10 gambar.
                        </div>
                        <div id="image-preview" class="mt-3 d-flex flex-wrap gap-2"></div>
                    </div>

                    <div class="text-end">
                        <a href="/inventory/items" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Simpan Barang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const fileInput = document.querySelector('input[name="pictures[]"]');
                const previewContainer = document.getElementById('image-preview');

                fileInput.addEventListener('change', function(e) {
                    previewContainer.innerHTML = '';

                    Array.from(e.target.files).forEach((file, index) => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const imgContainer = document.createElement('div');
                                imgContainer.className = 'position-relative';
                                imgContainer.innerHTML = `
                                    <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="removeImage(${index})">
                                        <i class="bi bi-x"></i>
                                    </button>
                                `;
                                previewContainer.appendChild(imgContainer);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                });

                window.removeImage = function(index) {
                    const dt = new DataTransfer();
                    const files = Array.from(fileInput.files);
                    files.splice(index, 1);
                    files.forEach(file => dt.items.add(file));
                    fileInput.files = dt.files;

                    // Trigger change event to update preview
                    fileInput.dispatchEvent(new Event('change'));
                };
            });
        </script>
    <?= $this->endSection() ?>
