<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<style>
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
        <h2 class="fw-bold"><i class="bi bi-building me-2"></i>Dormitory Management</h2>
        <p class="text-muted">Manage dormitory rooms and student assignments</p>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-outline-success me-2" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
            <i class="bi bi-file-earmark-arrow-up me-1"></i> Bulk Upload
        </button>
        <a href="<?= base_url('dormitory/search') ?>" class="btn btn-outline-primary me-2">
            <i class="bi bi-search me-1"></i> Search Student
        </a>
        <a href="<?= base_url('dormitory/create') ?>" class="btn btn-dark-red">
            <i class="bi bi-plus-lg me-1"></i> Add New Room
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card dashboard-card stat-card">
            <div class="card-body">
                <div class="stat-label">Total Rooms</div>
                <div class="stat-number"><?= count($dormitories) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-card stat-card">
            <div class="card-body">
                <div class="stat-label">Available</div>
                <div class="stat-number text-success">
                    <?= count(array_filter($dormitories, fn($d) => $d['status'] === 'available')) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-card stat-card">
            <div class="card-body">
                <div class="stat-label">Full</div>
                <div class="stat-number text-warning">
                    <?= count(array_filter($dormitories, fn($d) => $d['status'] === 'full')) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-card stat-card">
            <div class="card-body">
                <div class="stat-label">Maintenance</div>
                <div class="stat-number text-secondary">
                    <?= count(array_filter($dormitories, fn($d) => $d['status'] === 'maintenance')) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Dormitory List</span>
        <div class="input-group input-group-sm" style="width: 250px;">
            <input type="text" class="form-control" placeholder="Search rooms..." id="searchInput">
            <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover compact-table mb-0" id="dormitories-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No.</th>
                        <th class="sortable" data-sort="room_name">Room Name <span class="sort-icon"></span></th>
                        <th class="sortable" data-sort="location">Location <span class="sort-icon"></span></th>
                        <th class="sortable text-center" data-sort="room_capacity">Capacity <span class="sort-icon"></span></th>
                        <th class="sortable text-center" data-sort="occupied_beds">Occupied <span class="sort-icon"></span></th>
                        <th class="sortable text-center" data-sort="available_beds">Available <span class="sort-icon"></span></th>
                        <th class="sortable" data-sort="status">Status <span class="sort-icon"></span></th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="dormitories-tbody">
                    <?php if (!empty($dormitories)): ?>
                        <?php 
                        $startIndex = (($currentPage ?? 1) - 1) * 10 + 1;
                        foreach ($dormitories as $index => $dorm): 
                        ?>
                            <tr>
                                <td class="text-center text-muted"><?= $startIndex + $index ?></td>
                                <td>
                                    <div class="fw-bold"><?= esc($dorm['room_name']) ?></div>
                                    <?php if (!empty($dorm['facilities'])): ?>
                                        <small class="text-muted">
                                            <?= esc(implode(', ', array_slice($dorm['facilities'], 0, 3))) ?>
                                            <?= count($dorm['facilities']) > 3 ? '...' : '' ?>
                                        </small>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <small><?= esc($dorm['location']) ?></small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark"><?= $dorm['room_capacity'] ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger"><?= $dorm['occupied_beds'] ?></span>
                                </td>
                                <td class="text-center">
                                    <?php $available = $dorm['available_beds']; ?>
                                    <span class="badge <?= $available > 0 ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= $available ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($dorm['status'] === 'available'): ?>
                                        <span class="badge bg-success">Available</span>
                                    <?php elseif ($dorm['status'] === 'full'): ?>
                                        <span class="badge bg-warning text-dark">Full</span>
                                    <?php elseif ($dorm['status'] === 'maintenance'): ?>
                                        <span class="badge bg-secondary">Maintenance</span>
                                    <?php else: ?>
                                        <span class="badge bg-dark">Inactive</span>
                                    <?php endif ?>
                                </td>
                                <td class="text-center table-actions">
                                    <a href="<?= base_url('dormitory/show/' . $dorm['id']) ?>" 
                                       class="btn btn-sm btn-info text-white" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= base_url('dormitory/edit/' . $dorm['id']) ?>" 
                                       class="btn btn-sm btn-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= base_url('dormitory/assignments/' . $dorm['id']) ?>" 
                                       class="btn btn-sm btn-success" title="Manage Assignments">
                                        <i class="bi bi-people"></i>
                                    </a>
                                    <form action="<?= base_url('dormitory/delete/' . $dorm['id']) ?>" 
                                          method="post" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this dormitory?')">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No dormitories found. <a href="<?= base_url('dormitory/create') ?>">Add your first room</a>
                            </td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUploadModalLabel">
                    <i class="bi bi-file-earmark-arrow-up me-2"></i>Bulk Upload Dormitories
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('dormitory/bulk-upload') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Instructions:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Download the Excel template below</li>
                            <li>Fill in your dormitory data following the format</li>
                            <li>Upload the completed file</li>
                            <li>Maximum file size: 5MB</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <a href="<?= base_url('dormitory/download-template') ?>" class="btn btn-outline-primary w-100">
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
let currentSort = 'room_name';
let currentOrder = 'asc';

// Simple search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const search = this.value.toLowerCase();
    const rows = document.querySelectorAll('#dormitories-tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
});

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

    sortTable(sortField, currentOrder);
}

function sortTable(field, order) {
    const tbody = document.getElementById('dormitories-tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    const columnIndex = {
        'room_name': 1,
        'location': 2,
        'room_capacity': 3,
        'occupied_beds': 4,
        'available_beds': 5,
        'status': 6
    };

    const idx = columnIndex[field];
    if (idx === undefined) return;

    rows.sort((a, b) => {
        let aVal = a.cells[idx]?.textContent?.trim() || '';
        let bVal = b.cells[idx]?.textContent?.trim() || '';

        // Check if numeric
        const aNum = parseFloat(aVal);
        const bNum = parseFloat(bVal);
        
        if (!isNaN(aNum) && !isNaN(bNum)) {
            return order === 'asc' ? aNum - bNum : bNum - aNum;
        }

        return order === 'asc' 
            ? aVal.localeCompare(bVal) 
            : bVal.localeCompare(aVal);
    });

    // Update row numbers
    rows.forEach((row, index) => {
        if (row.cells[0]) {
            row.cells[0].textContent = index + 1;
        }
        tbody.appendChild(row);
    });
}

// Sort headers click handlers
document.querySelectorAll('.sortable').forEach(th => {
    th.addEventListener('click', function() {
        const sortField = this.getAttribute('data-sort');
        handleSort(sortField);
    });
});
</script>
<?= $this->endSection() ?>
