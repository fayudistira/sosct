<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Cleanup Test Data</h4>
        <p class="text-muted mb-0">Clear all admission-related data for fresh testing</p>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('settings') ?>" class="btn btn-outline-dark-red">
            <i class="bi bi-arrow-left me-1"></i> Back to Settings
        </a>
    </div>
</div>

<?php if (session('results')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>
        <strong>Cleanup Complete!</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <div class="card mb-4">
        <div class="card-header">Cleanup Results</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Table</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (session('results') as $table => $result): ?>
                            <tr>
                                <td><?= esc($table) ?></td>
                                <td>
                                    <?php if ($result['success']): ?>
                                        <span class="badge bg-success">Cleared</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Error</span>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <?php if ($result['success']): ?>
                                        <?= number_format($result['count']) ?> rows deleted
                                    <?php else: ?>
                                        <?= esc($result['error']) ?>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Warning: This will DELETE data!</h5>
            </div>
            <div class="card-body">
                <p class="lead">This action will <strong>permanently delete</strong> all data from:</p>

                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                profiles
                                <span class="badge bg-secondary rounded-pill"><?= number_format($tables['profiles'] ?? 0) ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                admissions
                                <span class="badge bg-secondary rounded-pill"><?= number_format($tables['admissions'] ?? 0) ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                invoices
                                <span class="badge bg-secondary rounded-pill"><?= number_format($tables['invoices'] ?? 0) ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                payments
                                <span class="badge bg-secondary rounded-pill"><?= number_format($tables['payments'] ?? 0) ?></span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                students
                                <span class="badge bg-secondary rounded-pill"><?= number_format($tables['students'] ?? 0) ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                conversations
                                <span class="badge bg-secondary rounded-pill"><?= number_format($tables['conversations'] ?? 0) ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                messages
                                <span class="badge bg-secondary rounded-pill"><?= number_format($tables['messages'] ?? 0) ?></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong> Users table (login accounts) and programs table will NOT be affected.
                    Uploaded files in <code>uploads/profiles/</code> will also be deleted.
                </div>

                <form method="post">
                    <div class="mb-3">
                        <label for="confirm" class="form-label">Type "DELETE" to confirm:</label>
                        <input type="text" class="form-control" id="confirm" name="confirm" placeholder="DELETE" required>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash me-2"></i>Delete All Test Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>