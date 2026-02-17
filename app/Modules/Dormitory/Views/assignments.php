<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold"><i class="bi bi-people me-2"></i>Manage Assignments</h2>
        <p class="text-muted"><?= esc($dormitory['room_name']) ?> - <?= esc($dormitory['location']) ?></p>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= base_url('dormitory/show/' . $dormitory['id']) ?>" class="btn btn-outline-info me-2">
            <i class="bi bi-eye me-1"></i> View Details
        </a>
        <a href="<?= base_url('dormitory') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show">
    <?= session('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif ?>

<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <?= session('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif ?>

<div class="row">
    <!-- Left Column - Add Student Form -->
    <div class="col-md-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <i class="bi bi-person-plus me-2"></i>Assign Student
            </div>
            <div class="card-body">
                <!-- Availability Info -->
                <div class="alert <?= $dormitory['available_beds'] > 0 ? 'alert-success' : 'alert-warning' ?> mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Available Beds:</span>
                        <strong><?= $dormitory['available_beds'] ?> / <?= $dormitory['room_capacity'] ?></strong>
                    </div>
                </div>

                <?php if ($dormitory['available_beds'] > 0): ?>
                    <form action="<?= base_url('dormitory/assign/' . $dormitory['id']) ?>" method="post">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Select Student</label>
                            <select class="form-select" id="student_id" name="student_id" required>
                                <option value="">-- Select Student --</option>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?= $student['id'] ?>">
                                        <?= esc($student['full_name'] ?? 'N/A') ?> (<?= esc($student['student_number']) ?>)
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date <small class="text-muted">(Optional)</small></label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>

                        <button type="submit" class="btn btn-dark-red w-100">
                            <i class="bi bi-plus-lg me-1"></i> Assign Student
                        </button>
                    </form>
                <?php else: ?>
                    <div class="text-center py-3">
                        <i class="bi bi-exclamation-triangle text-warning fs-1"></i>
                        <p class="mt-2 mb-0">This dormitory is full.</p>
                        <p class="text-muted small">Remove a student to add new assignments.</p>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <!-- Right Column - Current Assignments -->
    <div class="col-md-8">
        <div class="card dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list me-2"></i>Current Occupants</span>
                <span class="badge bg-primary"><?= count($assignments) ?></span>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($assignments)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover compact-table mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Student Number</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assignments as $assignment): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= esc($assignment['full_name'] ?? 'N/A') ?></div>
                                        </td>
                                        <td><?= esc($assignment['student_number'] ?? 'N/A') ?></td>
                                        <td><?= $assignment['start_date'] ?? '-' ?></td>
                                        <td><?= $assignment['end_date'] ?? '<span class="text-muted">-</span>' ?></td>
                                        <td>
                                            <?php if (!empty($assignment['phone'])): ?>
                                                <a href="tel:<?= esc($assignment['phone']) ?>"><?= esc($assignment['phone']) ?></a>
                                            <?php else: ?>
                                                -
                                            <?php endif ?>
                                        </td>
                                        <td>
                                            <?php if ($assignment['status'] === 'active'): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php elseif ($assignment['status'] === 'completed'): ?>
                                                <span class="badge bg-secondary">Completed</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Cancelled</span>
                                            <?php endif ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($assignment['status'] === 'active'): ?>
                                                <form action="<?= base_url('dormitory/unassign/' . $assignment['id']) ?>" 
                                                      method="post" class="d-inline"
                                                      onsubmit="return confirm('Remove this student from the dormitory?')">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove">
                                                        <i class="bi bi-person-x"></i>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-1"></i>
                        <p class="mt-2">No students assigned yet.</p>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
