<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Admission Details</h4>
        <p class="text-muted mb-0">View complete admission application information</p>
    </div>
    <div class="col-auto">
        <div class="btn-group">
            <?php if ($admission['status'] === 'approved'): ?>
                <a href="<?= base_url('admission/promote/' . $admission['admission_id']) ?>" class="btn btn-primary">
                    <i class="bi bi-award me-1"></i> Promote to Student
                </a>
            <?php endif; ?>
            <a href="<?= base_url('admission/switch/' . $admission['admission_id']) ?>" class="btn btn-warning">
                <i class="bi bi-arrow-left-right me-1"></i> Switch Program
            </a>
            <a href="<?= base_url('admission/edit/' . $admission['admission_id']) ?>" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <button type="button" class="btn btn-outline-danger" onclick="confirmDelete(<?= $admission['admission_id'] ?>)">
                <i class="bi bi-trash me-1"></i> Delete
            </button>
            <a href="<?= base_url('admission') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Main Information Column -->
    <div class="col-lg-8">
        <!-- Personal Information -->
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <i class="bi bi-person me-2"></i>Personal Information
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="p-3 border rounded" style="background-color: var(--light-red);">
                            <div class="stat-label">Registration Number</div>
                            <div class="stat-number fs-5"><?= esc($admission['registration_number']) ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded" style="background-color: var(--light-red);">
                            <div class="stat-label mb-2">Status</div>
                            <?php
                            $badgeClass = match ($admission['status']) {
                                'pending' => 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25',
                                'approved' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
                                'rejected' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25',
                                'withdrawn' => 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25',
                                default => 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25'
                            };
                            ?>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge <?= $badgeClass ?> fs-6" id="currentStatusBadge"><?= ucfirst($admission['status']) ?></span>
                                <button type="button" class="btn btn-sm btn-outline-dark-red" data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                                    <i class="bi bi-pencil"></i> Change
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="stat-label">Full Name</label>
                        <div class="fw-medium"><?= esc($admission['full_name']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="stat-label">Nickname</label>
                        <div class="fw-medium"><?= esc($admission['nickname'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="stat-label">Gender</label>
                        <div class="fw-medium"><?= esc($admission['gender']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="stat-label">Date of Birth</label>
                        <div class="fw-medium"><?= date('M d, Y', strtotime($admission['date_of_birth'])) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="stat-label">Place of Birth</label>
                        <div class="fw-medium"><?= esc($admission['place_of_birth']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="stat-label">Religion</label>
                        <div class="fw-medium"><?= esc($admission['religion']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="stat-label">Citizen ID</label>
                        <div class="fw-medium"><?= esc($admission['citizen_id'] ?? '-') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <i class="bi bi-telephone me-2"></i>Contact Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="stat-label">Phone</label>
                        <div class="fw-medium"><?= esc($admission['phone']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="stat-label">Email</label>
                        <div class="fw-medium"><?= esc($admission['email']) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address -->
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <i class="bi bi-geo-alt me-2"></i>Address
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="stat-label">Street Address</label>
                    <div class="fw-medium"><?= esc($admission['street_address']) ?></div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="stat-label">District</label>
                        <div class="fw-medium"><?= esc($admission['district']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="stat-label">Regency/City</label>
                        <div class="fw-medium"><?= esc($admission['regency']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="stat-label">Province</label>
                        <div class="fw-medium"><?= esc($admission['province']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="stat-label">Postal Code</label>
                        <div class="fw-medium"><?= esc($admission['postal_code'] ?? '-') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <i class="bi bi-exclamation-triangle me-2"></i>Emergency Contact
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="stat-label">Name</label>
                        <div class="fw-medium"><?= esc($admission['emergency_contact_name']) ?></div>
                    </div>
                    <div class="col-md-4">
                        <label class="stat-label">Phone</label>
                        <div class="fw-medium"><?= esc($admission['emergency_contact_phone']) ?></div>
                    </div>
                    <div class="col-md-4">
                        <label class="stat-label">Relationship</label>
                        <div class="fw-medium"><?= esc($admission['emergency_contact_relation']) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Family Information -->
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <i class="bi bi-people me-2"></i>Family Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="stat-label">Father's Name</label>
                        <div class="fw-medium"><?= esc($admission['father_name']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="stat-label">Mother's Name</label>
                        <div class="fw-medium"><?= esc($admission['mother_name']) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course & Notes -->
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <i class="bi bi-mortarboard me-2"></i>Program & Additional Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="stat-label">Desired Program</label>
                        <div class="fw-medium"><?= esc($admission['program_title'] ?? 'N/A') ?></div>
                        <?php if (!empty($admission['category'])): ?>
                            <small class="text-muted">Category: <?= esc($admission['category']) ?></small>
                        <?php endif ?>
                    </div>
                    <div class="col-md-6">
                        <label class="stat-label">Application Date</label>
                        <div class="fw-medium"><?= date('M d, Y', strtotime($admission['application_date'])) ?></div>
                    </div>
                </div>
                <?php if (!empty($admission['applicant_notes'])): ?>
                    <div class="mt-3">
                        <label class="stat-label">Applicant Notes</label>
                        <?php
                        $applicantNotes = $admission['applicant_notes'];
                        if (is_array($applicantNotes)) {
                            $applicantNotes = json_encode($applicantNotes);
                        }
                        /** @var string $applicantNotes */
                        ?>
                        <div class="fw-medium"><?= nl2br(esc($applicantNotes)) ?></div>
                    </div>
                <?php endif ?>
                <?php if (!empty($admission['notes'])): ?>
                    <div class="mt-3">
                        <label class="stat-label">Admin Notes</label>
                        <?php
                        $adminNotes = $admission['notes'];
                        if (is_array($adminNotes)) {
                            $adminNotes = json_encode($adminNotes);
                        }
                        /** @var string $adminNotes */
                        ?>
                        <div class="fw-medium"><?= nl2br(esc($adminNotes)) ?></div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <!-- Sidebar Column -->
    <div class="col-lg-4">
        <!-- Profile Photo -->
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <i class="bi bi-image me-2"></i>Profile Photo
            </div>
            <div class="card-body text-center">
                <?php if (!empty($admission['photo'])): ?>
                    <img src="<?= base_url('uploads/' . $admission['photo']) ?>"
                        alt="Profile Photo"
                        class="img-fluid rounded"
                        style="max-height: 300px; border: 2px solid var(--border-color);">
                <?php else: ?>
                    <div class="p-5 border rounded" style="background-color: var(--light-red);">
                        <i class="bi bi-person-circle" style="font-size: 4rem; color: var(--dark-red);"></i>
                        <p class="text-muted mt-2 mb-0">No photo uploaded</p>
                    </div>
                <?php endif ?>
            </div>
        </div>

        <!-- Supporting Documents -->
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <i class="bi bi-file-earmark-text me-2"></i>Supporting Documents
            </div>
            <div class="card-body">
                <?php if (!empty($admission['documents'])): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($admission['documents'] as $index => $doc): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="fs-sm">
                                    <i class="bi bi-file-pdf text-danger me-2"></i>
                                    Document <?= $index + 1 ?>
                                </span>
                                <a href="<?= base_url('uploads/' . $doc) ?>" target="_blank"
                                    class="btn btn-outline-dark-red btn-sm">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php else: ?>
                    <div class="text-center p-4" style="background-color: var(--light-red); border-radius: 8px;">
                        <i class="bi bi-file-earmark-x" style="font-size: 2rem; color: var(--dark-red);"></i>
                        <p class="text-muted mt-2 mb-0 fs-sm">No documents uploaded</p>
                    </div>
                <?php endif ?>
            </div>
        </div>

        <?php if (!empty($installment)): ?>
            <!-- Contract / Payment Info -->
            <div class="dashboard-card mb-3">
                <div class="card-header">
                    <i class="bi bi-file-contract me-2"></i>Riwayat Tagihan
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="stat-label">No. Registrasi</label>
                        <div class="fw-medium"><?= esc($installment['registration_number']) ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="stat-label">Total Biaya</label>
                        <div class="fw-medium text-primary">Rp<?= number_format($installment['total_contract_amount'], 2) ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="stat-label">Total Dibayar</label>
                        <div class="fw-medium text-success">Rp<?= number_format($installment['total_paid'], 2) ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="stat-label">Sisa</label>
                        <div class="fw-medium <?= $installment['remaining_balance'] > 0 ? 'text-danger' : 'text-success' ?>">
                            Rp<?= number_format($installment['remaining_balance'], 2) ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="stat-label">Due Date</label>
                        <div class="fw-medium"><?= !empty($installment['due_date']) ? date('M d, Y', strtotime($installment['due_date'])) : '-' ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="stat-label">Status</label>
                        <?php
                        $statusClass = match ($installment['status']) {
                            'unpaid' => 'bg-warning text-dark',
                            'partial' => 'bg-info text-dark',
                            'paid' => 'bg-success',
                            default => 'bg-secondary'
                        };
                        ?>
                        <span class="badge <?= $statusClass ?>"><?= ucfirst($installment['status']) ?></span>
                    </div>
                    <a href="<?= base_url('contract/view/' . $admission['registration_number']) ?>"
                        class="btn btn-primary w-100 mt-2">
                        <i class="bi bi-file-text me-1"></i> View Contract
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Change Status Modal -->
<div class="modal fade" id="changeStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Admission Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Current Status</label>
                    <div>
                        <span class="badge <?= $badgeClass ?> fs-6"><?= ucfirst($admission['status']) ?></span>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="newStatus" class="form-label">New Status <span class="text-danger">*</span></label>
                    <select id="newStatus" class="form-select">
                        <option value="pending" <?= $admission['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= $admission['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= $admission['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        <option value="withdrawn" <?= $admission['status'] === 'withdrawn' ? 'selected' : '' ?>>Withdrawn</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="statusNotes" class="form-label">Notes (Optional)</label>
                    <textarea id="statusNotes" class="form-control" rows="3" placeholder="Add notes about this status change..."></textarea>
                </div>
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <small>Changing status to Approved or Rejected will automatically record the review date and reviewer.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-dark-red" id="saveStatusBtn">
                    <i class="bi bi-check-circle me-1"></i> Update Status
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('saveStatusBtn').addEventListener('click', function() {
        const newStatus = document.getElementById('newStatus').value;
        const notes = document.getElementById('statusNotes').value;
        const admissionId = <?= $admission['admission_id'] ?>;
        const saveBtn = this;

        // Disable button and show loading
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';

        // Send AJAX request
        fetch('<?= base_url('admission/update-status') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({
                    admission_id: admissionId,
                    status: newStatus,
                    notes: notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('changeStatusModal'));
                    modal.hide();

                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show';
                    alertDiv.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
                    document.querySelector('.row.mb-4').after(alertDiv);

                    // Reload page after 1 second
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert('Error: ' + (data.message || 'Failed to update status'));
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Update Status';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the status');
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Update Status';
            });
    });
</script>
<?= $this->endSection() ?>