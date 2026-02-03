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
    .btn-invoice {
        background: linear-gradient(to right, #8B0000, #6B0000);
        color: white;
        border: none;
    }
</style>

<div class="container-fluid">
    <div class="invoice-header">
        <h3 class="mb-0">Edit Invoice <?= esc($invoice['invoice_number']) ?></h3>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('invoice/update/' . $invoice['id']) ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label class="form-label">Student *</label>
                    <select name="registration_number" class="form-select" required>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= esc($student['registration_number']) ?>" 
                                    <?= $student['registration_number'] === $invoice['registration_number'] ? 'selected' : '' ?>>
                                <?= esc($student['full_name']) ?> (<?= esc($student['registration_number']) ?>)
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-control" rows="3" required><?= esc($invoice['description']) ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Amount *</label>
                            <input type="number" name="amount" class="form-control" step="0.01" 
                                   value="<?= esc($invoice['amount']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Due Date *</label>
                            <input type="date" name="due_date" class="form-control" 
                                   value="<?= esc($invoice['due_date']) ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Invoice Type *</label>
                            <select name="invoice_type" class="form-select" required>
                                <option value="registration_fee" <?= $invoice['invoice_type'] === 'registration_fee' ? 'selected' : '' ?>>Registration Fee</option>
                                <option value="tuition_fee" <?= $invoice['invoice_type'] === 'tuition_fee' ? 'selected' : '' ?>>Tuition Fee</option>
                                <option value="miscellaneous_fee" <?= $invoice['invoice_type'] === 'miscellaneous_fee' ? 'selected' : '' ?>>Miscellaneous Fee</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <?php 
                                $isLocked = in_array($invoice['status'], ['paid', 'cancelled']);
                            ?>
                            <select name="status" class="form-select" <?= $isLocked ? 'disabled' : '' ?>>
                                <?php if ($invoice['status'] === 'unpaid'): ?>
                                    <option value="unpaid" selected>Unpaid</option>
                                    <option value="cancelled">Cancelled</option>
                                <?php elseif ($invoice['status'] === 'paid'): ?>
                                    <option value="paid" selected>Paid</option>
                                <?php elseif ($invoice['status'] === 'cancelled'): ?>
                                    <option value="cancelled" selected>Cancelled</option>
                                <?php endif; ?>
                            </select>
                            <?php if ($isLocked): ?>
                                <input type="hidden" name="status" value="<?= esc($invoice['status']) ?>">
                            <?php endif; ?>
                            <div class="form-text">
                                <?= $isLocked ? 'This status is locked and cannot be changed.' : 'Status can only be changed to Cancelled. Paid status occurs automatically upon payment.' ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('invoice') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-invoice">Update Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
