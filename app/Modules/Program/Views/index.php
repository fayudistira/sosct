<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    #programs-table tbody tr {
        cursor: pointer;
    }

    #programs-table tbody tr:hover {
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
    <div class="row mb-3">
        <div class="col-md-6">
            <h3>Programs</h3>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                <i class="bi bi-file-earmark-arrow-up"></i> Bulk Upload
            </button>
            <a href="<?= base_url('program/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Program
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <?php if (session()->getFlashdata('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <?= session()->getFlashdata('warning') ?>
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
                    <div class="col-md-4">
                        <input type="text" name="search" id="search-input" class="form-control"
                            placeholder="Search programs..." value="<?= esc($keyword ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="status" id="status-filter" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($status ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="category" id="category-filter" class="form-control"
                            placeholder="Category" value="<?= esc($category ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="clear-filters" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle"></i> Clear
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Programs Table -->
    <div class="card position-relative">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="programs-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">No.</th>
                            <th class="sortable" data-sort="title">Title <span class="sort-icon"></span></th>
                            <th class="sortable" data-sort="category">Category <span class="sort-icon"></span></th>
                            <th class="sortable" data-sort="sub_category">Sub Category <span class="sort-icon"></span></th>
                            <th class="sortable" data-sort="duration">Duration <span class="sort-icon"></span></th>
                            <th class="sortable" data-sort="registration_fee">Registration Fee <span class="sort-icon"></span></th>
                            <th class="sortable" data-sort="tuition_fee">Tuition Fee <span class="sort-icon"></span></th>
                            <th class="sortable" data-sort="discount">Discount <span class="sort-icon"></span></th>
                            <th class="sortable" data-sort="status">Status <span class="sort-icon"></span></th>
                            <th>Thumbnail</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="programs-tbody">
                        <?php if (!empty($programs)): ?>
                            <?php 
                            $startIndex = (($currentPage ?? 1) - 1) * 10 + 1;
                            foreach ($programs as $index => $program): 
                            ?>
                                <tr>
                                    <td class="text-center text-muted"><?= $startIndex + $index ?></td>
                                    <td><strong><?= esc($program['title']) ?></strong></td>
                                    <td><?= esc($program['category'] ?? '-') ?></td>
                                    <td><?= esc($program['sub_category'] ?? '-') ?></td>
                                    <td><?= esc($program['duration'] ?? '-') ?></td>
                                    <td>Rp <?= number_format($program['registration_fee'], 0, ',', '.') ?></td>
                                    <td>Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?></td>
                                    <td><?= number_format($program['discount'], 2) ?>%</td>
                                    <td>
                                        <?php if ($program['status'] === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($program['thumbnail'])): ?>
                                            <img src="<?= base_url('uploads/programs/thumbs/' . $program['thumbnail']) ?>"
                                                alt="Thumbnail" style="width: 50px; height: 50px; object-fit: cover;"
                                                class="rounded">
                                        <?php else: ?>
                                            <span class="text-muted">No image</span>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('program/view/' . $program['id']) ?>"
                                            class="btn btn-sm btn-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url('program/edit/' . $program['id']) ?>"
                                            class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= base_url('program/delete/' . $program['id']) ?>"
                                            class="btn btn-sm btn-danger" title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this program?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center">No programs found.</td>
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
                <p class="text-muted mt-2">No programs found matching your criteria.</p>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUploadModalLabel">
                    <i class="bi bi-file-earmark-arrow-up me-2"></i>Bulk Upload Programs
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('program/bulk-upload') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Instructions:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Download the Excel template below</li>
                            <li>Fill in your program data following the format</li>
                            <li>Upload the completed file</li>
                            <li>Maximum file size: 5MB</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <a href="<?= base_url('program/download-template') ?>" class="btn btn-outline-primary w-100">
                            <i class="bi bi-download me-2"></i>Download Excel Template
                        </a>
                    </div>

                    <div class="mb-3">
                        <label for="excel_file" class="form-label">Upload Excel File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="excel_file" name="excel_file"
                            accept=".xlsx,.xls" required>
                        <small class="text-muted">Accepted formats: .xlsx, .xls</small>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> Make sure all required fields are filled correctly.
                        Rows with errors will be skipped.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-upload me-2"></i>Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let searchTimeout;
let currentPage = 1;
let currentSort = 'title';
let currentOrder = 'asc';

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
    const categoryValue = document.getElementById('category-filter').value;

    // Show loading state
    const tbody = document.getElementById('programs-tbody');
    const spinner = document.querySelector('.loading-spinner');
    const table = document.getElementById('programs-table');
    
    table.classList.add('loading-overlay');
    spinner.style.display = 'block';

    // Build query params
    const params = new URLSearchParams();
    if (searchValue) params.append('q', searchValue);
    if (statusValue) params.append('status', statusValue);
    if (categoryValue) params.append('category', categoryValue);
    params.append('page', page);
    params.append('per_page', 10);
    params.append('sort', currentSort);
    params.append('order', currentOrder);

    // Make AJAX request
    fetch(`<?= base_url('api/programs') ?>?${params.toString()}`, {
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
function updateTable(programs) {
    const tbody = document.getElementById('programs-tbody');
    const noResults = document.getElementById('no-results');
    const table = document.getElementById('programs-table');

    if (programs.length === 0) {
        tbody.innerHTML = '';
        table.style.display = 'none';
        noResults.style.display = 'block';
        return;
    }

    table.style.display = 'table';
    noResults.style.display = 'none';

    const startIndex = (currentPage - 1) * 10 + 1;

    tbody.innerHTML = programs.map((program, index) => {
        const statusBadge = program.status === 'active'
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-secondary">Inactive</span>';
        
        const thumbnail = program.thumbnail
            ? `<img src="<?= base_url('uploads/programs/thumbs/') ?>${escapeHtml(program.thumbnail)}" alt="Thumbnail" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">`
            : '<span class="text-muted">No image</span>';

        return `
            <tr>
                <td class="text-center text-muted">${startIndex + index}</td>
                <td><strong>${escapeHtml(program.title)}</strong></td>
                <td>${escapeHtml(program.category || '-')}</td>
                <td>${escapeHtml(program.sub_category || '-')}</td>
                <td>${escapeHtml(program.duration || '-')}</td>
                <td>Rp ${formatNumber(program.registration_fee || 0)}</td>
                <td>Rp ${formatNumber(program.tuition_fee || 0)}</td>
                <td>${formatNumber(program.discount || 0, 2)}%</td>
                <td>${statusBadge}</td>
                <td>${thumbnail}</td>
                <td>
                    <a href="<?= base_url('program/view/') ?>${program.id}" class="btn btn-sm btn-info" title="View">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="<?= base_url('program/edit/') ?>${program.id}" class="btn btn-sm btn-warning" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="<?= base_url('program/delete/') ?>${program.id}" class="btn btn-sm btn-danger" title="Delete"
                        onclick="return confirm('Are you sure you want to delete this program?')">
                        <i class="bi bi-trash"></i>
                    </a>
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
        const tableContainer = document.querySelector('.card-body');
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

function formatNumber(num, decimals = 0) {
    return new Intl.NumberFormat('id-ID', { 
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals 
    }).format(num);
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
document.getElementById('category-filter').addEventListener('input', debounceSearch);

// Clear filters
document.getElementById('clear-filters').addEventListener('click', function() {
    document.getElementById('search-input').value = '';
    document.getElementById('status-filter').value = '';
    document.getElementById('category-filter').value = '';
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