<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>Switch Program</h5>
            </div>
            <div class="card-body">
                <!-- Current Program Info -->
                <div class="alert alert-info">
                    <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Current Program Information</h6>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Student:</strong> <?= esc($admission['full_name']) ?></p>
                            <p class="mb-1"><strong>Registration Number:</strong> <?= esc($admission['registration_number']) ?></p>
                            <p class="mb-0"><strong>Current Program:</strong> <?= esc($admission['program_title']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Registration Fee:</strong> Rp <?= number_format($currentRegFee, 0, ',', '.') ?></p>
                            <p class="mb-1"><strong>Tuition Fee:</strong> Rp <?= number_format($currentTuitionFee, 0, ',', '.') ?></p>
                            <p class="mb-0"><strong>Total Contract:</strong> Rp <?= number_format($currentTotal, 0, ',', '.') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <?php if ($totalPaid > 0): ?>
                <div class="alert alert-success">
                    <h6 class="alert-heading"><i class="bi bi-cash me-2"></i>Payment Credits</h6>
                    <hr>
                    <p class="mb-0">
                        <strong>Total Paid:</strong> 
                        <span class="text-success fw-bold">Rp <?= number_format($totalPaid, 0, ',', '.') ?></span>
                        <small class="text-muted">(This amount will be credited to the new program)</small>
                    </p>
                </div>
                <?php endif; ?>

                <form action="<?= base_url('admission/switch/' . $admission['admission_id']) ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- New Program Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Select New Program <span class="text-danger">*</span></label>
                        <?php if (empty($availablePrograms)): ?>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                No other programs available for switching.
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($availablePrograms as $program): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 <?= $program['id'] == old('new_program_id') ? 'border-primary' : '' ?>" 
                                         style="cursor: pointer;"
                                         onclick="document.getElementById('program_<?= $program['id'] ?>').checked = true; showProgramDetails(<?= $program['id'] ?>)">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="new_program_id" 
                                                       id="program_<?= $program['id'] ?>" 
                                                       value="<?= $program['id'] ?>"
                                                       <?= $program['id'] == old('new_program_id') ? 'checked' : '' ?>
                                                       required>
                                                <label class="form-check-label w-100" for="program_<?= $program['id'] ?>">
                                                    <strong><?= esc($program['title']) ?></strong>
                                                    <span class="badge bg-secondary float-end"><?= esc($program['category']) ?></span>
                                                </label>
                                            </div>
                                            <hr class="my-2">
                                            <small class="text-muted d-block">
                                                <i class="bi bi-cash me-1"></i>Registration: Rp <?= number_format($program['registration_fee'] ?? 0, 0, ',', '.') ?>
                                            </small>
                                            <small class="text-muted d-block">
                                                <i class="bi bi-book me-1"></i>Tuition: Rp <?= number_format($program['tuition_fee'] ?? 0, 0, ',', '.') ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Program Comparison -->
                    <?php if (!empty($availablePrograms)): ?>
                    <div id="programComparison" class="mb-4" style="display: none;">
                        <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-2"></i>Program Comparison</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Current Program</th>
                                        <th>New Program</th>
                                        <th>Difference</th>
                                    </tr>
                                </thead>
                                <tbody id="comparisonBody">
                                    <!-- Populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Switch Reason -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Reason for Switching <span class="text-danger">*</span></label>
                        <textarea name="switch_reason" class="form-control" rows="3" 
                                  placeholder="Please provide a reason for switching programs..."
                                  required><?= old('switch_reason') ?></textarea>
                    </div>

                    <!-- Important Notes -->
                    <div class="alert alert-secondary">
                        <h6 class="alert-heading"><i class="bi bi-exclamation-circle me-2"></i>Important Notes</h6>
                        <ul class="mb-0 small">
                            <li>All unpaid invoices from the current program will be <strong>cancelled</strong>.</li>
                            <li>Previous payments will be <strong>credited</strong> to the new program contract.</li>
                            <li>You will need to pay the <strong>difference</strong> (new program fees - previous payments).</li>
                            <li>This action can be performed multiple times if needed.</li>
                        </ul>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= base_url('admission/view/' . $admission['admission_id']) ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-warning" <?= empty($availablePrograms) ? 'disabled' : '' ?>>
                            <i class="bi bi-arrow-left-right me-2"></i>Switch Program
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
<?php 
// Create a JavaScript object with program data for comparison
$programData = [];
foreach ($availablePrograms as $prog) {
    $programData[$prog['id']] = [
        'title' => $prog['title'],
        'registration_fee' => (float) ($prog['registration_fee'] ?? 0),
        'tuition_fee' => (float) ($prog['tuition_fee'] ?? 0),
        'total' => (float) ($prog['registration_fee'] ?? 0) + (float) ($prog['tuition_fee'] ?? 0)
    ];
}
?>

const currentProgram = {
    title: '<?= esc($admission['program_title']) ?>',
    registration_fee: <?= $currentRegFee ?>,
    tuition_fee: <?= $currentTuitionFee ?>,
    total: <?= $currentTotal ?>
};

const availablePrograms = <?= json_encode($programData) ?>;
const totalPaid = <?= $totalPaid ?>;

function showProgramDetails(programId) {
    const program = availablePrograms[programId];
    const comparisonBody = document.getElementById('comparisonBody');
    const comparisonDiv = document.getElementById('programComparison');
    
    if (!program) return;

    // Calculate differences
    const regDiff = program.registration_fee - currentProgram.registration_fee;
    const tuitionDiff = program.tuition_fee - currentProgram.tuition_fee;
    const totalDiff = program.total - currentProgram.total;
    
    // Format number with color
    const formatDiff = (val) => {
        if (val === 0) return '<span class="text-muted">-</span>';
        if (val > 0) return '<span class="text-danger">+Rp ' + formatNumber(val) + '</span>';
        return '<span class="text-success">-Rp ' + formatNumber(Math.abs(val)) + '</span>';
    };

    comparisonBody.innerHTML = `
        <tr>
            <td>Registration Fee</td>
            <td>Rp ${formatNumber(currentProgram.registration_fee)}</td>
            <td>Rp ${formatNumber(program.registration_fee)}</td>
            <td>${formatDiff(regDiff)}</td>
        </tr>
        <tr>
            <td>Tuition Fee</td>
            <td>Rp ${formatNumber(currentProgram.tuition_fee)}</td>
            <td>Rp ${formatNumber(program.tuition_fee)}</td>
            <td>${formatDiff(tuitionDiff)}</td>
        </tr>
        <tr class="table-primary">
            <td><strong>Total Contract</strong></td>
            <td><strong>Rp ${formatNumber(currentProgram.total)}</strong></td>
            <td><strong>Rp ${formatNumber(program.total)}</strong></td>
            <td>${formatDiff(totalDiff)}</td>
        </tr>
        <tr class="table-success">
            <td><strong>Credits (Previous Payments)</strong></td>
            <td colspan="2"><strong>Rp ${formatNumber(totalPaid)}</strong></td>
            <td></td>
        </tr>
        <tr class="table-warning">
            <td><strong>New Remaining Balance</strong></td>
            <td colspan="2"></td>
            <td><strong>Rp ${formatNumber(Math.max(0, program.total - totalPaid))}</strong></td>
        </tr>
    `;
    
    comparisonDiv.style.display = 'block';
}

// Format number with thousand separator
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Show program details if previously selected
<?php if (old('new_program_id')): ?>
showProgramDetails(<?= old('new_program_id') ?>);
<?php endif; ?>
</script>

<?= $this->endSection() ?>
