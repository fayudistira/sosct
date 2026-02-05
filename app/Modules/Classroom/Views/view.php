<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Classroom Details</h2>
        <p class="text-muted">Viewing details for <?= esc($classroom['title']) ?></p>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= base_url('classroom') ?>" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
        <a href="<?= base_url('classroom/edit/' . $classroom['id']) ?>" class="btn btn-dark-red">
            <i class="bi bi-pencil me-1"></i> Edit Class
        </a>
    </div>
</div>

<div class="row">
    <!-- Left Column: Basic Info & Schedule -->
    <div class="col-lg-7">
        <!-- Basic Info -->
        <div class="card dashboard-card mb-4">
            <div class="card-header">General Information</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Class Title:</div>
                    <div class="col-sm-8"><?= esc($classroom['title']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Batch / Grade:</div>
                    <div class="col-sm-8"><?= esc($classroom['batch'] ?: '-') ?> / <?= esc($classroom['grade'] ?: '-') ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Program:</div>
                    <div class="col-sm-8"><?= esc($classroom['program'] ?: '-') ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Status:</div>
                    <div class="col-sm-8">
                        <?php if ($classroom['status'] === 'active'): ?>
                            <span class="badge bg-success">Active</span>
                        <?php elseif ($classroom['status'] === 'completed'): ?>
                            <span class="badge bg-primary">Completed</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactive</span>
                        <?php endif ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Active Period:</div>
                    <div class="col-sm-8">
                        <?= $classroom['start_date'] ? date('d F Y', strtotime($classroom['start_date'])) : 'Not set' ?> 
                        <strong>to</strong> 
                        <?= $classroom['end_date'] ? date('d F Y', strtotime($classroom['end_date'])) : 'Not set' ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule -->
        <div class="card dashboard-card mb-4">
            <div class="card-header">Class Schedule</div>
            <div class="card-body p-0">
                <table class="table table-hover compact-table mb-0">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Instructor</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($classroom['schedule'])): ?>
                            <?php foreach ($classroom['schedule'] as $subject => $details): ?>
                                <tr>
                                    <td class="fw-bold"><?= esc($subject) ?></td>
                                    <td><?= esc($details['instructor'] ?: '-') ?></td>
                                    <td><i class="bi bi-clock me-1 text-muted"></i> <?= esc($details['time'] ?: '-') ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">No schedule defined.</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Members -->
    <div class="col-lg-5">
        <div class="card dashboard-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Class Members</span>
                <span class="badge bg-dark-red"><?= count($members) ?> Students</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" style="max-height: 500px; overflow-y: auto;">
                    <?php if (!empty($members)): ?>
                        <?php foreach ($members as $member): ?>
                            <div class="list-group-item d-flex align-items-center">
                                <div class="avatar-sm rounded-circle bg-light-red d-flex align-items-center justify-content-center me-3 text-dark-red fw-bold">
                                    <?= substr($member['full_name'], 0, 1) ?>
                                </div>
                                <div>
                                    <div class="fw-medium"><?= esc($member['full_name']) ?></div>
                                    <small class="text-muted"><?= esc($member['registration_number']) ?></small>
                                </div>
                                <div class="ms-auto">
                                    <a href="<?= base_url('admission/view-by-reg/' . $member['registration_number']) ?>" class="btn btn-sm btn-link text-muted p-0" title="View Profile">
                                        <i class="bi bi-person-bounding-box"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach ?>
                    <?php else: ?>
                        <div class="p-4 text-center text-muted">No members assigned to this class.</div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
