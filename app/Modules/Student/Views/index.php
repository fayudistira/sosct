<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Student Management</h1>
    <a href="<?= base_url('student/promote') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Promote New Student
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">List of Students</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Student No</th>
                        <th>Name</th>
                        <th>Program</th>
                        <th>Batch</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
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
                                <a href="<?= base_url('student/view/' . $student['id']) ?>" class="btn btn-sm btn-info" title="View Details">
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
<?= $this->endSection() ?>