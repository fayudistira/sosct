<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Admission Management</h4>
        <p class="text-muted mb-0">Manage student admission applications</p>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('admission/create') ?>" class="btn btn-dark-red">
            <i class="bi bi-plus-circle me-1"></i> Create New
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card stat-card">
            <div class="card-body compact">
                <div class="stat-label">Total Applications</div>
                <div class="stat-number"><?= $statusCounts['total'] ?? 0 ?></div>
                <div class="stat-change">
                    <i class="bi bi-file-earmark-text me-1"></i>All submissions
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card stat-card">
            <div class="card-body compact">
                <div class="stat-label">Pending Review</div>
                <div class="stat-number"><?= $statusCounts['pending'] ?? 0 ?></div>
                <div class="stat-change">
                    <i class="bi bi-clock-history me-1"></i>Awaiting decision
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card stat-card">
            <div class="card-body compact">
                <div class="stat-label">Approved</div>
                <div class="stat-number"><?= $statusCounts['approved'] ?? 0 ?></div>
                <div class="stat-change positive">
                    <i class="bi bi-check-circle me-1"></i>Accepted
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card stat-card">
            <div class="card-body compact">
                <div class="stat-label">Rejected</div>
                <div class="stat-number"><?= $statusCounts['rejected'] ?? 0 ?></div>
                <div class="stat-change negative">
                    <i class="bi bi-x-circle me-1"></i>Not admitted
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Bar -->
<div class="row mb-3">
    <div class="col-md-6">
        <form action="<?= base_url('admission/search') ?>" method="get">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="Search by name, email, registration number..." value="<?= esc($keyword ?? '') ?>">
                <button type="submit" class="btn btn-dark-red">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Admissions Table -->
<div class="dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-file-earmark-text me-2"></i>Admission Applications</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover compact-table mb-0">
                <thead>
                    <tr>
                        <th>Registration #</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Program</th>
                        <th>Status</th>
                        <th>Application Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($admissions)): ?>
                        <?php foreach ($admissions as $admission): ?>
                            <tr>
                                <td class="fw-medium"><?= esc($admission['registration_number']) ?></td>
                                <td><?= esc($admission['full_name']) ?></td>
                                <td><?= esc($admission['email']) ?></td>
                                <td><?= esc($admission['phone']) ?></td>
                                <td><?= esc($admission['program_title'] ?? 'N/A') ?></td>
                                <td>
                                    <?php
                                    $badgeClass = match($admission['status']) {
                                        'pending' => 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25',
                                        'approved' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
                                        'rejected' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25',
                                        default => 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25'
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst($admission['status']) ?></span>
                                </td>
                                <td><?= date('M d, Y', strtotime($admission['application_date'])) ?></td>
                                <td class="text-end table-actions">
                                    <a href="<?= base_url('admission/view/' . $admission['id']) ?>" class="btn btn-outline-dark-red btn-sm" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= base_url('admission/edit/' . $admission['id']) ?>" class="btn btn-outline-dark-red btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-dark-red btn-sm" title="Delete" onclick="confirmDelete(<?= $admission['id'] ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No admissions found</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div class="card-body">
            <nav>
                <ul class="pagination justify-content-center mb-0">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($currentPage ?? 1) == $i ? 'active' : '' ?>">
                            <a class="page-link" href="<?= base_url('admission?page=' . $i) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor ?>
                </ul>
            </nav>
        </div>
    <?php endif ?>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this admission?')) {
        fetch('<?= base_url('admission/delete/') ?>' + id, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(() => {
            window.location.reload();
        });
    }
}
</script>
<?= $this->endSection() ?>
