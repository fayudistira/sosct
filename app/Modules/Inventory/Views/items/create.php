<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add New Item</h4>
            <a href="/inventory/items" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="post" action="/inventory/items/store">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Item Code</label>
                                <input type="text" name="item_code" class="form-control" value="<?= $itemCode ?? '' ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Barcode</label>
                                <input type="text" name="barcode" class="form-control" placeholder="Auto-generate if empty">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select">
                                    <option value="">Select Category</option>
                                    <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Location</label>
                                <select name="location_id" class="form-select">
                                    <option value="">Select Location</option>
                                    <?php foreach($locations as $loc): ?>
                                    <option value="<?= $loc['id'] ?>"><?= $loc['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Linked Program</label>
                                <select name="program_id" class="form-select">
                                    <option value="">No Program</option>
                                    <?php foreach($programs as $prog): ?>
                                    <option value="<?= $prog['id'] ?>"><?= $prog['title'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Unit</label>
                                <select name="unit" class="form-select">
                                    <?php foreach($units as $key => $val): ?>
                                    <option value="<?= $key ?>"><?= $val ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Current Stock</label>
                                <input type="number" name="current_stock" class="form-control" value="0" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Minimum Stock</label>
                                <input type="number" name="minimum_stock" class="form-control" value="0" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Maximum Stock</label>
                                <input type="number" name="maximum_stock" class="form-control" value="0" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Purchase Price</label>
                                <input type="number" name="purchase_price" class="form-control" step="0.01" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Selling Price</label>
                                <input type="number" name="selling_price" class="form-control" step="0.01" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="discontinued">Discontinued</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Supplier ID</label>
                                <input type="text" name="supplier_id" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Supplier Name</label>
                                <input type="text" name="supplier_name" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="/inventory/items" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?= $this->endSection() ?>
