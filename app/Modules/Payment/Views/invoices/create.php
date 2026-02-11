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

    <!-- Invoice Creation Option -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title mb-3">Invoice Creation Option</h5>
            <div class="btn-group w-100" role="group">
                <input type="radio" class="btn-check" name="invoiceOption" id="optionNew" value="new" checked>
                <label class="btn btn-outline-primary" for="optionNew">
                    <i class="bi bi-plus-circle"></i> Create New Invoice
                </label>

                <input type="radio" class="btn-check" name="invoiceOption" id="optionExtend" value="extend">
                <label class="btn btn-outline-warning" for="optionExtend">
                    <i class="bi bi-arrow-repeat"></i> Extend Previous Invoice
                </label>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('invoice/store') ?>" method="post" id="invoiceForm">
                <?= csrf_field() ?>

                <!-- New Invoice Section (Shown by default) -->
                <div id="newInvoiceSection">
                    <div class="mb-3">
                        <label class="form-label">Student *</label>
                        <select name="registration_number" class="form-select" id="newStudentSelect">
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
                        <select name="invoice_type" class="form-select">
                            <option value="">Select Type</option>
                            <option value="registration_fee">Registration Fee</option>
                            <option value="tuition_fee">Tuition Fee</option>
                            <option value="miscellaneous_fee">Miscellaneous Fee</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Due Date *</label>
                        <input type="date" name="due_date" class="form-control" id="newDueDate">
                    </div>
                </div>

                <!-- Extend Invoice Section (Hidden by default) -->
                <div id="extendInvoiceSection" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Student *</label>
                        <select name="registration_number" class="form-select" id="studentSelect">
                            <option value="">Select Student First</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?= esc($student['registration_number']) ?>">
                                    <?= esc($student['full_name']) ?> (<?= esc($student['registration_number']) ?>)
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="mb-3" id="invoiceSelectContainer" style="display: none;">
                        <label class="form-label">Select Invoice to Extend *</label>
                        <select name="invoice_id" class="form-select" id="invoiceSelect">
                            <option value="">Select Invoice</option>
                        </select>
                    </div>

                    <input type="hidden" name="invoice_type" id="extendInvoiceType" value="">

                    <div class="mb-3" id="dueDateContainer" style="display: none;">
                        <label class="form-label">Due Date *</label>
                        <input type="date" name="due_date" class="form-control" id="extendDueDate">
                    </div>

                    <div id="selectedInvoiceDetails" class="alert alert-info" style="display: none;">
                        <!-- Invoice details will be loaded here -->
                    </div>
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

    // Handle option selection
    document.querySelectorAll('input[name="invoiceOption"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const newSection = document.getElementById('newInvoiceSection');
            const extendSection = document.getElementById('extendInvoiceSection');

            if (this.value === 'new') {
                newSection.style.display = 'block';
                extendSection.style.display = 'none';
            } else {
                newSection.style.display = 'none';
                extendSection.style.display = 'block';
            }
        });
    });

    // Load student invoices when student is selected (for extend option)
    document.getElementById('studentSelect').addEventListener('change', function() {
        const registrationNumber = this.value;
        const invoiceSelectContainer = document.getElementById('invoiceSelectContainer');
        const dueDateContainer = document.getElementById('dueDateContainer');
        const invoiceSelect = document.getElementById('invoiceSelect');

        if (!registrationNumber) {
            invoiceSelectContainer.style.display = 'none';
            dueDateContainer.style.display = 'none';
            return;
        }

        // Fetch invoices via AJAX
        fetch(`/invoice/student-invoices?registration_number=${registrationNumber}`)
            .then(response => response.json())
            .then(data => {
                if (data.invoices && data.invoices.length > 0) {
                    invoiceSelect.innerHTML = '<option value="">Select Invoice</option>';
                    data.invoices.forEach(invoice => {
                        const items = JSON.parse(invoice.items || '[]');
                        const itemDescriptions = items.map(i => i.description).join(', ');
                        invoiceSelect.innerHTML += `
                            <option value="${invoice.id}" data-invoice-type="${invoice.invoice_type}">
                                ${invoice.invoice_number} - ${formatCurrency(invoice.amount)}
                                (${invoice.status}) - ${itemDescriptions}
                            </option>
                        `;
                    });
                    invoiceSelectContainer.style.display = 'block';
                    dueDateContainer.style.display = 'block';
                } else {
                    invoiceSelectContainer.style.display = 'none';
                    dueDateContainer.style.display = 'none';
                    alert('No unpaid or partially paid invoices found for this student.');
                }
            })
            .catch(error => {
                console.error('Error fetching invoices:', error);
                alert('Failed to load invoices. Please try again.');
            });
    });

    // Show invoice details when invoice is selected
    document.getElementById('invoiceSelect').addEventListener('change', function() {
        const invoiceId = this.value;
        const detailsDiv = document.getElementById('selectedInvoiceDetails');
        const invoiceTypeInput = document.getElementById('extendInvoiceType');

        if (!invoiceId) {
            detailsDiv.style.display = 'none';
            invoiceTypeInput.value = '';
            return;
        }

        // Get selected option
        const selectedOption = this.options[this.selectedIndex];
        const invoiceType = selectedOption.getAttribute('data-invoice-type');

        // Set the invoice type
        invoiceTypeInput.value = invoiceType;

        // Fetch invoice summary via AJAX
        fetch(`/invoice/invoice-summary?invoice_id=${invoiceId}`)
            .then(response => response.json())
            .then(data => {
                if (data.summary) {
                    const s = data.summary;
                    detailsDiv.innerHTML = `
                        <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Invoice Extension Summary</h6>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Invoice Number:</strong> ${s.invoice_number}
                                </div>
                                <div class="mb-2">
                                    <strong>Initial Program Amount:</strong> ${formatCurrency(s.initial_program_amount)}
                                </div>
                                <div class="mb-2">
                                    <strong>Registration Fee:</strong> ${formatCurrency(s.registration_fee)}
                                </div>
                                <div class="mb-2">
                                    <strong>Total Initial Amount:</strong> ${formatCurrency(s.total_initial_amount)}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Total Paid:</strong> <span class="text-success">${formatCurrency(s.total_paid)}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Outstanding Balance:</strong>
                                    <span class="badge ${s.outstanding_balance > 0 ? 'bg-warning' : 'bg-success'}">
                                        ${formatCurrency(s.outstanding_balance)}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <strong>Current Invoice Amount:</strong> ${formatCurrency(s.current_invoice_amount)}
                                </div>
                                <div class="mb-2">
                                    <strong>Invoice Status:</strong>
                                    <span class="badge bg-${s.invoice_status === 'paid' ? 'success' : s.invoice_status === 'partially_paid' ? 'info' : 'warning'}">
                                        ${s.invoice_status.replace('_', ' ')}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <small class="text-muted">
                            <i class="bi bi-lightbulb"></i>
                            The new amount you add below is for information/notification only and will NOT update the invoice amount.
                            The invoice amount remains unchanged. The outstanding balance is based on the original program cost (tuition fee + registration fee).
                        </small>
                    `;
                    detailsDiv.className = 'alert alert-info';
                    detailsDiv.style.display = 'block';
                } else if (data.error) {
                    detailsDiv.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> ${data.error}
                        </div>
                    `;
                    detailsDiv.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error fetching invoice summary:', error);
                detailsDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle"></i> Failed to load invoice summary. Please try again.
                    </div>
                `;
                detailsDiv.style.display = 'block';
            });
    });

    function addLineItem() {
        lineItemCount++;
        const itemId = 'item_' + lineItemCount;

        const html = `
            <div class="line-item-row" data-item-id="${itemId}">
                <div class="form-group flex-grow-1">
                    <input type="text" name="items[${lineItemCount}][description]"
                           class="form-control item-description" placeholder="Item description" >
                </div>
                <div class="form-group amount">
                    <input type="number" name="items[${lineItemCount}][amount]"
                           class="form-control item-amount" placeholder="0.00" step="0.01" min="0" >
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

        // Update the "New Amount Being Added" label
        const totalLabel = document.querySelector('.total-row td:first-child');
        if (totalLabel) {
            totalLabel.innerHTML = 'New Amount Being Added (Informational):';
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(amount);
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
        const action = document.querySelector('input[name="invoiceOption"]:checked').value;

        // Validate based on action
        if (action === 'new') {
            // Validate new invoice fields
            const newStudentSelect = document.getElementById('newStudentSelect');
            const newInvoiceType = document.querySelector('#newInvoiceSection select[name="invoice_type"]');
            const newDueDate = document.getElementById('newDueDate');

            if (!newStudentSelect.value) {
                e.preventDefault();
                alert('Please select a student');
                return false;
            }

            if (!newInvoiceType.value) {
                e.preventDefault();
                alert('Please select an invoice type');
                return false;
            }

            if (!newDueDate.value) {
                e.preventDefault();
                alert('Please select a due date');
                return false;
            }
        } else {
            // Validate extend invoice fields
            const studentSelect = document.getElementById('studentSelect');
            const invoiceSelect = document.getElementById('invoiceSelect');
            const extendDueDate = document.getElementById('extendDueDate');

            if (!studentSelect.value) {
                e.preventDefault();
                alert('Please select a student');
                return false;
            }

            if (!invoiceSelect.value) {
                e.preventDefault();
                alert('Please select an invoice to extend');
                return false;
            }

            if (!extendDueDate.value) {
                e.preventDefault();
                alert('Please select a due date');
                return false;
            }
        }

        // Validate line items
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

        // Add hidden field for action
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        this.appendChild(actionInput);
    });

    // Initialize with one empty line item
    addLineItem();
</script>
<?= $this->endSection() ?>