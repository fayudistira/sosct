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

<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Siswa</h1>
    <a href="<?= base_url('student/promote') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambahkan Siswa Baru
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Siswa</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No.</th>
                        <th class="sortable" data-sort="student_number">No. Siswa <span class="sort-icon"></span></th>
                        <th class="sortable" data-sort="full_name">Nama <span class="sort-icon"></span></th>
                        <th class="sortable" data-sort="program_title">Program <span class="sort-icon"></span></th>
                        <th class="sortable" data-sort="batch">Angkatan <span class="sort-icon"></span></th>
                        <th class="sortable" data-sort="status">Status <span class="sort-icon"></span></th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="students-tbody">
                    <?php 
                    $startIndex = (($currentPage ?? 1) - 1) * 10 + 1;
                    foreach ($students as $index => $student): 
                    ?>
                        <tr>
                            <td class="text-center text-muted"><?= $startIndex + $index ?></td>
                            <td><?= esc($student['student_number']) ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if (!empty($student['photo'])): ?>
                                        <img src="<?= base_url('uploads/' . $student['photo']) ?>" alt="Photo" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-secondary text-white me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <?= substr($student['full_name'], 0, 1) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-bold"><?= esc($student['full_name']) ?></div>
                                        <small class="text-muted"><?= esc($student['profile_email'] ?? '') ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?= esc($student['program_title']) ?></td>
                            <td><?= esc($student['batch']) ?></td>
                            <td>
                                <?php
                                $statusClass = match ($student['status']) {
                                    'active' => 'success',
                                    'inactive' => 'secondary',
                                    'graduated' => 'primary',
                                    'dropped' => 'danger',
                                    'suspended' => 'warning',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $statusClass ?>"><?= ucfirst($student['status']) ?></span>
                            </td>
                            <td>
                                <a href="<?= base_url('student/view/' . $student['id']) ?>" class="btn btn-sm btn-info" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= base_url('student/edit/' . $student['id']) ?>" class="btn btn-sm btn-warning" title="Edit Status">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
let currentSort = 'student_number';
let currentOrder = 'asc';

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
    const tbody = document.getElementById('students-tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    const columnIndex = {
        'student_number': 1,
        'full_name': 2,
        'program_title': 3,
        'batch': 4,
        'status': 5
    };

    const idx = columnIndex[field];
    if (idx === undefined) return;

    rows.sort((a, b) => {
        let aVal = a.cells[idx]?.textContent?.trim() || '';
        let bVal = b.cells[idx]?.textContent?.trim() || '';

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