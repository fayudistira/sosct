<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
<h4><i class="bi bi-arrow-left-right me-2"></i>Stock Movements</h4>
<a href="/inventory/movements/create" class="btn btn-primary mb-3">Record Movement</a>

<!-- Filter Form -->
<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="/inventory/movements" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Item</label>
                <select name="item" class="form-select">
                    <option value="">All Items</option>
                    <?php foreach ($allItems as $item): ?>
                        <option value="<?= $item['id'] ?>" <?= $selectedItem == $item['id'] ? 'selected' : '' ?>>
                            <?= esc($item['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <?php foreach ($types as $typeKey => $typeLabel): ?>
                        <option value="<?= $typeKey ?>" <?= $selectedType == $typeKey ? 'selected' : '' ?>>
                            <?= esc($typeLabel) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?= $startDate ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?= $endDate ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <a href="/inventory/movements" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Movements Table -->
<div class="card">
    <div class="card-body">
        <?php if (!empty($movements)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Before</th>
                            <th>After</th>
                            <th>Location</th>
                            <th>Transfer Details</th>
                            <th>Reference</th>
                            <th>Performed By</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movements as $movement): ?>
                            <tr>
                                <td><?= date('d M Y H:i', strtotime($movement['movement_date'])) ?></td>
                                <td><?= esc($items[$movement['item_id']] ?? 'Unknown') ?></td>
                                <td>
                                    <?php 
                                    $typeClass = '';
                                    switch($movement['movement_type']) {
                                        case 'in':
                                            $typeClass = 'badge bg-success';
                                            break;
                                        case 'out':
                                            $typeClass = 'badge bg-danger';
                                            break;
                                        case 'transfer':
                                            $typeClass = 'badge bg-info';
                                            break;
                                        case 'adjustment':
                                            $typeClass = 'badge bg-warning';
                                            break;
                                        default:
                                            $typeClass = 'badge bg-secondary';
                                    }
                                    ?>
                                    <span class="<?= $typeClass ?>"><?= esc(ucfirst($movement['movement_type'])) ?></span>
                                </td>
                                <td><?= $movement['quantity'] >= 0 ? '+' : '' ?><?= $movement['quantity'] ?></td>
                                <td><?= $movement['quantity_before'] ?></td>
                                <td><?= $movement['quantity_after'] ?></td>
                                <td><?= esc($locations[$movement['location_id']] ?? $movement['location_id'] ?? '-') ?></td>
                                <td>
                                    <?php if ($movement['movement_type'] === 'transfer' && !empty($movement['source_location_id'])): ?>
                                        <span class="text-info">From: <?= esc($locations[$movement['source_location_id']] ?? $movement['source_location_id'] ?? '-') ?></span><br>
                                        <span class="text-success">To: <?= esc($locations[$movement['to_location_id']] ?? $movement['to_location_id'] ?? '-') ?></span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($movement['reference_number'] ?? '-') ?></td>
                                <td><?= esc($movement['performed_by'] ?? '-') ?></td>
                                <td><?= esc($movement['description'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($pager): ?>
                <div class="mt-3">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-muted">Stock movements will appear here</p>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
