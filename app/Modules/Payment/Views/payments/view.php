<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    .payment-header {
        background: linear-gradient(to right, #8B0000, #6B0000);
        color: white;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .info-label {
        font-weight: bold;
        color: #8B0000;
    }
</style>

<div class="container-fluid">
    <div class="payment-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-0">Detail Pembayaran #<?= esc($payment['id']) ?></h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?= base_url('payment/receipt/' . $payment['id']) ?>" class="btn btn-light" target="_blank">
                    <i class="bi bi-printer"></i> Cetak Kwitansi
                </a>
                <a href="<?= base_url('payment/edit/' . $payment['id']) ?>" class="btn btn-light">Edit</a>
                <a href="<?= base_url('payment') ?>" class="btn btn-outline-light">Kembali</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header" style="background-color: #8B0000; color: white;">
                    <h5 class="mb-0">Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="info-label">Jumlah:</span> Rp <?= number_format($payment['amount'], 0, ',', '.') ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Metode Pembayaran:</span> <?= ucwords(str_replace('_', ' ', $payment['payment_method'])) ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Nomor Dokumen:</span> <?= esc($payment['document_number']) ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Tgl. Pembayaran:</span> <?= date('F d, Y', strtotime($payment['payment_date'])) ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Status:</span>
                        <span class="badge bg-<?= $payment['status'] === 'paid' ? 'success' : ($payment['status'] === 'pending' ? 'warning' : 'danger') ?>">
                            <?= ucfirst($payment['status']) ?>
                        </span>
                    </div>
                    <?php if ($payment['notes']): ?>
                        <div class="mb-2">
                            <span class="info-label">Keterangan:</span><br>
                            <?= nl2br(esc((string)$payment['notes'])) ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>

            <?php if (!empty($payment['receipt_file'])): ?>
                <div class="card mb-3">
                    <div class="card-header" style="background-color: #8B0000; color: white;">
                        <h5 class="mb-0">Bukti Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $filePath = WRITEPATH . 'uploads/' . $payment['receipt_file'];
                        $fileUrl = base_url('uploads/' . $payment['receipt_file']);
                        $fileExt = strtolower(pathinfo($payment['receipt_file'], PATHINFO_EXTENSION));
                        ?>

                        <div class="mb-3">
                            <span class="info-label">File Name:</span> <?= basename($payment['receipt_file']) ?>
                        </div>

                        <?php if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                            <!-- Image Preview -->
                            <div class="text-center mb-3">
                                <img src="<?= $fileUrl ?>"
                                    class="img-fluid rounded border"
                                    style="max-height: 400px;"
                                    alt="Receipt">
                            </div>
                        <?php elseif ($fileExt === 'pdf'): ?>
                            <!-- PDF Preview -->
                            <div class="mb-3">
                                <embed src="<?= $fileUrl ?>"
                                    type="application/pdf"
                                    width="100%"
                                    height="500px"
                                    class="border rounded">
                            </div>
                        <?php endif; ?>

                        <div class="d-grid gap-2">
                            <a href="<?= $fileUrl ?>"
                                target="_blank"
                                class="btn btn-outline-secondary">
                                <i class="bi bi-download"></i> Unduh Bukti Pembayaran
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header" style="background-color: #8B0000; color: white;">
                    <h5 class="mb-0">Informasi Siswa</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="info-label">Nama Siswa:</span> <?= esc($payment['student']['full_name'] ?? 'N/A') ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">No. Registrasi:</span> <?= esc($payment['registration_number']) ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Email:</span> <?= esc($payment['student']['email'] ?? 'N/A') ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Telp:</span> <?= esc($payment['student']['phone'] ?? 'N/A') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>