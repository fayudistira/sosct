<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    .contract-header {
        background: linear-gradient(to right, #198754, #146c43);
        color: white;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .btn-contract {
        background: linear-gradient(to right, #198754, #146c43);
        color: white;
        border: none;
    }

    .btn-contract:hover {
        background: linear-gradient(to right, #146c43, #198754);
        color: white;
    }
</style>

<div class="container-fluid">
    <div class="contract-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-0">Kontrak</h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?= base_url('contract') ?>" class="btn btn-light">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </a>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <!-- Search and Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="get" action="<?= base_url('contract') ?>">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari kontrak..." value="<?= esc($keyword ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="unpaid" <?= ($status ?? '') === 'unpaid' ? 'selected' : '' ?>>Belum Dibayar</option>
                            <option value="partial" <?= ($status ?? '') === 'partial' ? 'selected' : '' ?>>Dibayar Sebagian</option>
                            <option value="paid" <?= ($status ?? '') === 'paid' ? 'selected' : '' ?>>Lunas</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-contract w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Contracts Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Kontrak</th>
                            <th>Siswa</th>
                            <th>Program</th>
                            <th>Total Kontrak</th>
                            <th>Total Dibayar</th>
                            <th>Sisa</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($contracts)): ?>
                            <?php foreach ($contracts as $contract): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($contract['registration_number']) ?></strong>
                                    </td>
                                    <td>
                                        <?= esc($contract['full_name']) ?><br>
                                        <small class="text-muted"><?= esc($contract['email'] ?? '') ?></small>
                                    </td>
                                    <td><?= esc($contract['program_title']) ?></td>
                                    <td>Rp <?= number_format($contract['total_contract_amount'], 0, ',', '.') ?></td>
                                    <td class="text-success">Rp <?= number_format($contract['total_paid'], 0, ',', '.') ?></td>
                                    <td class="text-danger">Rp <?= number_format($contract['remaining_balance'], 0, ',', '.') ?></td>
                                    <td><?= date('M d, Y', strtotime($contract['due_date'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?php
                                                                if ($contract['status'] === 'paid') echo 'success';
                                                                elseif ($contract['status'] === 'partial') echo 'info';
                                                                else echo 'warning';
                                                                ?>">
                                            <?= str_replace('_', ' ', ucfirst($contract['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('contract/view/' . $contract['registration_number']) ?>"
                                            class="btn btn-sm btn-success" title="Lihat Kontrak">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url('contract/print/' . $contract['registration_number']) ?>"
                                            class="btn btn-sm btn-danger" target="_blank" title="Cetak">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada kontrak ditemukan</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="mt-3">
                    <nav aria-label="Contract pagination">
                        <ul class="pagination justify-content-center mb-0">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url('contract?page=' . ($page - 1) . '&status=' . ($status ?? '') . '&keyword=' . ($keyword ?? '')) ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= base_url('contract?page=' . $i . '&status=' . ($status ?? '') . '&keyword=' . ($keyword ?? '')) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url('contract?page=' . ($page + 1) . '&status=' . ($status ?? '') . '&keyword=' . ($keyword ?? '')) ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <div class="text-center mt-2 text-muted">
                    Showing <?= count($contracts) ?> of <?= $total ?> contracts
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>