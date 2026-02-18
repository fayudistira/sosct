<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    #admissions-table tbody tr {
        cursor: pointer;
    }

    #admissions-table tbody tr:hover {
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

<!-- Page Header -->
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Manajemen Pendaftaran</h4>
        <p class="text-muted mb-0">Manajemen Data Pendaftaran Siswa</p>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('admission/create') ?>" class="btn btn-dark-red disabled">
            <i class="bi bi-plus-circle me-1"></i> Input Pendaftaran
        </a>
    </div>
</div>

<!-- Search Bar -->
<div class="row mb-3">
    <div class="col-md-8">
        <div class="input-group">
            <input type="text" id="search-input" class="form-control" placeholder="Cari berdasarkan nama, email, nomor registrasi, atau telepon..." value="<?= esc($keyword ?? '') ?>">
            <select id="status-filter" class="form-select" style="max-width: 150px;">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            <button type="button" id="clear-filters" class="btn btn-outline-secondary" title="Clear Filters">
                <i class="bi bi-x-circle"></i>
            </button>
        </div>
    </div>
</div>

<!-- Admissions Table -->
<div class="dashboard-card position-relative">
    <div class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-file-earmark-text me-2"></i>Data Pendaftaran</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover compact-table mb-0" id="admissions-table">
                <thead>
                    <tr>
                        <th>No. Registrasi</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Telp.</th>
                        <th>Program</th>
                        <th>Status</th>
                        <th>Tgl. Daftar</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="admissions-tbody">
                    <?php if (!empty($admissions)): ?>
                        <?php foreach ($admissions as $admission): ?>
                            <tr>
                                <td class="fw-medium"><?= esc($admission['registration_number']) ?></td>
                                <td><?= esc($admission['full_name']) ?></td>
                                <td><?= esc($admission['email']) ?></td>
                                <td><?= esc($admission['phone']) ?></td>
                                <td><?= esc($admission['program_title'] ?? 'N/A') ?></td>
                                <td>
                                    <?php
                                    $badgeClass = match ($admission['status']) {
                                        'pending' => 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25',
                                        'approved' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
                                        'rejected' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25',
                                        default => 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25'
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst($admission['status']) ?></span>
                                </td>
                                <td><?= date('M d, Y', strtotime($admission['application_date'])) ?></td>
                                <td class="text-end table-actions">
                                    <a href="<?= base_url('admission/view/' . $admission['id']) ?>" class="btn btn-outline-dark-red btn-sm" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= base_url('admission/edit/' . $admission['id']) ?>" class="btn btn-outline-dark-red btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-dark-red btn-sm" title="Delete" onclick="confirmDelete(<?= $admission['id'] ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No admissions found</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div id="pagination-container" class="card-body">
        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center mb-0">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($currentPage ?? 1) == $i ? 'active' : '' ?>">
                            <a class="page-link" href="<?= base_url('admission?page=' . $i) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor ?>
                </ul>
            </nav>
        <?php endif ?>
    </div>
    
    <div id="no-results" class="text-center py-4" style="display: none;">
        <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
        <p class="text-muted mt-2">No admissions found matching your criteria.</p>
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

    // Show loading state
    const tbody = document.getElementById('admissions-tbody');
    const spinner = document.querySelector('.loading-spinner');
    const table = document.getElementById('admissions-table');
    
    table.classList.add('loading-overlay');
    spinner.style.display = 'block';

    // Build query params
    const params = new URLSearchParams();
    if (searchValue) params.append('q', searchValue);
    if (statusValue) params.append('status', statusValue);
    params.append('page', page);
    params.append('per_page', 10);

    // Make AJAX request
    fetch(`<?= base_url('api/admissions') ?>?${params.toString()}`, {
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
function updateTable(admissions) {
    const tbody = document.getElementById('admissions-tbody');
    const noResults = document.getElementById('no-results');
    const table = document.getElementById('admissions-table');

    if (admissions.length === 0) {
        tbody.innerHTML = '';
        table.style.display = 'none';
        noResults.style.display = 'block';
        return;
    }

    table.style.display = 'table';
    noResults.style.display = 'none';

    tbody.innerHTML = admissions.map(admission => {
        const badgeClass = getStatusBadgeClass(admission.status);
        
        return `
            <tr>
                <td class="fw-medium">${escapeHtml(admission.registration_number)}</td>
                <td>${escapeHtml(admission.full_name)}</td>
                <td>${escapeHtml(admission.email)}</td>
                <td>${escapeHtml(admission.phone)}</td>
                <td>${escapeHtml(admission.program_title || 'N/A')}</td>
                <td>
                    <span class="badge ${badgeClass}">${escapeHtml(ucfirst(admission.status))}</span>
                </td>
                <td>${formatDate(admission.application_date)}</td>
                <td class="text-end table-actions">
                    <a href="<?= base_url('admission/view/') ?>${admission.id}" class="btn btn-outline-dark-red btn-sm" title="View">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="<?= base_url('admission/edit/') ?>${admission.id}" class="btn btn-outline-dark-red btn-sm" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button type="button" class="btn btn-outline-dark-red btn-sm" title="Delete" onclick="confirmDelete(${admission.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

// Get status badge class
function getStatusBadgeClass(status) {
    const classes = {
        'pending': 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25',
        'approved': 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
        'rejected': 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'
    };
    return classes[status] || 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25';
}

// Update pagination
function updatePagination(pagination) {
    const container = document.getElementById('pagination-container');
    
    if (!pagination || pagination.total_pages <= 1) {
        container.innerHTML = '';
        return;
    }

    let html = '<nav><ul class="pagination justify-content-center mb-0">';
    
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

function formatDate(dateStr) {
    if (!dateStr) return 'N/A';
    const date = new Date(dateStr);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${months[date.getMonth()]} ${String(date.getDate()).padStart(2, '0')}, ${date.getFullYear()}`;
}

// Event listeners
document.getElementById('search-input').addEventListener('input', debounceSearch);
document.getElementById('status-filter').addEventListener('change', () => performSearch(1));

// Clear filters
document.getElementById('clear-filters').addEventListener('click', function() {
    document.getElementById('search-input').value = '';
    document.getElementById('status-filter').value = '';
    performSearch(1);
});

// Delete confirmation
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this admission?')) {
        fetch('<?= base_url('admission/delete/') ?>' + id, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(() => {
            performSearch(currentPage);
        });
    }
}
</script>
<?= $this->endSection() ?>