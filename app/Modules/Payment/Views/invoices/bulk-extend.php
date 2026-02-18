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

    .btn-invoice:hover {
        background: linear-gradient(to right, #6B0000, #8B0000);
        color: white;
    }

    .bulk-line-row {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 15px;
        padding: 15px;
        position: relative;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .bulk-line-row .row-number {
        position: absolute;
        top: -10px;
        left: 15px;
        background: #8B0000;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }

    .student-search-container {
        position: relative;
    }

    .student-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 4px 4px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }

    .student-search-results .search-result-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }

    .student-search-results .search-result-item:hover {
        background-color: #f8f9fa;
    }

    .student-search-results .search-result-item:last-child {
        border-bottom: none;
    }

    .selected-student-info {
        background-color: #e8f5e9;
        border-radius: 4px;
        padding: 10px;
        margin-top: 10px;
        display: none;
        line-height: 1.5;
    }

    .invoice-list-container {
        background-color: #f8f9fa;
        border-radius: 4px;
        padding: 10px;
        margin-top: 10px;
        max-height: 150px;
        overflow-y: auto;
        display: none;
    }

    .invoice-item {
        padding: 8px;
        border-bottom: 1px solid #dee2e6;
        cursor: pointer;
    }

    .invoice-item:hover {
        background-color: #e9ecef;
    }

    .invoice-item.selected {
        background-color: #cfe2ff;
        border-color: #0d6efd;
    }

    .invoice-item:last-child {
        border-bottom: none;
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

    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #8B0000;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .badge-status {
        font-size: 0.75rem;
        padding: 3px 6px;
    }
</style>

<div class="container-fluid">
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="invoice-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="mb-0"><i class="bi bi-layers"></i> Perpanjang Faktur Massal</h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?= base_url('invoice') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Faktur
                </a>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> <strong>Petunjuk:</strong>
        <ol class="mb-0 mt-2">
            <li>Ketik nama atau nomor registrasi untuk mencari pendaftaran (minimal 2 karakter)</li>
            <li>Pilih pendaftaran dari hasil pencarian</li>
            <li>Pilih faktur yang akan diperpanjang dari daftar</li>
            <li>Tambahkan item dan jumlah untuk faktur baru</li>
            <li>Ulangi untuk menambah baris lainnya</li>
        </ol>
    </div>

    <form action="<?= base_url('invoice/bulk-extend-store') ?>" method="post" id="bulkExtendForm">
        <?= csrf_field() ?>

        <!-- Bulk Lines Container -->
        <div id="bulkLinesContainer">
            <!-- Lines will be added here dynamically -->
        </div>

        <!-- Add Line Button -->
        <div class="mb-3">
            <button type="button" class="btn btn-outline-primary" id="addBulkLine">
                <i class="bi bi-plus-circle"></i> Tambah Baris
            </button>
        </div>

        <!-- Summary Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-check"></i> Ringkasan Faktur</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pendaftaran</th>
                                <th>Faktur Asli</th>
                                <th>Item Baru</th>
                                <th style="text-align: right;">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody id="summaryBody">
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada baris ditambahkan</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-warning">
                                <td colspan="4" class="text-end"><strong>Total Keseluruhan:</strong></td>
                                <td class="text-end"><strong id="grandTotal">Rp 0</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-between mt-4">
            <a href="<?= base_url('invoice') ?>" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Batal
            </a>
            <button type="submit" class="btn btn-invoice" id="submitBtn" disabled>
                <i class="bi bi-check-circle"></i> Proses Perpanjangan Massal
            </button>
        </div>
    </form>
</div>

<!-- Template for bulk line row -->
<template id="bulkLineTemplate">
    <div class="bulk-line-row" data-line-index="">
        <span class="row-number"></span>
        <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 10px; right: 10px;" onclick="removeBulkLine(this)">
            <i class="bi bi-trash"></i>
        </button>

        <div class="row mt-2">
            <!-- Student Search -->
            <div class="col-md-4">
                <label class="form-label">Cari Pendaftaran *</label>
                <div class="student-search-container">
                    <input type="text" class="form-control student-search-input" 
                           placeholder="Ketik nama atau nomor registrasi..." autocomplete="off">
                    <input type="hidden" class="selected-registration-number" name="lines[INDEX][registration_number]">
                    <div class="student-search-results"></div>
                </div>
                <div class="selected-student-info">
                    <small class="student-name-display"></small>
                </div>
            </div>

            <!-- Invoice Selection -->
            <div class="col-md-4">
                <label class="form-label">Pilih Faktur *</label>
                <div class="invoice-list-container">
                    <div class="text-center text-muted invoice-loading" style="display: none;">
                        <span class="loading-spinner"></span> Memuat faktur...
                    </div>
                    <div class="invoice-list"></div>
                    <div class="no-invoices text-center text-muted" style="display: none;">
                        <small>Tidak ada faktur yang dapat diperpanjang</small>
                    </div>
                </div>
                <input type="hidden" class="selected-invoice-id" name="lines[INDEX][invoice_id]">
                <input type="hidden" class="selected-invoice-type" name="lines[INDEX][invoice_type]">
                <div class="selected-invoice-info mt-2" style="display: none;">
                    <small class="invoice-number-display"></small>
                </div>
            </div>

            <!-- Due Date -->
            <div class="col-md-4">
                <label class="form-label">Tanggal Jatuh Tempo *</label>
                <input type="date" class="form-control due-date-input" name="lines[INDEX][due_date]">
            </div>
        </div>

        <!-- Line Items -->
        <div class="row mt-3">
            <div class="col-12">
                <label class="form-label">Item Faktur Baru</label>
                <div class="line-items-container">
                    <!-- Line items will be added here -->
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary add-line-item-btn">
                    <i class="bi bi-plus"></i> Tambah Item
                </button>
            </div>
        </div>

        <!-- Line Total -->
        <div class="row mt-3">
            <div class="col-12 text-end">
                <strong>Total Baris: <span class="line-total-amount">Rp 0</span></strong>
            </div>
        </div>
    </div>
</template>

<!-- Template for line item -->
<template id="lineItemTemplate">
    <div class="row line-item-row mb-2">
        <div class="col-md-8">
            <input type="text" class="form-control item-description" placeholder="Deskripsi item">
        </div>
        <div class="col-md-3">
            <input type="number" class="form-control item-amount" placeholder="Jumlah" step="0.01" min="0">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </div>
</template>

<script>
let lineCounter = 0;
let searchTimeout;

// Initialize with one line
document.addEventListener('DOMContentLoaded', function() {
    addBulkLine();
});

// Add a new bulk line row
function addBulkLine() {
    lineCounter++;
    const template = document.getElementById('bulkLineTemplate');
    const clone = template.content.cloneNode(true);
    const row = clone.querySelector('.bulk-line-row');
    
    row.dataset.lineIndex = lineCounter;
    row.querySelector('.row-number').textContent = lineCounter;
    
    // Update input names with correct index
    row.querySelectorAll('input[name]').forEach(input => {
        input.name = input.name.replace('INDEX', lineCounter);
    });
    
    document.getElementById('bulkLinesContainer').appendChild(row);
    
    // Initialize event listeners for this row
    initializeRowEvents(row);
    
    // Add one line item by default
    addLineItemToRow(row);
    
    updateSummary();
}

// Initialize event listeners for a row
function initializeRowEvents(row) {
    const searchInput = row.querySelector('.student-search-input');
    const searchResults = row.querySelector('.student-search-results');
    const addLineItemBtn = row.querySelector('.add-line-item-btn');
    const dueDateInput = row.querySelector('.due-date-input');
    
    // Set default due date to 30 days from now
    const defaultDueDate = new Date();
    defaultDueDate.setDate(defaultDueDate.getDate() + 30);
    dueDateInput.value = defaultDueDate.toISOString().split('T')[0];
    
    // Student search
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            searchAdmissions(query, row);
        }, 300);
    });
    
    // Hide search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!row.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
    
    // Add line item button
    addLineItemBtn.addEventListener('click', function() {
        addLineItemToRow(row);
    });
}

