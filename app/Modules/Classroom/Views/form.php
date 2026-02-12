<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold"><i class="bi bi-pencil-square me-2"></i><?= esc($title) ?></h2>
        <p class="text-muted">Fill in the details for the classroom.</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= base_url('classroom') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>
</div>

<form action="<?= $action ?>" method="post">
    <?= csrf_field() ?>
    <div class="row">
        <!-- Basic Information -->
        <div class="col-lg-8">
            <div class="card dashboard-card mb-4">
                <div class="card-header">Basic Information</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Class Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= old('title', $classroom['title'] ?? '') ?>" required placeholder="e.g. Intensive English February 2026">
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="batch" class="form-label fw-bold">Batch</label>
                            <input type="text" class="form-control" id="batch" name="batch" value="<?= old('batch', $classroom['batch'] ?? '') ?>" placeholder="e.g. Batch 12">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="grade" class="form-label fw-bold">Grade</label>
                            <input type="text" class="form-control" id="grade" name="grade" value="<?= old('grade', $classroom['grade'] ?? '') ?>" placeholder="e.g. Intermediate">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="program" class="form-label fw-bold">Program</label>
                            <input type="text" class="form-control" id="program" name="program" value="<?= old('program', $classroom['program'] ?? '') ?>" placeholder="e.g. Fullstack English">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label fw-bold">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= old('start_date', $classroom['start_date'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label fw-bold">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= old('end_date', $classroom['end_date'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" <?= (old('status', $classroom['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= (old('status', $classroom['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Inactive</option>
                            <option value="completed" <?= (old('status', $classroom['status'] ?? '') === 'completed') ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Schedule -->
            <div class="card dashboard-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Class Schedule</span>
                    <button type="button" class="btn btn-sm btn-outline-dark-red" id="addSchedule">
                        <i class="bi bi-plus-lg me-1"></i> Add Subject
                    </button>
                </div>
                <div class="card-body">
                    <div id="scheduleContainer">
                        <?php
                        $schedule = old('schedule', $classroom['schedule'] ?? [
                            'Speaking' => ['instructor' => '', 'time' => '07.30-09.00'],
                            'Grammar'  => ['instructor' => '', 'time' => '07.30-09.00'],
                            'Writing'  => ['instructor' => '', 'time' => '07.30-09.00']
                        ]);
                        ?>
                        <?php foreach ($schedule as $subject => $details): ?>
                            <div class="schedule-item border rounded p-3 mb-3 bg-light position-relative">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-schedule" title="Remove"></button>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label fs-sm fw-bold">Subject</label>
                                        <input type="text" class="form-control form-control-sm" name="schedule_subject[]" value="<?= esc($subject) ?>" placeholder="e.g. Speaking">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label fs-sm fw-bold">Instructor</label>
                                        <select class="form-select form-select-sm" name="schedule_instructor[]">
                                            <option value="">-- Select Instructor --</option>
                                            <?php if (!empty($instructors)): ?>
                                                <?php foreach ($instructors as $instructor): ?>
                                                    <option value="<?= esc($instructor['full_name']) ?>" <?= ($details['instructor'] ?? '') === $instructor['full_name'] ? 'selected' : '' ?>>
                                                        <?= esc($instructor['full_name']) ?>
                                                    </option>
                                                <?php endforeach ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label fs-sm fw-bold">Time</label>
                                        <input type="text" class="form-control form-control-sm" name="schedule_time[]" value="<?= esc($details['time'] ?? '07.30-09.00') ?>" placeholder="e.g. 07.30-09.00">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Members Selection -->
        <div class="col-lg-4">
            <div class="card dashboard-card mb-4">
                <div class="card-header">Class Members</div>
                <div class="card-body p-0">
                    <div class="p-3">
                        <input type="text" id="memberSearch" class="form-control form-control-sm mb-3" placeholder="Search applicants...">
                    </div>
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0" id="memberTable">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th style="width: 40px;" class="ps-3">
                                        <input type="checkbox" class="form-check-input" id="selectAllMembers">
                                    </th>
                                    <th>Full Name / Reg. No</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($admissions)): ?>
                                    <?php
                                    $currentMembers = old('members', $classroom['members'] ?? []);
                                    ?>
                                    <?php foreach ($admissions as $admission): ?>
                                        <tr class="member-row">
                                            <td class="ps-3">
                                                <input type="checkbox" class="form-check-input member-checkbox" name="members[]"
                                                    value="<?= $admission['registration_number'] ?>"
                                                    <?= in_array($admission['registration_number'], $currentMembers) ? 'checked' : '' ?>>
                                            </td>
                                            <td>
                                                <div class="fw-medium"><?= esc($admission['full_name']) ?></div>
                                                <small class="text-muted"><?= esc($admission['registration_number']) ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="2" class="text-center py-3 text-muted">No applicants found.</td>
                                    </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <span id="selectedCount" class="badge bg-dark-red">0</span> members selected
                </div>
            </div>

            <div class="card dashboard-card bg-light">
                <div class="card-body">
                    <button type="submit" class="btn btn-dark-red w-100 mb-2">
                        <i class="bi bi-save me-1"></i> Save Classroom
                    </button>
                    <a href="<?= base_url('classroom') ?>" class="btn btn-outline-secondary w-100">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    // Pass instructors data to JavaScript
    const instructorsData = <?= json_encode($instructors ?? []) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        // Add Schedule Item
        const addScheduleBtn = document.getElementById('addSchedule');
        const scheduleContainer = document.getElementById('scheduleContainer');

        addScheduleBtn.addEventListener('click', function() {
            const newItem = document.createElement('div');
            newItem.className = 'schedule-item border rounded p-3 mb-3 bg-light position-relative';

            // Build instructor dropdown options
            let instructorOptions = '<option value="">-- Select Instructor --</option>';
            instructorsData.forEach(function(instructor) {
                instructorOptions += `<option value="${instructor.full_name}">${instructor.full_name}</option>`;
            });

            newItem.innerHTML = `
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-schedule" title="Remove"></button>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <label class="form-label fs-sm fw-bold">Subject</label>
                    <input type="text" class="form-control form-control-sm" name="schedule_subject[]" placeholder="e.g. Speaking">
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label fs-sm fw-bold">Instructor</label>
                    <select class="form-select form-select-sm" name="schedule_instructor[]">
                        ${instructorOptions}
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label fs-sm fw-bold">Time</label>
                    <input type="text" class="form-control form-control-sm" name="schedule_time[]" value="07.30-09.00" placeholder="e.g. 07.30-09.00">
                </div>
            </div>
        `;
            scheduleContainer.appendChild(newItem);
            attachRemoveEvents();
        });

        function attachRemoveEvents() {
            document.querySelectorAll('.remove-schedule').forEach(btn => {
                btn.onclick = function() {
                    this.closest('.schedule-item').remove();
                };
            });
        }
        attachRemoveEvents();

        // Member Selection Search
        const memberSearch = document.getElementById('memberSearch');
        const memberRows = document.querySelectorAll('.member-row');

        memberSearch.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            memberRows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });

        // Select All
        const selectAll = document.getElementById('selectAllMembers');
        const checkboxes = document.querySelectorAll('.member-checkbox');
        const selectedCount = document.getElementById('selectedCount');

        function updateCount() {
            const count = document.querySelectorAll('.member-checkbox:checked').length;
            selectedCount.innerText = count;
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                if (cb.closest('.member-row').style.display !== 'none') {
                    cb.checked = this.checked;
                }
            });
            updateCount();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateCount);
        });

        updateCount();
    });
</script>

<style>
    .fs-sm {
        font-size: 0.75rem;
    }

    .btn-close {
        font-size: 0.75rem;
    }
</style>
<?= $this->endSection() ?>