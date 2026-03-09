<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-box-seam me-2"></i>Inventory Items</h4>
            <a href="/inventory/items/create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Item
            </a>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search items..." value="<?= $search ?? '' ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($selectedCategory ?? '') == $cat['id'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="location" class="form-select">
                            <option value="">All Locations</option>
                            <?php foreach($locations as $loc): ?>
                            <option value="<?= $loc['id'] ?>" <?= ($selectedLocation ?? '') == $loc['id'] ? 'selected' : '' ?>><?= $loc['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" <?= ($selectedStatus ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($selectedStatus ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            <option value="discontinued" <?= ($selectedStatus ?? '') == 'discontinued' ? 'selected' : '' ?>>Discontinued</option>
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
                                <th>Code</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Location</th>
                                <th class="text-end">Stock</th>
                                <th class="text-end">Min Stock</th>
                                <th class="text-end">Price</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($items)): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No items found</td>
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
                                    <span class="badge bg-success">Active</span>
                                    <?php elseif($item['status'] == 'inactive'): ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                    <?php else: ?>
                                    <span class="badge bg-danger">Discontinued</span>
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
