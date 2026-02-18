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

    #invoices-table tbody tr {
        cursor: pointer;
    }

    #invoices-table tbody tr:hover {
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
</style>

<div class="container-fluid">
    <div class="invoice-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-0">Faktur</h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?= base_url('invoice/create') ?>" class="btn btn-light">
                    <i class="bi bi-plus-circle"></i> Buat Faktur Baru
                </a>
                <a href="<?= base_url('invoice/extend') ?>" class="btn btn-outline-light">
                    <i class="bi bi-arrow-repeat"></i> Perpanjang Faktur
                </a>
                <a href="<?= base_url('invoice/bulk-extend') ?>" class="btn btn-outline-light">
                    <i class="bi bi-layers"></i> Perpanjang Massal
                </a>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
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
                            placeholder="Cari..." value="<?= esc($keyword ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="status" id="status-filter" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="unpaid" <?= ($status ?? '') === 'unpaid' ? 'selected' : '' ?>>Belum Dibayar</option>
                            <option value="paid" <?= ($status ?? '') === 'paid' ? 'selected' : '' ?>>Lunas</option>
                            <option value="partially_paid" <?= ($status ?? '') === 'partially_paid' ? 'selected' : '' ?>>Dibayar Sebagian</option>
                            <option value="extended" <?= ($status ?? '') === 'extended' ? 'selected' : '' ?>>Diperpanjang</option>
                            <option value="cancelled" <?= ($status ?? '') === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                            <option value="expired" <?= ($status ?? '') === 'expired' ? 'selected' : '' ?>>Kedaluwarsa</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="type" id="type-filter" class="form-select">
                            <option value="">Semua Jenis</option>
                            <option value="registration_fee" <?= ($type ?? '') === 'registration_fee' ? 'selected' : '' ?>>Biaya Registrasi</option>
                            <option value="tuition_fee" <?= ($type ?? '') === 'tuition_fee' ? 'selected' : '' ?>>Biaya Program</option>
                            <option value="miscellaneous_fee" <?= ($type ?? '') === 'miscellaneous_fee' ? 'selected' : '' ?>>Biaya Lainnya</option>
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

    <!-- Invoices Table -->
    <div class="card">
        <div class="card-body position-relative">
            <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover" id="invoices-table">
                    <thead>
                        <tr>
                            <th>No. Faktur</th>
                            <th>Siswa</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Tanggal Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="invoices-tbody">
                        <?php if (!empty($invoices)): ?>
                            <?php foreach ($invoices as $invoice): ?>
                                <tr>
                                    <td><?= esc($invoice['invoice_number']) ?></td>
                                    <td>
                                        <?= esc($invoice['student']['full_name'] ?? 'N/A') ?><br>
                                        <small class="text-muted"><?= esc($invoice['registration_number']) ?></small>
                                    </td>
                                    <td><?= ucwords(str_replace('_', ' ', $invoice['invoice_type'])) ?></td>
                                    <td>Rp <?= number_format($invoice['amount'], 0, ',', '.') ?></td>
                                    <td><?= date('M d, Y', strtotime($invoice['due_date'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?php
                                                                if ($invoice['status'] === 'paid') echo 'success';
                                                                elseif ($invoice['status'] === 'partially_paid') echo 'info';
                                                                elseif ($invoice['status'] === 'unpaid') echo 'warning';
                                                                elseif ($invoice['status'] === 'expired') echo 'danger';
                                                                elseif ($invoice['status'] === 'extended') echo 'primary';
                                                                else echo 'secondary';
                                                                ?>">
                                            <?= str_replace('_', ' ', ucfirst($invoice['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('invoice/view/' . $invoice['id']) ?>"
                                            class="btn btn-sm btn-info" title="Lihat Detail"><i class="bi bi-eye"></i></a>
                                        <a href="<?= base_url('invoice/pdf/' . $invoice['id']) ?>"
                                            class="btn btn-sm btn-danger" target="_blank" title="Unduh PDF"><i class="bi bi-file-pdf"></i></a>

                                        <?php if ($invoice['status'] === 'unpaid' || $invoice['status'] === 'expired'): ?>
                                            <a href="<?= base_url('invoice/cancel/' . $invoice['id']) ?>"
                                                class="btn btn-sm btn-secondary" title="Batalkan Faktur"
                                                onclick="return confirm('Apakah Anda yakin ingin membatalkan faktur ini? Tindakan ini tidak dapat dibatalkan.')">
                                                <i class="bi bi-x-circle"></i>
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-light disabled" title="Terkunci: Pembayaran Sedang Berjalan/Selesai">
                                                <i class="bi bi-lock-fill"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada faktur ditemukan</td>
                            </tr>
                        <?php endif ?>
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
                <p class="text-muted mt-2">Tidak ada faktur ditemukan.</p>
            </div>
        </div>
    </div>
</div>

<script>
let searchTimeout;
let currentPage = 1;

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
    const typeValue = document.getElementById('type-filter').value;
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    // Show loading state
    const tbody = document.getElementById('invoices-tbody');
    const spinner = document.querySelector('.loading-spinner');
    const table = document.getElementById('invoices-table');
    
    table.classList.add('loading-overlay');
    spinner.style.display = 'block';

    // Build query params
    const params = new URLSearchParams();
    if (searchValue) params.append('q', searchValue);
    if (statusValue) params.append('status', statusValue);
    if (typeValue) params.append('type', typeValue);
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    params.append('page', page);
    params.append('per_page', 10);

    // Make AJAX request
    fetch(`<?= base_url('api/invoices') ?>?${params.toString()}`, {
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
function updateTable(invoices) {
    const tbody = document.getElementById('invoices-tbody');
    const noResults = document.getElementById('no-results');
    const table = document.getElementById('invoices-table');

    if (invoices.length === 0) {
        tbody.innerHTML = '';
        table.style.display = 'none';
        noResults.style.display = 'block';
        return;
    }

    table.style.display = 'table';
    noResults.style.display = 'none';

    tbody.innerHTML = invoices.map(invoice => {
        const statusBadge = getStatusBadge(invoice.status);
        const cancelBtn = (invoice.status === 'unpaid' || invoice.status === 'expired')
            ? `<a href="<?= base_url('invoice/cancel/') ?>${invoice.id}" class="btn btn-sm btn-secondary" title="Batalkan Faktur" onclick="return confirm('Apakah Anda yakin ingin membatalkan faktur ini? Tindakan ini tidak dapat dibatalkan.')"><i class="bi bi-x-circle"></i></a>`
            : '<button class="btn btn-sm btn-light disabled" title="Terkunci: Pembayaran Sedang Berjalan/Selesai"><i class="bi bi-lock-fill"></i></button>';

        return `
            <tr>
                <td>${escapeHtml(invoice.invoice_number)}</td>
                <td>
                    ${escapeHtml(invoice.student?.full_name || 'N/A')}<br>
                    <small class="text-muted">${escapeHtml(invoice.registration_number)}</small>
                </td>
                <td>${escapeHtml(ucwords((invoice.invoice_type || '').replace(/_/g, ' ')))}</td>
                <td>Rp ${formatNumber(invoice.amount || 0)}</td>
                <td>${formatDate(invoice.due_date)}</td>
                <td>${statusBadge}</td>
                <td>
                    <a href="<?= base_url('invoice/view/') ?>${invoice.id}" class="btn btn-sm btn-info" title="Lihat Detail"><i class="bi bi-eye"></i></a>
                    <a href="<?= base_url('invoice/pdf/') ?>${invoice.id}" class="btn btn-sm btn-danger" target="_blank" title="Unduh PDF"><i class="bi bi-file-pdf"></i></a>
                    ${cancelBtn}
                </td>
            </tr>
        `;
    }).join('');
}

// Get status badge HTML
function getStatusBadge(status) {
    const statusColors = {
        'paid': 'success',
        'partially_paid': 'info',
        'unpaid': 'warning',
        'expired': 'danger',
        'extended': 'primary',
        'cancelled': 'secondary'
    };
    const color = statusColors[status] || 'secondary';
    const label = status.replace(/_/g, ' ').charAt(0).toUpperCase() + status.replace(/_/g, ' ').slice(1);
    return `<span class="badge bg-${color}">${escapeHtml(label)}</span>`;
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

// Event listeners
document.getElementById('search-input').addEventListener('input', debounceSearch);
document.getElementById('status-filter').addEventListener('change', () => performSearch(1));
document.getElementById('type-filter').addEventListener('change', () => performSearch(1));
document.getElementById('start-date').addEventListener('change', () => performSearch(1));
document.getElementById('end-date').addEventListener('change', () => performSearch(1));

// Clear filters
document.getElementById('clear-filters').addEventListener('click', function() {
    document.getElementById('search-input').value = '';
    document.getElementById('status-filter').value = '';
    document.getElementById('type-filter').value = '';
    document.getElementById('start-date').value = '';
    document.getElementById('end-date').value = '';
    performSearch(1);
});
</script>
<?= $this->endSection() ?>