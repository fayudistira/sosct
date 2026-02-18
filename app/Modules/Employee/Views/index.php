<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    #employees-table tbody tr {
        cursor: pointer;
    }

    #employees-table tbody tr:hover {
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

    .avatar-sm {
        width: 32px;
        height: 32px;
        object-fit: cover;
    }
</style>

<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Employee Management</h4>
        <p class="text-muted mb-0">Manage your staff and employment records</p>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('admin/employee/create') ?>" class="btn btn-dark-red">
            <i class="bi bi-person-plus me-1"></i> Add Employee
        </a>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-3">
    <div class="card-body">
        <form id="search-form">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" id="search-input" class="form-control" placeholder="Search by name, email, staff ID...">
                </div>
                <div class="col-md-3">
                    <select id="status-filter" class="form-select">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="resigned">Resigned</option>
                        <option value="terminated">Terminated</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" id="department-filter" class="form-control" placeholder="Department">
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

<div class="dashboard-card position-relative">
    <div class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table compact-table mb-0" id="employees-table">
                <thead>
                    <tr>
                        <th>Staff ID</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Join Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="employees-tbody">
                    <?php if (empty($employees)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No employees found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($employees as $emp): ?>
                            <tr>
                                <td class="fw-bold"><?= esc($emp['staff_number']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= $emp['photo'] ? base_url('uploads/' . $emp['photo']) : 'https://ui-avatars.com/api/?name=' . urlencode($emp['full_name']) . '&background=8B0000&color=fff&size=32' ?>" 
                                             class="avatar-sm rounded-circle me-2" alt="Photo">
                                        <div>
                                            <div class="fw-medium"><?= esc($emp['full_name']) ?></div>
                                            <small class="text-muted"><?= esc($emp['email']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= esc($emp['position']) ?></td>
                                <td><?= esc($emp['department'] ?? '-') ?></td>
                                <td>
                                    <?php 
                                    $statusClass = [
                                        'active' => 'bg-success',
                                        'inactive' => 'bg-secondary',
                                        'resigned' => 'bg-warning text-dark',
                                        'terminated' => 'bg-danger'
                                    ][$emp['status']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= ucfirst($emp['status']) ?></span>
                                </td>
                                <td><?= date('d M Y', strtotime($emp['hire_date'])) ?></td>
                                <td class="text-end table-actions">
                                    <a href="<?= base_url('admin/employee/view/' . $emp['id']) ?>" class="btn btn-outline-primary" title="View Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= base_url('admin/employee/edit/' . $emp['id']) ?>" class="btn btn-outline-dark-red" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div id="pagination-container" class="card-body">
    </div>
    
    <div id="no-results" class="text-center py-4" style="display: none;">
        <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
        <p class="text-muted mt-2">No employees found matching your criteria.</p>
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
    const departmentValue = document.getElementById('department-filter').value;

    // Show loading state
    const tbody = document.getElementById('employees-tbody');
    const spinner = document.querySelector('.loading-spinner');
    const table = document.getElementById('employees-table');
    
    table.classList.add('loading-overlay');
    spinner.style.display = 'block';

    // Build query params
    const params = new URLSearchParams();
    if (searchValue) params.append('q', searchValue);
    if (statusValue) params.append('status', statusValue);
    if (departmentValue) params.append('department', departmentValue);
    params.append('page', page);
    params.append('per_page', 10);

    // Make AJAX request
    fetch(`<?= base_url('api/employees') ?>?${params.toString()}`, {
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
function updateTable(employees) {
    const tbody = document.getElementById('employees-tbody');
    const noResults = document.getElementById('no-results');
    const table = document.getElementById('employees-table');

    if (employees.length === 0) {
        tbody.innerHTML = '';
        table.style.display = 'none';
        noResults.style.display = 'block';
        return;
    }

    table.style.display = 'table';
    noResults.style.display = 'none';

    tbody.innerHTML = employees.map(emp => {
        const statusBadge = getStatusBadge(emp.status);
        const photoUrl = emp.photo 
            ? `<?= base_url('uploads/') ?>${escapeHtml(emp.photo)}`
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(emp.full_name || 'Unknown')}&background=8B0000&color=fff&size=32`;
        
        return `
            <tr>
                <td class="fw-bold">${escapeHtml(emp.staff_number)}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="${photoUrl}" class="avatar-sm rounded-circle me-2" alt="Photo">
                        <div>
                            <div class="fw-medium">${escapeHtml(emp.full_name)}</div>
                            <small class="text-muted">${escapeHtml(emp.email)}</small>
                        </div>
                    </div>
                </td>
                <td>${escapeHtml(emp.position)}</td>
                <td>${escapeHtml(emp.department || '-')}</td>
                <td>${statusBadge}</td>
                <td>${formatDate(emp.hire_date)}</td>
                <td class="text-end table-actions">
                    <a href="<?= base_url('admin/employee/view/') ?>${emp.id}" class="btn btn-outline-primary" title="View Detail">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="<?= base_url('admin/employee/edit/') ?>${emp.id}" class="btn btn-outline-dark-red" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                </td>
            </tr>
        `;
    }).join('');
}

// Get status badge HTML
function getStatusBadge(status) {
    const badges = {
        'active': '<span class="badge bg-success">Active</span>',
        'inactive': '<span class="badge bg-secondary">Inactive</span>',
        'resigned': '<span class="badge bg-warning text-dark">Resigned</span>',
        'terminated': '<span class="badge bg-danger">Terminated</span>'
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

// Event listeners
document.getElementById('search-input').addEventListener('input', debounceSearch);
document.getElementById('status-filter').addEventListener('change', () => performSearch(1));
document.getElementById('department-filter').addEventListener('input', debounceSearch);

// Clear filters
document.getElementById('clear-filters').addEventListener('click', function() {
    document.getElementById('search-input').value = '';
    document.getElementById('status-filter').value = '';
    document.getElementById('department-filter').value = '';
    performSearch(1);
});
</script>
<?= $this->endSection() ?>
