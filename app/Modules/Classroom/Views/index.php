<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    #classrooms-table tbody tr {
        cursor: pointer;
    }

    #classrooms-table tbody tr:hover {
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

<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold"><i class="bi bi-door-open me-2"></i>Manajemen Kelas</h2>
        <p class="text-muted">Pengaturan Kelas, Jadwal dan Siswa</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= base_url('classroom/create') ?>" class="btn btn-dark-red">
            <i class="bi bi-plus-lg me-1"></i> Buat Kelas Baru
        </a>
    </div>
</div>

<div class="card dashboard-card position-relative">
    <div class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Daftar Kelas</span>
        <div class="d-flex gap-2">
            <select id="status-filter" class="form-select form-select-sm" style="width: 120px;">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="completed">Completed</option>
            </select>
            <div class="input-group input-group-sm" style="width: 250px;">
                <input type="text" id="search-input" class="form-control" placeholder="Search classes...">
                <button class="btn btn-outline-secondary" type="button" id="clear-filters"><i class="bi bi-x-circle"></i></button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover compact-table mb-0" id="classrooms-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No.</th>
                        <th class="sortable" data-sort="title">Nama Kelas <span class="sort-icon"></span></th>
                        <th class="sortable" data-sort="batch">Angkatan / Tingkat <span class="sort-icon"></span></th>
                        <th class="sortable" data-sort="program">Program <span class="sort-icon"></span></th>
                        <th>Jadwal</th>
                        <th class="sortable" data-sort="status">Status <span class="sort-icon"></span></th>
                        <th class="sortable" data-sort="start_date">Periode <span class="sort-icon"></span></th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="classrooms-tbody">
                    <?php if (!empty($classrooms)): ?>
                        <?php 
                        $startIndex = (($currentPage ?? 1) - 1) * 10 + 1;
                        foreach ($classrooms as $index => $class): 
                        ?>
                            <tr>
                                <td class="text-center text-muted"><?= $startIndex + $index ?></td>
                                <td>
                                    <div class="fw-bold"><?= esc($class['title']) ?></div>
                                </td>
                                <td><?= esc($class['batch'] ?: '-') ?> / <?= esc($class['grade'] ?: '-') ?></td>
                                <td><?= esc($class['program'] ?: '-') ?></td>
                                <td>
                                    <?php
                                    $schedule = json_decode($class['schedule'] ?? '[]', true);
                                    if (!empty($schedule)) {
                                        $keys = array_keys($schedule);
                                        echo count($keys) . " Subjects";
                                    } else {
                                        echo "-";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($class['status'] === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php elseif ($class['status'] === 'completed'): ?>
                                        <span class="badge bg-primary">Completed</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= $class['start_date'] ? date('d M Y', strtotime($class['start_date'])) : 'N/A' ?> -
                                        <?= $class['end_date'] ? date('d M Y', strtotime($class['end_date'])) : 'N/A' ?>
                                    </small>
                                </td>
                                <td class="text-center table-actions">
                                    <a href="<?= base_url('classroom/show/' . $class['id']) ?>" class="btn btn-sm btn-info text-white" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= base_url('classroom/edit/' . $class['id']) ?>" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?= base_url('classroom/delete/' . $class['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this classroom?')">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No classrooms found.</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div id="pagination-container" class="card-body">
    </div>
    
    <div id="no-results" class="text-center py-4" style="display: none;">
        <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
        <p class="text-muted mt-2">No classrooms found matching your criteria.</p>
    </div>
</div>

<script>
let searchTimeout;
let currentPage = 1;
let currentSort = 'start_date';
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

    // Show loading state
    const tbody = document.getElementById('classrooms-tbody');
    const spinner = document.querySelector('.loading-spinner');
    const table = document.getElementById('classrooms-table');
    
    table.classList.add('loading-overlay');
    spinner.style.display = 'block';

    // Build query params
    const params = new URLSearchParams();
    if (searchValue) params.append('q', searchValue);
    if (statusValue) params.append('status', statusValue);
    params.append('page', page);
    params.append('per_page', 10);
    params.append('sort', currentSort);
    params.append('order', currentOrder);

    // Make AJAX request
    fetch(`<?= base_url('api/classrooms') ?>?${params.toString()}`, {
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
function updateTable(classrooms) {
    const tbody = document.getElementById('classrooms-tbody');
    const noResults = document.getElementById('no-results');
    const table = document.getElementById('classrooms-table');

    if (classrooms.length === 0) {
        tbody.innerHTML = '';
        table.style.display = 'none';
        noResults.style.display = 'block';
        return;
    }

    table.style.display = 'table';
    noResults.style.display = 'none';

    const startIndex = (currentPage - 1) * 10 + 1;

    tbody.innerHTML = classrooms.map((classroom, index) => {
        const schedule = classroom.schedule ? JSON.parse(classroom.schedule) : [];
        const scheduleCount = schedule ? Object.keys(schedule).length : 0;
        const statusBadge = getStatusBadge(classroom.status);
        
        return `
            <tr>
                <td class="text-center text-muted">${startIndex + index}</td>
                <td>
                    <div class="fw-bold">${escapeHtml(classroom.title)}</div>
                </td>
                <td>${escapeHtml(classroom.batch || '-')} / ${escapeHtml(classroom.grade || '-')}</td>
                <td>${escapeHtml(classroom.program || '-')}</td>
                <td>${scheduleCount > 0 ? scheduleCount + ' Subjects' : '-'}</td>
                <td>${statusBadge}</td>
                <td>
                    <small class="text-muted">
                        ${classroom.start_date ? formatDate(classroom.start_date) : 'N/A'} -
                        ${classroom.end_date ? formatDate(classroom.end_date) : 'N/A'}
                    </small>
                </td>
                <td class="text-center table-actions">
                    <a href="<?= base_url('classroom/show/') ?>${classroom.id}" class="btn btn-sm btn-info text-white" title="View Details">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="<?= base_url('classroom/edit/') ?>${classroom.id}" class="btn btn-sm btn-primary" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="<?= base_url('classroom/delete/') ?>${classroom.id}" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this classroom?')">
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
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
        const tableContainer = document.querySelector('.dashboard-card');
        tableContainer.insertBefore(countDiv, tableContainer.querySelector('.card-body'));
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

// Get status badge HTML
function getStatusBadge(status) {
    const badges = {
        'active': '<span class="badge bg-success">Active</span>',
        'completed': '<span class="badge bg-primary">Completed</span>',
        'inactive': '<span class="badge bg-secondary">Inactive</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
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

function formatDate(dateStr) {
    if (!dateStr) return 'N/A';
    const date = new Date(dateStr);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${String(date.getDate()).padStart(2, '0')} ${months[date.getMonth()]} ${date.getFullYear()}`;
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

// Clear filters
document.getElementById('clear-filters').addEventListener('click', function() {
    document.getElementById('search-input').value = '';
    document.getElementById('status-filter').value = '';
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