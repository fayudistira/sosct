<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    #users-table tbody tr {
        cursor: pointer;
    }

    #users-table tbody tr:hover {
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
        <div class="col">
            <h1 class="h3 mb-0">User Management</h1>
            <p class="text-muted">Manage user accounts and roles</p>
        </div>
    </div>

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Search and Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form id="search-form">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" id="search-input" class="form-control" placeholder="Search by username...">
                    </div>
                    <div class="col-md-3">
                        <select id="status-filter" class="form-select">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="role-filter" class="form-select">
                            <option value="">All Roles</option>
                            <option value="superadmin">Super Admin</option>
                            <option value="admin">Admin</option>
                            <option value="frontline">Frontline</option>
                            <option value="instructor">Instructor</option>
                            <option value="student">Student</option>
                        </select>
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

    <div class="card position-relative">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="users-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">No.</th>
                            <th class="sortable" data-sort="id">ID <span class="sort-icon"></span></th>
                            <th class="sortable" data-sort="username">Username <span class="sort-icon"></span></th>
                            <th class="sortable" data-sort="email">Email <span class="sort-icon"></span></th>
                            <th>Roles</th>
                            <th class="sortable" data-sort="active">Status <span class="sort-icon"></span></th>
                            <th class="sortable" data-sort="last_active">Last Active <span class="sort-icon"></span></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-tbody">
                        <?php 
                        $startIndex = (($currentPage ?? 1) - 1) * 10 + 1;
                        foreach ($users as $index => $user): 
                        ?>
                            <tr>
                                <td class="text-center text-muted"><?= $startIndex + $index ?></td>
                                <td><?= $user['id'] ?></td>
                                <td><?= esc($user['username']) ?></td>
                                <td>
                                    <?php
                                    $userEntity = model('CodeIgniter\Shield\Models\UserModel')->findById($user['id']);
                                    $identities = $userEntity->getEmailIdentity();
                                    echo esc($identities->secret ?? '-');
                                    ?>
                                </td>
                                <td>
                                    <?php if (!empty($user['groups'])): ?>
                                        <?php foreach ($user['groups'] as $group): ?>
                                            <span class="badge bg-primary"><?= esc(ucfirst($group)) ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No Role</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user['active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $user['last_active'] ? date('M d, Y H:i', strtotime($user['last_active'])) : 'Never' ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('users/edit/' . $user['id']) ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i> Edit Roles
                                    </a>
                                    <a href="<?= base_url('users/toggle-status/' . $user['id']) ?>" 
                                       class="btn btn-sm btn-<?= $user['active'] ? 'warning' : 'success' ?>"
                                       onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-<?= $user['active'] ? 'x-circle' : 'check-circle' ?>"></i>
                                        <?= $user['active'] ? 'Deactivate' : 'Activate' ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div id="pagination-container" class="card-body">
        </div>
        
        <div id="no-results" class="text-center py-4" style="display: none;">
            <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-2">No users found matching your criteria.</p>
        </div>
    </div>
</div>

<script>
let searchTimeout;
let currentPage = 1;
let currentSort = 'id';
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
    const roleValue = document.getElementById('role-filter').value;

    // Show loading state
    const tbody = document.getElementById('users-tbody');
    const spinner = document.querySelector('.loading-spinner');
    const table = document.getElementById('users-table');
    
    table.classList.add('loading-overlay');
    spinner.style.display = 'block';

    // Build query params
    const params = new URLSearchParams();
    if (searchValue) params.append('q', searchValue);
    if (statusValue) params.append('status', statusValue);
    if (roleValue) params.append('role', roleValue);
    params.append('page', page);
    params.append('per_page', 10);
    params.append('sort', currentSort);
    params.append('order', currentOrder);

    // Make AJAX request
    fetch(`<?= base_url('api/users') ?>?${params.toString()}`, {
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
function updateTable(users) {
    const tbody = document.getElementById('users-tbody');
    const noResults = document.getElementById('no-results');
    const table = document.getElementById('users-table');

    if (users.length === 0) {
        tbody.innerHTML = '';
        table.style.display = 'none';
        noResults.style.display = 'block';
        return;
    }

    table.style.display = 'table';
    noResults.style.display = 'none';

    const startIndex = (currentPage - 1) * 10 + 1;

    tbody.innerHTML = users.map((user, index) => {
        const statusBadge = user.active 
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-danger">Inactive</span>';
        
        const rolesBadges = user.groups && user.groups.length > 0
            ? user.groups.map(g => `<span class="badge bg-primary">${escapeHtml(ucfirst(g))}</span>`).join(' ')
            : '<span class="badge bg-secondary">No Role</span>';
        
        const lastActive = user.last_active 
            ? formatDateTime(user.last_active)
            : 'Never';
        
        const toggleBtn = user.active
            ? `<a href="<?= base_url('users/toggle-status/') ?>${user.id}" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure?')"><i class="bi bi-x-circle"></i> Deactivate</a>`
            : `<a href="<?= base_url('users/toggle-status/') ?>${user.id}" class="btn btn-sm btn-success" onclick="return confirm('Are you sure?')"><i class="bi bi-check-circle"></i> Activate</a>`;

        return `
            <tr>
                <td class="text-center text-muted">${startIndex + index}</td>
                <td>${user.id}</td>
                <td>${escapeHtml(user.username)}</td>
                <td>${escapeHtml(user.email)}</td>
                <td>${rolesBadges}</td>
                <td>${statusBadge}</td>
                <td>${lastActive}</td>
                <td>
                    <a href="<?= base_url('users/edit/') ?>${user.id}" class="btn btn-sm btn-primary">
                        <i class="bi bi-pencil"></i> Edit Roles
                    </a>
                    ${toggleBtn}
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

function ucfirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function formatDateTime(dateStr) {
    if (!dateStr) return 'Never';
    const date = new Date(dateStr);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${months[date.getMonth()]} ${String(date.getDate()).padStart(2, '0')}, ${date.getFullYear()} ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
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
document.getElementById('role-filter').addEventListener('change', () => performSearch(1));

// Clear filters
document.getElementById('clear-filters').addEventListener('click', function() {
    document.getElementById('search-input').value = '';
    document.getElementById('status-filter').value = '';
    document.getElementById('role-filter').value = '';
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
