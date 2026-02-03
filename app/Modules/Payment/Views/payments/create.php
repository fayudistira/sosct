<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
    /* Select2 Bootstrap 5 compatibility */
    .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 0.375rem !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
        padding-left: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .loading-spinner {
        display: none;
        order: 2;
        margin-left: 10px;
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
    
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="<?= base_url('payment/store') ?>" method="post" enctype="multipart/form-data" id="paymentForm">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-bold">1. Select Student *</label>
                            <select name="registration_number" id="studentSearch" class="form-select" required>
                                <option value="">Type to search student name or reg number...</option>
                            </select>
                            <div class="form-text">Search by registration number or student name.</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-bold">2. Related Invoice *</label>
                            <div class="d-flex align-items-center">
                                <select name="invoice_id" id="invoiceSelect" class="form-select" disabled required>
                                    <option value="">Select student first...</option>
                                </select>
                                <div id="invoiceLoading" class="spinner-border spinner-border-sm text-danger ms-2" role="status" style="display: none;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div class="form-text">Invoices will be populated automatically after selecting a student.</div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Amount *</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="amount" id="amountInput" class="form-control" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Method *</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">Select Method</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="mobile_banking">Mobile Banking</option>
                                <option value="credit_card">Credit Card</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Reference / Document Number *</label>
                            <input type="text" name="document_number" class="form-control" placeholder="Trial/Ref Code/Tnx ID" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Date *</label>
                            <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="pending">Pending (Review Needed)</option>
                                <option value="paid" selected>Paid (Confirmed)</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Receipt File (Max 2MB)</label>
                            <input type="file" name="receipt_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Additional Notes</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Any additional information..."></textarea>
                </div>
                
                <div class="d-flex justify-content-between p-3 bg-light rounded shadow-sm border">
                    <a href="<?= base_url('payment') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-payment px-5">
                        <i class="bi bi-check-circle me-1"></i> Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Explicitly reset on load
    $('#invoiceSelect').empty().append('<option value="">Select student first...</option>');
    $('#amountInput').val('');

    // Initialize Select2 for Student Search
    $('#studentSearch').select2({
        theme: 'default',
        placeholder: 'Search for a student...',
        minimumInputLength: 1,
        ajax: {
            url: '<?= base_url('admission/ajax-search') ?>',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.results
                };
            },
            cache: true
        }
    });

    // When Student is selected, fetch their unpaid invoices
    $('#studentSearch').on('change', function() {
        const regNumber = $(this).val();
        const invoiceSelect = $('#invoiceSelect');
        const loadingSpinner = $('#invoiceLoading');
        
        console.log('Selected student registration number:', regNumber);
        
        // Reset and disable invoice dropdown
        invoiceSelect.empty().append('<option value="">Loading invoices...</option>').prop('disabled', true);
        $('#amountInput').val('');
        
        if (regNumber) {
            loadingSpinner.show();
            const apiUrl = '<?= base_url('api/invoices/student') ?>/' + regNumber;
            console.log('Fetching invoices from:', apiUrl);
            
            $.ajax({
                url: apiUrl,
                method: 'GET',
                success: function(response) {
                    console.log('API full response:', response);
                    loadingSpinner.hide();
                    invoiceSelect.prop('disabled', false);
                    invoiceSelect.empty().append('<option value="">-- Choose Invoice --</option>');
                    
                    if (response.data && response.data.length > 0) {
                        let unpaidFound = false;
                        response.data.forEach(function(invoice) {
                            if (invoice.status === 'unpaid') {
                                const typeName = invoice.invoice_type ? invoice.invoice_type.replace(/_/g, ' ').toUpperCase() : 'INVOICE';
                                invoiceSelect.append(`<option value="${invoice.id}" data-amount="${invoice.amount}">
                                    ${invoice.invoice_number} - ${typeName} (Rp ${parseInt(invoice.amount).toLocaleString('id-ID')})
                                </option>`);
                                unpaidFound = true;
                            }
                        });
                        
                        if (!unpaidFound) {
                            invoiceSelect.append('<option value="" disabled>No unpaid invoices found</option>');
                        } else {
                            // If only one unpaid invoice, select it automatically
                            if (invoiceSelect.find('option[data-amount]').length === 1) {
                                invoiceSelect.find('option[data-amount]').prop('selected', true).trigger('change');
                            }
                        }
                    } else {
                        invoiceSelect.append('<option disabled>No invoices recorded for this student</option>');
                    }
                },
                error: function(xhr, status, error) {
                    loadingSpinner.hide();
                    console.error('Failed to fetch invoices:', error);
                    console.log('Response Text:', xhr.responseText);
                    invoiceSelect.prop('disabled', false);
                    invoiceSelect.empty().append('<option value="" disabled>Error loading invoices. Please try again.</option>');
                    alert('Could not fetch invoices. Please check your connection or server logs.');
                }
            });
        }
    });

    // When Invoice is selected, auto-populate the amount
    $('#invoiceSelect').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const amount = selectedOption.data('amount');
        
        if (amount) {
            $('#amountInput').val(amount);
            // Highlight the change visually
            $('#amountInput').addClass('is-valid');
            setTimeout(() => $('#amountInput').removeClass('is-valid'), 2000);
        }
    });
});
</script>
<?= $this->endSection() ?>
