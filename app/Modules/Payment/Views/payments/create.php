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
        <h3 class="mb-0">Create Payment</h3>
    </div>
    
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>
    
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('payment/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Student *</label>
                            <select name="registration_number" class="form-select" required>
                                <option value="">Select Student</option>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?= esc($student['registration_number']) ?>">
                                        <?= esc($student['full_name']) ?> (<?= esc($student['registration_number']) ?>)
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Invoice (Optional)</label>
                            <select name="invoice_id" class="form-select">
                                <option value="">No Invoice</option>
                                <?php foreach ($invoices as $invoice): ?>
                                    <option value="<?= esc($invoice['id']) ?>">
                                        <?= esc($invoice['invoice_number']) ?> - $<?= number_format($invoice['amount'], 2) ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Amount *</label>
                            <input type="number" name="amount" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Payment Method *</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">Select Method</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Document Number *</label>
                            <input type="text" name="document_number" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Payment Date *</label>
                            <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Receipt File (PDF, JPG, PNG - Max 2MB)</label>
                            <input type="file" name="receipt_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('payment') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-payment">Create Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