// Search admissions via API
function searchAdmissions(query, row) {
    const searchResults = row.querySelector('.student-search-results');
    
    fetch(`<?= base_url('api/invoices/search-admissions') ?>?q=${encodeURIComponent(query)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success' && data.data.length > 0) {
                let html = '';
                data.data.forEach(admission => {
                    const statusBadge = admission.status === 'approved' 
                        ? '<span class="badge bg-success badge-status">approved</span>'
                        : admission.status === 'pending'
                        ? '<span class="badge bg-warning badge-status">pending</span>'
                        : '<span class="badge bg-secondary badge-status">' + admission.status + '</span>';
                    
                    html += `
                        <div class="search-result-item" 
                             data-registration="${admission.registration_number}"
                             data-name="${escapeHtml(admission.full_name)}"
                             data-program="${escapeHtml(admission.program_title || '')}"
                             data-outstanding="${admission.outstanding || 0}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>${escapeHtml(admission.full_name)}</strong> ${statusBadge}<br>
                                    <small class="text-muted">${admission.registration_number}</small><br>
                                    <small class="text-info">${escapeHtml(admission.program_title || '-')}</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">Outstanding:</small><br>
                                    <strong class="${admission.outstanding > 0 ? 'text-danger' : 'text-success'}">${formatCurrency(admission.outstanding || 0)}</strong>
                                </div>
                            </div>
                        </div>
                    `;
                });
                searchResults.innerHTML = html;
                searchResults.style.display = 'block';
                
                // Add click handlers to results
                searchResults.querySelectorAll('.search-result-item').forEach(item => {
                    item.addEventListener('click', function() {
                        selectAdmission(this, row);
                    });
                });
            } else {
                searchResults.innerHTML = '<div class="p-3 text-center text-muted">Tidak ditemukan</div>';
                searchResults.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error searching admissions:', error);
            searchResults.innerHTML = '<div class="p-3 text-center text-danger">Gagal mencari: ' + error.message + '</div>';
            searchResults.style.display = 'block';
        });
}

// Select an admission
function selectAdmission(element, row) {
    const registrationNumber = element.dataset.registration;
    const admissionName = element.dataset.name;
    const program = element.dataset.program;
    const outstanding = element.dataset.outstanding;
    
    // Update hidden input
    row.querySelector('.selected-registration-number').value = registrationNumber;
    
    // Update display
    row.querySelector('.student-search-input').value = admissionName;
    row.querySelector('.student-name-display').innerHTML = `
        <strong>${admissionName}</strong> (${registrationNumber})<br>
        <small class="text-info">${program}</small><br>
        <small class="${parseFloat(outstanding) > 0 ? 'text-danger' : 'text-success'}">
            Outstanding: ${formatCurrency(parseFloat(outstanding))}
        </small>
    `;
    row.querySelector('.selected-student-info').style.display = 'block';
    row.querySelector('.student-search-results').style.display = 'none';
    
    // Clear previous invoice selection
    row.querySelector('.selected-invoice-id').value = '';
    row.querySelector('.selected-invoice-type').value = '';
    row.querySelector('.selected-invoice-info').style.display = 'none';
    
    // Load invoices for this admission
    loadAdmissionInvoices(registrationNumber, row);
}

// Load invoices for an admission
function loadAdmissionInvoices(registrationNumber, row) {
    const invoiceListContainer = row.querySelector('.invoice-list-container');
    const invoiceList = row.querySelector('.invoice-list');
    const loadingDiv = row.querySelector('.invoice-loading');
    const noInvoicesDiv = row.querySelector('.no-invoices');
    
    invoiceListContainer.style.display = 'block';
    loadingDiv.style.display = 'block';
    invoiceList.innerHTML = '';
    noInvoicesDiv.style.display = 'none';
    
    fetch(`<?= base_url('api/invoices/student/') ?>${registrationNumber}`)
        .then(response => response.json())
        .then(data => {
            loadingDiv.style.display = 'none';
            
            if (data.status === 'success' && data.data.length > 0) {
                // Filter only extendable invoices
                const extendableInvoices = data.data.filter(inv => 
                    ['unpaid', 'partially_paid', 'expired'].includes(inv.status)
                );
                
                if (extendableInvoices.length > 0) {
                    let html = '';
                    extendableInvoices.forEach(invoice => {
                        const statusBadge = getStatusBadge(invoice.status);
                        html += `
                            <div class="invoice-item" 
                                 data-invoice-id="${invoice.id}"
                                 data-invoice-type="${invoice.invoice_type}"
                                 data-invoice-number="${invoice.invoice_number}"
                                 data-amount="${invoice.amount}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${invoice.invoice_number}</strong>
                                        ${statusBadge}
                                    </div>
                                    <span class="text-end">${formatCurrency(invoice.amount)}</span>
                                </div>
                            </div>
                        `;
                    });
                    invoiceList.innerHTML = html;
                    
                    // Add click handlers
                    invoiceList.querySelectorAll('.invoice-item').forEach(item => {
                        item.addEventListener('click', function() {
                            selectInvoice(this, row);
                        });
                    });
                } else {
                    noInvoicesDiv.style.display = 'block';
                }
            } else {
                noInvoicesDiv.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error loading invoices:', error);
            loadingDiv.style.display = 'none';
            noInvoicesDiv.style.display = 'block';
        });
}

// Select an invoice
function selectInvoice(element, row) {
    // Remove selection from other items
    row.querySelectorAll('.invoice-item').forEach(item => {
        item.classList.remove('selected');
    });
    
    // Add selection to clicked item
    element.classList.add('selected');
    
    // Update hidden inputs
    row.querySelector('.selected-invoice-id').value = element.dataset.invoiceId;
    row.querySelector('.selected-invoice-type').value = element.dataset.invoiceType;
    
    // Update display
    row.querySelector('.invoice-number-display').textContent = 
        'Faktur: ' + element.dataset.invoiceNumber + ' (' + formatCurrency(element.dataset.amount) + ')';
    row.querySelector('.selected-invoice-info').style.display = 'block';
    
    updateSummary();
}

// Add line item to a row
function addLineItemToRow(row) {
    const template = document.getElementById('lineItemTemplate');
    const clone = template.content.cloneNode(true);
    const container = row.querySelector('.line-items-container');
    const lineIndex = row.dataset.lineIndex;
    const itemCount = container.children.length;
    
    // Update input names
    clone.querySelectorAll('input').forEach(input => {
        if (input.classList.contains('item-description')) {
            input.name = `lines[${lineIndex}][items][${itemCount}][description]`;
        } else if (input.classList.contains('item-amount')) {
            input.name = `lines[${lineIndex}][items][${itemCount}][amount]`;
        }
    });
    
    container.appendChild(clone);
    
    // Add event listeners
    const newItem = container.lastElementChild;
    newItem.querySelector('.item-description').addEventListener('input', () => updateLineTotal(row));
    newItem.querySelector('.item-amount').addEventListener('input', () => updateLineTotal(row));
    newItem.querySelector('.remove-item-btn').addEventListener('click', function() {
        newItem.remove();
        updateLineTotal(row);
    });
}

// Update line total
function updateLineTotal(row) {
    const amounts = row.querySelectorAll('.item-amount');
    let total = 0;
    
    amounts.forEach(input => {
        const amount = parseFloat(input.value) || 0;
        total += amount;
    });
    
    row.querySelector('.line-total-amount').textContent = formatCurrency(total);
    updateSummary();
}

// Remove a bulk line row
function removeBulkLine(button) {
    const row = button.closest('.bulk-line-row');
    row.remove();
    updateSummary();
    renumberLines();
}

// Renumber lines after removal
function renumberLines() {
    const rows = document.querySelectorAll('.bulk-line-row');
    rows.forEach((row, index) => {
        row.querySelector('.row-number').textContent = index + 1;
    });
}

// Update summary table
function updateSummary() {
    const summaryBody = document.getElementById('summaryBody');
    const rows = document.querySelectorAll('.bulk-line-row');
    let grandTotal = 0;
    let hasValidRows = false;
    
    if (rows.length === 0) {
        summaryBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Belum ada baris ditambahkan</td></tr>';
        document.getElementById('grandTotal').textContent = 'Rp 0';
        document.getElementById('submitBtn').disabled = true;
        return;
    }
    
    let html = '';
    rows.forEach((row, index) => {
        const regNumber = row.querySelector('.selected-registration-number').value;
        const invoiceId = row.querySelector('.selected-invoice-id').value;
        const studentName = row.querySelector('.student-name-display').textContent || '-';
        const invoiceNumber = row.querySelector('.invoice-number-display').textContent || '-';
        
        // Calculate line total
        let lineTotal = 0;
        const itemDescriptions = [];
        row.querySelectorAll('.line-item-row').forEach(itemRow => {
            const desc = itemRow.querySelector('.item-description').value.trim();
            const amount = parseFloat(itemRow.querySelector('.item-amount').value) || 0;
            if (desc && amount > 0) {
                lineTotal += amount;
                itemDescriptions.push(desc);
            }
        });
        
        grandTotal += lineTotal;
        
        const isValid = regNumber && invoiceId && lineTotal > 0;
        if (isValid) hasValidRows = true;
        
        html += `
            <tr class="${isValid ? '' : 'table-light text-muted'}">
                <td>${index + 1}</td>
                <td>${escapeHtml(studentName)}</td>
                <td>${escapeHtml(invoiceNumber)}</td>
                <td><small>${escapeHtml(itemDescriptions.slice(0, 2).join(', '))}${itemDescriptions.length > 2 ? '...' : ''}</small></td>
                <td class="text-end">${formatCurrency(lineTotal)}</td>
            </tr>
        `;
    });
    
    summaryBody.innerHTML = html;
    document.getElementById('grandTotal').textContent = formatCurrency(grandTotal);
    document.getElementById('submitBtn').disabled = !hasValidRows;
}

// Helper functions
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

function getStatusBadge(status) {
    const colors = {
        'paid': 'success',
        'partially_paid': 'info',
        'unpaid': 'warning',
        'expired': 'danger',
        'extended': 'primary',
        'cancelled': 'secondary'
    };
    const color = colors[status] || 'secondary';
    return `<span class="badge bg-${color} badge-status">${status.replace('_', ' ')}</span>`;
}

// Add bulk line button handler
document.getElementById('addBulkLine').addEventListener('click', function() {
    addBulkLine();
});

// Form submission
document.getElementById('bulkExtendForm').addEventListener('submit', function(e) {
    const rows = document.querySelectorAll('.bulk-line-row');
    let hasErrors = false;
    let errorMessages = [];
    
    rows.forEach((row, index) => {
        const regNumber = row.querySelector('.selected-registration-number').value;
        const invoiceId = row.querySelector('.selected-invoice-id').value;
        const dueDate = row.querySelector('.due-date-input').value;
        
        // Check required fields
        if (!regNumber) {
            errorMessages.push(`Baris ${index + 1}: Pilih pendaftaran`);
            hasErrors = true;
        }
        
        if (!invoiceId) {
            errorMessages.push(`Baris ${index + 1}: Pilih faktur untuk diperpanjang`);
            hasErrors = true;
        }
        
        if (!dueDate) {
            errorMessages.push(`Baris ${index + 1}: Pilih tanggal jatuh tempo`);
            hasErrors = true;
        }
        
        // Check line items
        let hasValidItem = false;
        row.querySelectorAll('.line-item-row').forEach(itemRow => {
            const desc = itemRow.querySelector('.item-description').value.trim();
            const amount = parseFloat(itemRow.querySelector('.item-amount').value) || 0;
            if (desc && amount > 0) {
                hasValidItem = true;
            }
        });
        
        if (!hasValidItem) {
            errorMessages.push(`Baris ${index + 1}: Tambahkan minimal satu item dengan deskripsi dan jumlah`);
            hasErrors = true;
        }
    });
    
    if (hasErrors) {
        e.preventDefault();
        alert('Mohon perbaiki kesalahan berikut:\n\n' + errorMessages.join('\n'));
        return false;
    }
    
    // Confirm submission
    if (!confirm('Apakah Anda yakin ingin memproses perpanjangan faktur massal ini?')) {
        e.preventDefault();
        return false;
    }
});
</script>
<?= $this->endSection() ?>
