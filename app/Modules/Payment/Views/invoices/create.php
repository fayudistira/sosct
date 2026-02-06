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

    .line-item-row {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        align-items: flex-end;
    }

    .line-item-row .form-group {
        flex: 1;
        margin-bottom: 0;
    }

    .line-item-row .form-group.amount {
        flex: 0 0 150px;
    }

    .line-item-row .btn-danger {
        flex: 0 0 auto;
        padding: 6px 12px;
        height: 38px;
    }

    .items-table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .items-table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #ddd;
        padding: 10px;
        text-align: left;
        font-weight: 600;
    }

    .items-table td {
        border-bottom: 1px solid #ddd;
        padding: 10px;
    }

    .total-row {
        background-color: #fff3cd;
        font-weight: 600;
    }

    .total-row td {
        padding: 12px 10px;
    }
</style>

<div class="container-fluid">
    <div class="invoice-header">
        <h3 class="mb-0">Create Invoice</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('invoice/store') ?>" method="post" id="invoiceForm">
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
                    <label class="form-label">Invoice Type *</label>
                    <select name="invoice_type" class="form-select" required>
                        <option value="">Select Type</option>
                        <option value="registration_fee">Registration Fee</option>
                        <option value="tuition_fee">Tuition Fee</option>
                        <option value="miscellaneous_fee">Miscellaneous Fee</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Due Date *</label>
                    <input type="date" name="due_date" class="form-control" required>
                </div>

                <!-- Line Items Section -->
                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Invoice Line Items</h5>
                    </div>
                    <div class="card-body">
                        <div id="lineItemsContainer">
                            <!-- Line items will be added here -->
                        </div>

                        <button type="button" class="btn btn-sm btn-primary" id="addLineItem">
                            <i class="bi bi-plus"></i> Add Line Item
                        </button>
                    </div>
                </div>

                <!-- Items Summary Table -->
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th style="width: 150px; text-align: right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="itemsPreview">
                        <!-- Preview will be shown here -->
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td style="text-align: right;">Total Amount:</td>
                            <td style="text-align: right;">
                                <span id="totalAmount">0.00</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= base_url('invoice') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-invoice">Create Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let lineItemCount = 0;

    function addLineItem() {
        lineItemCount++;
        const itemId = 'item_' + lineItemCount;

        const html = `
            <div class="line-item-row" data-item-id="${itemId}">
                <div class="form-group flex-grow-1">
                    <input type="text" name="items[${lineItemCount}][description]" 
                           class="form-control item-description" placeholder="Item description" required>
                </div>
                <div class="form-group amount">
                    <input type="number" name="items[${lineItemCount}][amount]" 
                           class="form-control item-amount" placeholder="0.00" step="0.01" min="0" required>
                </div>
                <button type="button" class="btn btn-danger btn-sm removeLineItem">Remove</button>
            </div>
        `;

        document.getElementById('lineItemsContainer').insertAdjacentHTML('beforeend', html);

        // Add event listeners to the new inputs
        const descInput = document.querySelector(`[name="items[${lineItemCount}][description]"]`);
        const amountInput = document.querySelector(`[name="items[${lineItemCount}][amount]"]`);

        descInput.addEventListener('input', updateSummary);
        amountInput.addEventListener('input', updateSummary);

        // Add remove button listener
        document.querySelector(`[data-item-id="${itemId}"] .removeLineItem`).addEventListener('click', function() {
            document.querySelector(`[data-item-id="${itemId}"]`).remove();
            updateSummary();
        });
    }

    function updateSummary() {
        const descriptions = document.querySelectorAll('.item-description');
        const amounts = document.querySelectorAll('.item-amount');

        let total = 0;
        let previewHtml = '';

        descriptions.forEach((desc, index) => {
            if (desc.value.trim()) {
                const amount = parseFloat(amounts[index].value) || 0;
                total += amount;

                previewHtml += `
                    <tr>
                        <td>${escapeHtml(desc.value)}</td>
                        <td style="text-align: right;">${amount.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    </tr>
                `;
            }
        });

        document.getElementById('itemsPreview').innerHTML = previewHtml;
        document.getElementById('totalAmount').textContent = total.toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Event delegation for remove buttons
    document.getElementById('lineItemsContainer').addEventListener('click', function(e) {
        if (e.target.classList.contains('removeLineItem')) {
            e.preventDefault();
            e.target.closest('.line-item-row').remove();
            updateSummary();
        }
    });

    // Add click listener for add button
    document.getElementById('addLineItem').addEventListener('click', function(e) {
        e.preventDefault();
        addLineItem();
    });

    // Form validation
    document.getElementById('invoiceForm').addEventListener('submit', function(e) {
        const lineItems = document.querySelectorAll('.line-item-row');
        if (lineItems.length === 0) {
            e.preventDefault();
            alert('Please add at least one line item');
            return false;
        }

        // Check if all items have description and amount
        const allValid = Array.from(lineItems).every(item => {
            const desc = item.querySelector('.item-description').value.trim();
            const amount = item.querySelector('.item-amount').value;
            return desc && amount;
        });

        if (!allValid) {
            e.preventDefault();
            alert('All line items must have description and amount');
            return false;
        }
    });

    // Initialize with one empty line item
    addLineItem();
</script>
<?= $this->endSection() ?>