<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold"><i class="bi bi-door-open me-2"></i>Classroom Management</h2>
        <p class="text-muted">Manage your classes, schedules, and members.</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= base_url('classroom/create') ?>" class="btn btn-dark-red">
            <i class="bi bi-plus-lg me-1"></i> Create New Class
        </a>
    </div>
</div>

<div class="card dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Classroom List</span>
        <div class="input-group input-group-sm" style="width: 250px;">
            <input type="text" class="form-control" placeholder="Search classes...">
            <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover compact-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Class Title</th>
                        <th>Batch / Grade</th>
                        <th>Program</th>
                        <th>Schedule Summary</th>
                        <th>Status</th>
                        <th>Active Period</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($classrooms)): ?>
                        <?php foreach ($classrooms as $index => $class): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
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
</div>
<?= $this->endSection() ?>