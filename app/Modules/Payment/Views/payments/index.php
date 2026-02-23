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

    .badge-paid {
        background-color: #28a745;
    }

    .badge-pending {
        background-color: #ffc107;
    }

    .badge-failed {
        background-color: #dc3545;
    }

    .badge-refunded {
        background-color: #6c757d;
    }

    #payments-table tbody tr {
        cursor: pointer;
    }

    #payments-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .loading-overlay {
        position: relative;
        opacity: 0.6;
    }

    .loading-spinner {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
    }

    .sortable {
        cursor: pointer;
        user-select: none;
    }

    .sortable:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .sortable .sort-icon {
        margin-left: 5px;
        opacity: 0.3;
    }

    .sortable.asc .sort-icon,
    .sortable.desc .sort-icon {
        opacity: 1;
    }

    .sortable.asc .sort-icon::before {
        content: '\25B2';
    }

    .sortable.desc .sort-icon::before {
        content: '\25BC';
    }

    .sortable:not(.asc):not(.desc) .sort-icon::before {
        content: '\25B2';
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Riwayat Pembayaran</h3>
        <a href="<?= base_url('payment/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Input Pembayaran
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <!-- Search and Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form id="search-form">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" id="search-input" class="form-control"
                            placeholder="Search..." value="<?= esc($keyword ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="status" id="status-filter" class="form-select">
                            <option value="">Status</option>
                            <option value="pending" <?= ($status ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="paid" <?= ($status ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="failed" <?= ($status ?? '') === 'failed' ? 'selected' : '' ?>>Failed</option>
                            <option value="refunded" <?= ($status ?? '') === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="method" id="method-filter" class="form-select">
                            <option value="">Metode</option>
                            <option value="cash" <?= ($method ?? '') === 'cash' ? 'selected' : '' ?>>Cash</option>
                            <option value="bank_transfer" <?= ($method ?? '') === 'bank_transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="start_date" id="start-date" class="form-control" value="<?= esc($start_date ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="end_date" id="end-date" class="form-control" value="<?= esc($end_date ?? '') ?>">
                    </div>
                    <div class="col-md-1">
                        <button type="button" id="clear-filters" class="btn btn-outline-secondary w-100" title="Clear Filters">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div class="card-body position-relative">
            <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="payments-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">No.</th>
                            <th class="sortable" data-sort="payment_date">Tgl. <span class="sort-icon"></span></th>
                            <th class="sortable" data-sort="invoice_number">No.Invoice <span class="sort-icon"></span></th>
                            <th class="sortable" data-sort="student_name">Nama Siswa <span class="sort-icon"></span></th>
                            <th class="sortable" data-sort="amount">Jumlah <span class="sort-icon"></span></th>
                            <th class="sortable" data-sort="payment_method">Metode <span class="sort-icon"></span></th>
                            <th class="sortable" data-sort="status">Status <span class="sort-icon"></span></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="payments-tbody">
                        <?php 
                        $startIndex = (($currentPage ?? 1) - 1) * 10 + 1;
                        foreach ($payments as $index => $payment): 
                        ?>
                            <tr>
                                <td class="text-center text-muted"><?= $startIndex + $index ?></td>
                                <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                                <td>
                                    <?php if (!empty($payment['invoice_id']) && !empty($payment['invoice_number'])): ?>
                                        <a href="<?= base_url('invoice/view/' . $payment['invoice_id']) ?>">
                                            #<?= esc($payment['invoice_number']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span>N/A</span>
                                    <?php endif ?>
                                </td>
                                <td><?= esc($payment['student_name'] ?? 'N/A') ?></td>
                                <td>Rp <?= number_format($payment['amount'] ?? 0, 0, ',', '.') ?></td>
                                <td><?= ucwords(str_replace('_', ' ', $payment['payment_method'] ?? 'N/A')) ?></td>
                                <td>
                                    <span class="badge bg-<?= $payment['status'] === 'paid' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($payment['status'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('payment/view/' . $payment['id']) ?>"
                                            class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url('payment/edit/' . $payment['id']) ?>"
                                            class="btn btn-sm btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= base_url('payment/receipt/' . $payment['id']) ?>"
                                            class="btn btn-sm btn-outline-info"
                                            target="_blank"
                                            title="Print Receipt">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div id="pagination-container" class="mt-3">
                <?php if (isset($pager)): ?>
                    <?= $pager->links() ?>
                <?php endif ?>
            </div>
            
            <div id="no-results" class="text-center py-4" style="display: none;">
                <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-2">No payments found matching your criteria.</p>
            </div>
        </div>
    </div>
</div>

<script>
let searchTimeout;
let currentPage = 1;
let currentSort = 'payment_date';
let currentOrder = 'desc';

// Debounced search function
function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(performSearch, 300);
}

// Perform AJAX search
function performSearch(page = 1) {
    currentPage = page;
    const searchValue = document.getElementById('search-input').value;
    const statusValue = document.getElementById('status-filter').value;
    const methodValue = document.getElementById('method-filter').value;
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    // Show loading state
    const tbody = document.getElementById('payments-tbody');
    const spinner = document.querySelector('.loading-spinner');
    const table = document.getElementById('payments-table');
    
    table.classList.add('loading-overlay');
    spinner.style.display = 'block';

    // Build query params
    const params = new URLSearchParams();
    if (searchValue) params.append('q', searchValue);
    if (statusValue) params.append('status', statusValue);
    if (methodValue) params.append('method', methodValue);
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    params.append('page', page);
    params.append('per_page', 10);
    params.append('sort', currentSort);
    params.append('order', currentOrder);

    // Make AJAX request
    fetch(`<?= base_url('api/payments') ?>?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        table.classList.remove('loading-overlay');
        spinner.style.display = 'none';

        if (data.status === 'success') {
            updateTable(data.data);
            updatePagination(data.pagination);
            updateRecordCount(data.pagination);
        } else {
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        table.classList.remove('loading-overlay');
        spinner.style.display = 'none';
        console.error('Error:', error);
    });
}

// Update table with new data
function updateTable(payments) {
    const tbody = document.getElementById('payments-tbody');
    const noResults = document.getElementById('no-results');
    const table = document.getElementById('payments-table');

    if (payments.length === 0) {
        tbody.innerHTML = '';
        table.style.display = 'none';
        noResults.style.display = 'block';
        return;
    }

    table.style.display = 'table';
    noResults.style.display = 'none';

    const startIndex = (currentPage - 1) * 10 + 1;

    tbody.innerHTML = payments.map((payment, index) => {
        const invoiceLink = payment.invoice_id && payment.invoice_number
            ? `<a href="<?= base_url('invoice/view/') ?>${payment.invoice_id}">#${escapeHtml(payment.invoice_number)}</a>`
            : '<span>N/A</span>';

        const statusBadge = payment.status === 'paid'
            ? '<span class="badge bg-success">Paid</span>'
            : '<span class="badge bg-warning">' + escapeHtml(ucfirst(payment.status || 'N/A')) + '</span>';

        return `
            <tr>
                <td class="text-center text-muted">${startIndex + index}</td>
                <td>${formatDate(payment.payment_date)}</td>
                <td>${invoiceLink}</td>
                <td>${escapeHtml(payment.student_name || payment.student?.full_name || 'N/A')}</td>
                <td>Rp ${formatNumber(payment.amount || 0)}</td>
                <td>${escapeHtml(ucwords((payment.payment_method || 'N/A').replace(/_/g, ' ')))}</td>
                <td>${statusBadge}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('payment/view/') ?>${payment.id}"
                            class="btn btn-sm btn-outline-primary" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="<?= base_url('payment/edit/') ?>${payment.id}"
                            class="btn btn-sm btn-outline-secondary" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= base_url('payment/receipt/') ?>${payment.id}"
                            class="btn btn-sm btn-outline-info"
                            target="_blank"
                            title="Print Receipt">
                            <i class="bi bi-printer"></i>
                        </a>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Update record count display
function updateRecordCount(pagination) {
    let countDiv = document.getElementById('record-count');
    if (!countDiv) {
        countDiv = document.createElement('div');
        countDiv.id = 'record-count';
        countDiv.className = 'text-muted small mb-2';
        const tableContainer = document.querySelector('.card-body.position-relative');
        if (tableContainer) {
            tableContainer.insertBefore(countDiv, tableContainer.querySelector('.table-responsive'));
        }
    }

    if (pagination && pagination.total > 0) {
        const start = (pagination.current_page - 1) * pagination.per_page + 1;
        const end = Math.min(pagination.current_page * pagination.per_page, pagination.total);
        countDiv.innerHTML = `<i class="bi bi-list-ul me-1"></i>Menampilkan ${start}-${end} dari ${pagination.total} data`;
        countDiv.style.display = 'block';
    } else {
        countDiv.style.display = 'none';
    }
}

// Update pagination
function updatePagination(pagination) {
    const container = document.getElementById('pagination-container');
    
    if (!pagination || pagination.total_pages <= 1) {
        container.innerHTML = '';
        return;
    }

    let html = '<nav><ul class="pagination justify-content-center">';
    
    // Previous button
    html += `<li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
        <a class="page-link" href="#" onclick="performSearch(${pagination.current_page - 1}); return false;">Previous</a>
    </li>`;

    // Page numbers
    for (let i = 1; i <= pagination.total_pages; i++) {
        if (i === 1 || i === pagination.total_pages || (i >= pagination.current_page - 2 && i <= pagination.current_page + 2)) {
            html += `<li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                <a class="page-link" href="#" onclick="performSearch(${i}); return false;">${i}</a>
            </li>`;
        } else if (i === pagination.current_page - 3 || i === pagination.current_page + 3) {
            html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }

    // Next button
    html += `<li class="page-item ${pagination.current_page === pagination.total_pages ? 'disabled' : ''}">
        <a class="page-link" href="#" onclick="performSearch(${pagination.current_page + 1}); return false;">Next</a>
    </li>`;

    html += '</ul></nav>';
    container.innerHTML = html;
}

// Helper functions
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function ucfirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function ucwords(str) {
    return str.replace(/\b\w/g, char => char.toUpperCase());
}

function formatDate(dateStr) {
    if (!dateStr) return 'N/A';
    const date = new Date(dateStr);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${months[date.getMonth()]} ${String(date.getDate()).padStart(2, '0')}, ${date.getFullYear()}`;
}

function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

// Sort functionality
function handleSort(sortField) {
    if (currentSort === sortField) {
        currentOrder = currentOrder === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort = sortField;
        currentOrder = 'asc';
    }

    // Update sort indicators
    document.querySelectorAll('.sortable').forEach(th => {
        th.classList.remove('asc', 'desc');
    });

    const clickedHeader = document.querySelector(`.sortable[data-sort="${sortField}"]`);
    if (clickedHeader) {
        clickedHeader.classList.add(currentOrder);
    }

    performSearch(1);
}

// Event listeners
document.getElementById('search-input').addEventListener('input', debounceSearch);
document.getElementById('status-filter').addEventListener('change', () => performSearch(1));
document.getElementById('method-filter').addEventListener('change', () => performSearch(1));
document.getElementById('start-date').addEventListener('change', () => performSearch(1));
document.getElementById('end-date').addEventListener('change', () => performSearch(1));

// Clear filters
document.getElementById('clear-filters').addEventListener('click', function() {
    document.getElementById('search-input').value = '';
    document.getElementById('status-filter').value = '';
    document.getElementById('method-filter').value = '';
    document.getElementById('start-date').value = '';
    document.getElementById('end-date').value = '';
    performSearch(1);
});

// Sort headers click handlers
document.querySelectorAll('.sortable').forEach(th => {
    th.addEventListener('click', function() {
        const sortField = this.getAttribute('data-sort');
        handleSort(sortField);
    });
});
</script>
<?= $this->endSection() ?>