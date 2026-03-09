<?= $this->extend('Modules\Inventory\Views\layouts\main') ?>

<?= $this->section('content') ?>
        <h4><i class="bi bi-geo-alt me-2"></i>Inventory Locations</h4>
        <a href="/inventory/locations/create" class="btn btn-primary mb-3">Add Location</a>
        <div class="card">
            <div class="card-body">
                <?php if(empty($locations)): ?>
                <p class="text-muted">No locations found</p>
                <?php else: ?>
                <table class="table">
                    <thead><tr><th>Name</th><th>Type</th><th>Address</th><th>Default</th></tr></thead>
                    <tbody>
                        <?php foreach($locations as $loc): ?>
                        <tr>
                            <td><?= $loc['name'] ?></td>
                            <td><?= ucfirst($loc['type']) ?></td>
                            <td><?= $loc['address'] ?? '-' ?></td>
                            <td><?= $loc['is_default'] ? 'Yes' : '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    <?= $this->endSection() ?>
