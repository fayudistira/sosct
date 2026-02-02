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
    .info-label { font-weight: bold; color: #8B0000; }
</style>

<div class="container-fluid">
    <div class="payment-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-0">Payment Details #<?= esc($payment['id']) ?></h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?= base_url('payment/edit/' . $payment['id']) ?>" class="btn btn-light">Edit</a>
                <a href="<?= base_url('payment') ?>" class="btn btn-outline-light">Back to List</a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header" style="background-color: #8B0000; color: white;">
                    <h5 class="mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="info-label">Amount:</span> $<?= number_format($payment['amount'], 2) ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Payment Method:</span> <?= ucwords(str_replace('_', ' ', $payment['payment_method'])) ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Document Number:</span> <?= esc($payment['document_number']) ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Payment Date:</span> <?= date('F d, Y', strtotime($payment['payment_date'])) ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Status:</span> 
                        <span class="badge bg-<?= $payment['status'] === 'paid' ? 'success' : ($payment['status'] === 'pending' ? 'warning' : 'danger') ?>">
                            <?= ucfirst($payment['status']) ?>
                        </span>
                    </div>
                    <?php if ($payment['notes']): ?>
                        <div class="mb-2">
                            <span class="info-label">Notes:</span><br>
                            <?= nl2br(esc($payment['notes'])) ?>
                        </div>
                    <?php endif ?>
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
                        <span class="info-label">Name:</span> <?= esc($payment['student']['full_name'] ?? 'N/A') ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Registration Number:</span> <?= esc($payment['registration_number']) ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Email:</span> <?= esc($payment['student']['email'] ?? 'N/A') ?>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Phone:</span> <?= esc($payment['student']['phone'] ?? 'N/A') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
