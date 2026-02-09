<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Settings</h4>
        <p class="text-muted mb-0">Application settings and test data management</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-trash display-4 text-danger"></i>
                <h5 class="card-title mt-3">Cleanup Test Data</h5>
                <p class="card-text text-muted">Clear all admission-related data for fresh testing</p>
                <a href="<?= base_url('settings/cleanup') ?>" class="btn btn-outline-danger">
                    <i class="bi bi-arrow-right me-1"></i>Go to Cleanup
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-database display-4 text-primary"></i>
                <h5 class="card-title mt-3">Generate Test Data</h5>
                <p class="card-text text-muted">Create sample data for testing purposes</p>
                <a href="<?= base_url('settings/test-data') ?>" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-right me-1"></i>Go to Test Data
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-bar-chart display-4 text-success"></i>
                <h5 class="card-title mt-3">Database Stats</h5>
                <p class="card-text text-muted">View current data counts in all tables</p>
                <button class="btn btn-outline-success" disabled>
                    <i class="bi bi-arrow-right me-1"></i>View Stats
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Table Stats -->
<div class="row mt-4">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-table me-2"></i>Database Table Statistics
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Table Name</th>
                                <th>Record Count</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tables as $table => $count): ?>
                                <tr>
                                    <td><?= esc($table) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $count > 0 ? 'warning' : 'success' ?>">
                                            <?= number_format($count) ?> records
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($count > 0): ?>
                                            <i class="bi bi-exclamation-triangle text-warning"></i> Has data
                                        <?php else: ?>
                                            <i class="bi bi-check-circle text-success"></i> Empty
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>