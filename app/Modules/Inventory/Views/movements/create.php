<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>Record Stock Movement</h4>
            <a href="/inventory/movements" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="post" action="/inventory/movements/store">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Item</label>
                                <select name="item_id" id="itemSelect" class="form-select" required onchange="updateCurrentStock()">
                                    <option value="">Select Item</option>
                                    <?php foreach($items as $item): ?>
                                    <option value="<?= $item['id'] ?>" data-stock="<?= $item['current_stock'] ?>"><?= $item['item_code'] ?> - <?= $item['name'] ?> (Stock: <?= $item['current_stock'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Movement Type</label>
                                <select name="movement_type" id="movementType" class="form-select" onchange="toggleLocationFields()">
                                    <option value="purchase">Purchase</option>
                                    <option value="return">Return</option>
                                    <option value="sale">Sale</option>
                                    <option value="distributed">Distributed</option>
                                    <option value="adjustment">Adjustment</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="damage">Damage</option>
                                    <option value="expired">Expired</option>
                                    <option value="initial">Initial Stock</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Transfer Location Fields (hidden by default) -->
                    <div id="transferFields" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">From Location (Source)</label>
                                    <select name="source_location_id" class="form-select">
                                        <option value="">Select Source Location</option>
                                        <?php foreach($locations as $loc): ?>
                                        <option value="<?= $loc['id'] ?>"><?= $loc['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">To Location (Destination)</label>
                                    <select name="to_location_id" class="form-select">
                                        <option value="">Select Destination Location</option>
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
                                    <label class="form-label">Quantity</label>
                                    <input type="number" name="quantity" id="quantityInput" class="form-control" required min="1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Reference Number</label>
                                    <input type="text" name="reference_number" class="form-control" placeholder="Invoice #, PO #, etc.">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transfer Quantity Field -->
                    <div id="transferQuantityField" style="display: none;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Quantity to Transfer</label>
                                    <input type="number" name="quantity" id="transferQuantity" class="form-control" min="1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Reference Number</label>
                                    <input type="text" name="reference_number" class="form-control" placeholder="Transfer #, etc.">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description / Notes</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="text-end">
                        <a href="/inventory/movements" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Record Movement
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
        </script>
    <?= $this->endSection() ?>
