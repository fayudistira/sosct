<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<!-- QRCode.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<style>
    .invoice-header {
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
    <div class="invoice-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-0">Invoice <?= esc($invoice['invoice_number']) ?></h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?= base_url('invoice/pdf/' . $invoice['id']) ?>" class="btn btn-light" target="_blank">
                    <i class="bi bi-file-pdf"></i> Download PDF
                </a>
                <?php if (in_array($invoice['status'], ['unpaid', 'expired'])): ?>
                    <a href="<?= base_url('invoice/cancel/' . $invoice['id']) ?>" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to cancel this invoice? This action cannot be undone.')">
                        <i class="bi bi-x-circle"></i> Cancel Invoice
                    </a>
                <?php endif; ?>
                <a href="<?= base_url('invoice') ?>" class="btn btn-outline-light">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header" style="background-color: #8B0000; color: white;">
                    <h5 class="mb-0">Invoice Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="info-label">Invoice Number:</span> <?= esc($invoice['invoice_number']) ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Type:</span> <?= ucwords(str_replace('_', ' ', $invoice['invoice_type'])) ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Amount:</span> Rp <?= number_format($invoice['amount'], 0, ',', '.') ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Due Date:</span> <?= date('F d, Y', strtotime($invoice['due_date'])) ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Status:</span>
                        <span class="badge bg-<?php
                                                if ($invoice['status'] === 'paid') echo 'success';
                                                elseif ($invoice['status'] === 'partially_paid') echo 'info';
                                                elseif ($invoice['status'] === 'unpaid') echo 'warning';
                                                elseif ($invoice['status'] === 'expired') echo 'danger';
                                                else echo 'secondary';
                                                ?>">
                            <?= str_replace('_', ' ', ucfirst($invoice['status'])) ?>
                        </span>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Description:</span><br>
                        <?= nl2br(esc((string)($invoice['description'] ?? ''))) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header" style="background-color: #8B0000; color: white;">
                    <h5 class="mb-0">Student Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="info-label">Name:</span> <?= esc($invoice['student']['full_name'] ?? 'N/A') ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Registration Number:</span> <?= esc($invoice['registration_number']) ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Email:</span> <?= esc($invoice['student']['email'] ?? 'N/A') ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Phone:</span> <?= esc($invoice['student']['phone'] ?? 'N/A') ?>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header" style="background-color: #8B0000; color: white;">
                    <h5 class="mb-0">Share Invoice</h5>
                </div>
                <div class="card-body text-center">
                    <p class="small text-muted mb-2">Scan QR code to view invoice publicly</p>
                    <div id="qrcode" style="display: inline-block;"></div>
                    <div class="mt-2">
                        <a href="<?= base_url('invoice/public/' . $invoice['id']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-box-arrow-up-right"></i> Public View
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($invoice['payments'])): ?>
        <div class="card">
            <div class="card-header" style="background-color: #8B0000; color: white;">
                <h5 class="mb-0">Associated Payments</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invoice['payments'] as $payment): ?>
                            <tr>
                                <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                                <td>Rp <?= number_format($payment['amount'], 0, ',', '.') ?></td>
                                <td><?= ucwords(str_replace('_', ' ', $payment['payment_method'])) ?></td>
                                <td>
                                    <span class="badge bg-<?= $payment['status'] === 'paid' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($payment['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif ?>
</div>

<script>
    // Generate QR Code when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const invoiceUrl = '<?= base_url('invoice/public/' . $invoice['id']) ?>';

        new QRCode(document.getElementById('qrcode'), {
            text: invoiceUrl,
            width: 200,
            height: 200,
            colorDark: '#8B0000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    });
</script>
<?= $this->endSection() ?>