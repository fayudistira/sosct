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
                <h3 class="mb-0">Kontrak - <?= esc($installment['registration_number']) ?></h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?= base_url('contract/print/' . $installment['registration_number']) ?>"
                    class="btn btn-light" target="_blank">
                    <i class="bi bi-printer"></i> Cetak
                </a>
                <a href="<?= base_url('contract') ?>"
                    class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
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

    <!-- Student Info -->
    <div class="card mb-3">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-person"></i> Informasi Siswa</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-2">
                        <label class="text-muted small">Nama Lengkap</label>
                        <div class="fw-bold"><?= esc($admission['full_name'] ?? $installment['full_name']) ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-2">
                        <label class="text-muted small">Email</label>
                        <div><?= esc($admission['email'] ?? $installment['email']) ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-2">
                        <label class="text-muted small">Program</label>
                        <div class="fw-bold"><?= esc($admission['program_title'] ?? $installment['program_title']) ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-2">
                        <label class="text-muted small">Kategori</label>
                        <div><?= esc($installment['category'] ?? 'N/A') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Summary -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Total Kontrak</h5>
                </div>
                <div class="card-body">
                    <h3 class="text-primary">Rp <?= number_format($installment['total_contract_amount'], 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Sudah Dibayar</h5>
                </div>
                <div class="card-body">
                    <h3 class="text-success">Rp <?= number_format($totalPaid, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Sisa Pembayaran</h5>
                </div>
                <div class="card-body">
                    <h3 class="text-warning">Rp <?= number_format($remainingBalance, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    <div class="card mb-3">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-credit-card"></i> Riwayat Pembayaran</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($paidPayments)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No. Dokumen</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($paidPayments as $payment): ?>
                                <tr>
                                    <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                                    <td><?= esc($payment['document_number'] ?? 'N/A') ?></td>
                                    <td><?= ucfirst(str_replace('_', ' ', $payment['payment_method'])) ?></td>
                                    <td class="text-success fw-bold">+Rp <?= number_format($payment['amount'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge bg-success">Lunas</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mt-2">Belum ada pembayaran tercatat</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Aksi</h5>
                    <div class="d-flex gap-2">
                        <?php if ($remainingBalance > 0): ?>
                            <a href="<?= base_url('payment/create?registration_number=' . $installment['registration_number']) ?>"
                                class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Catat Pembayaran
                            </a>
                        <?php endif; ?>

                        <?php if ($latestInvoice): ?>
                            <a href="<?= base_url('invoice/view/' . $latestInvoice['id']) ?>"
                                class="btn btn-primary">
                                <i class="bi bi-file-text"></i> Lihat Faktur
                            </a>
                        <?php endif; ?>

                        <a href="<?= base_url('admission/view/' . $admission['id'] ?? '') ?>"
                            class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Pendaftaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>