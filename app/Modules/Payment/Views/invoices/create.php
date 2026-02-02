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
        <h3 class="mb-0">Create Invoice</h3>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('invoice/store') ?>" method="post">
                <?= csrf_field() ?>
                
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
                
                <div class="mb-3">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
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
                            <label class="form-label">Due Date *</label>
                            <input type="date" name="due_date" class="form-control" required>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Invoice Type *</label>
                    <select name="invoice_type" class="form-select" required>
                        <option value="">Select Type</option>
                        <option value="registration_fee">Registration Fee</option>
                        <option value="tuition_fee">Tuition Fee</option>
                        <option value="miscellaneous_fee">Miscellaneous Fee</option>
                    </select>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('invoice') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-invoice">Create Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
