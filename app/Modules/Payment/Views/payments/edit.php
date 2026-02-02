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
    .btn-payment {
        background: linear-gradient(to right, #8B0000, #6B0000);
        color: white;
        border: none;
    }
    .btn-payment:hover {
        background: linear-gradient(to right, #6B0000, #8B0000);
        color: white;
    }
</style>

<div class="container-fluid">
    <div class="payment-header">
        <h3 class="mb-0">Edit Payment #<?= esc($payment['id']) ?></h3>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('payment/update/' . $payment['id']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Student *</label>
                            <select name="registration_number" class="form-select" required>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?= esc($student['registration_number']) ?>" 
                                            <?= $student['registration_number'] === $payment['registration_number'] ? 'selected' : '' ?>>
                                        <?= esc($student['full_name']) ?> (<?= esc($student['registration_number']) ?>)
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Amount *</label>
                            <input type="number" name="amount" class="form-control" step="0.01" 
                                   value="<?= esc($payment['amount']) ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Payment Method *</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="cash" <?= $payment['payment_method'] === 'cash' ? 'selected' : '' ?>>Cash</option>
                                <option value="bank_transfer" <?= $payment['payment_method'] === 'bank_transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Document Number *</label>
                            <input type="text" name="document_number" class="form-control" 
                                   value="<?= esc($payment['document_number']) ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Payment Date *</label>
                            <input type="date" name="payment_date" class="form-control" 
                                   value="<?= esc($payment['payment_date']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" <?= $payment['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="paid" <?= $payment['status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                                <option value="failed" <?= $payment['status'] === 'failed' ? 'selected' : '' ?>>Failed</option>
                                <option value="refunded" <?= $payment['status'] === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Receipt File</label>
                            <?php if (!empty($payment['receipt_file'])): ?>
                                <div class="mb-2">
                                    <a href="<?= base_url('writable/uploads/' . $payment['receipt_file']) ?>" 
                                       target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-file-earmark"></i> View Current Receipt
                                    </a>
                                    <small class="text-muted d-block mt-1">Current file: <?= basename($payment['receipt_file']) ?></small>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="receipt_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Upload new receipt file (PDF, JPG, PNG - Max 2MB). Leave empty to keep current file.</small>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3"><?= esc($payment['notes'] ?? '') ?></textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('payment') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-payment">Update Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
