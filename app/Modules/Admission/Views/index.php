<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h6 class="card-title">Total Applications</h6>
                    <h3><?= $statusCounts['total'] ?? 0 ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h6 class="card-title">Pending</h6>
                    <h3><?= $statusCounts['pending'] ?? 0 ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6 class="card-title">Approved</h6>
                    <h3><?= $statusCounts['approved'] ?? 0 ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h6 class="card-title">Rejected</h6>
                    <h3><?= $statusCounts['rejected'] ?? 0 ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Actions -->
    <div class="row mb-3">
        <div class="col-md-6">
            <form action="<?= base_url('admission/search') ?>" method="get" class="d-flex">
                <input type="text" name="keyword" class="form-control me-2" placeholder="Search by name, email, registration number..." value="<?= esc($keyword ?? '') ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Search
                </button>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= base_url('admission/create') ?>" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Create New Admission
            </a>
        </div>
    </div>

    <!-- Admissions Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Registration #</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Status</th>
                            <th>Application Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($admissions)): ?>
                            <?php foreach ($admissions as $admission): ?>
                                <tr>
                                    <td><?= esc($admission['registration_number']) ?></td>
                                    <td><?= esc($admission['full_name']) ?></td>
                                    <td><?= esc($admission['email']) ?></td>
                                    <td><?= esc($admission['phone']) ?></td>
                                    <td><?= esc($admission['course']) ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = match($admission['status']) {
                                            'pending' => 'bg-warning',
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst($admission['status']) ?></span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($admission['application_date'])) ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= base_url('admission/view/' . $admission['id']) ?>" class="btn btn-info" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?= base_url('admission/edit/' . $admission['id']) ?>" class="btn btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" title="Delete" onclick="confirmDelete(<?= $admission['id'] ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
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

            <!-- Pagination -->
            <?php if (isset($pager)): ?>
                <div class="mt-3">
                    <?= $pager->links() ?>
                </div>
            <?php endif ?>
        </div>
    </div>
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
