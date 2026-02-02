<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    .invoice-header {
        background: linear-gradient(to right, #8B0000, #6B0000);
        color: white;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .info-label { font-weight: bold; color: #8B0000; }
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
                <a href="<?= base_url('invoice/edit/' . $invoice['id']) ?>" class="btn btn-light">Edit</a>
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
                        <span class="info-label">Amount:</span> $<?= number_format($invoice['amount'], 2) ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Due Date:</span> <?= date('F d, Y', strtotime($invoice['due_date'])) ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Status:</span> 
                        <span class="badge bg-<?= $invoice['status'] === 'paid' ? 'success' : ($invoice['status'] === 'unpaid' ? 'warning' : 'secondary') ?>">
                            <?= ucfirst($invoice['status']) ?>
                        </span>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Description:</span><br>
                        <?= nl2br(esc($invoice['description'])) ?>
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
                                <td>$<?= number_format($payment['amount'], 2) ?></td>
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
<?= $this->endSection() ?>
