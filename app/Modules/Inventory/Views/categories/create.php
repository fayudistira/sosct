<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-folder-plus me-2"></i>Add New Category</h4>
            <a href="/inventory/categories" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="post" action="/inventory/categories/store">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Parent Category</label>
                        <select name="parent_id" class="form-select">
                            <option value="">No Parent</option>
                            <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>

                    <div class="text-end">
                        <a href="/inventory/categories" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?= $this->endSection() ?>
