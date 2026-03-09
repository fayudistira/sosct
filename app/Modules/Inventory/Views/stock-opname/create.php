<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-clipboard-plus me-2"></i>New Stock Opname</h4>
            <a href="/inventory/stock-opname" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="post" action="/inventory/stock-opname/store">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Location</label>
                                <select name="location_id" class="form-select">
                                    <option value="">All Locations</option>
                                    <?php foreach($locations as $loc): ?>
                                    <option value="<?= $loc['id'] ?>"><?= $loc['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Performed By</label>
                                <input type="text" name="performed_by" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Purpose of stock opname, date range, etc."></textarea>
                    </div>

                    <div class="text-end">
                        <a href="/inventory/stock-opname" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-play-circle me-1"></i> Start Opname
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?= $this->endSection() ?>
