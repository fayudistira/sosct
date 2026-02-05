<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
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

<div class="dashboard-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table compact-table mb-0">
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
                <tbody>
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
</div>
<?= $this->endSection() ?>
