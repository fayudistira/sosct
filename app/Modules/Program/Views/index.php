<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h3>Programs</h3>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= base_url('program/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Program
            </a>
        </div>
    </div>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>
    
    <!-- Search and Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="get" action="<?= base_url('program') ?>">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search programs..." value="<?= esc($keyword ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($status ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="category" class="form-control" 
                               placeholder="Category" value="<?= esc($category ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Programs Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Registration Fee</th>
                            <th>Tuition Fee</th>
                            <th>Discount</th>
                            <th>Status</th>
                            <th>Thumbnail</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($programs)): ?>
                            <?php foreach ($programs as $program): ?>
                            <tr>
                                <td><strong><?= esc($program['title']) ?></strong></td>
                                <td><?= esc($program['category'] ?? '-') ?></td>
                                <td><?= esc($program['sub_category'] ?? '-') ?></td>
                                <td>Rp <?= number_format($program['registration_fee'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?></td>
                                <td><?= number_format($program['discount'], 2) ?>%</td>
                                <td>
                                    <?php if ($program['status'] === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <?php if (!empty($program['thumbnail'])): ?>
                                        <img src="<?= base_url('writable/uploads/programs/thumbs/' . $program['thumbnail']) ?>" 
                                             alt="Thumbnail" style="width: 50px; height: 50px; object-fit: cover;" 
                                             class="rounded">
                                    <?php else: ?>
                                        <span class="text-muted">No image</span>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('program/view/' . $program['id']) ?>" 
                                       class="btn btn-sm btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= base_url('program/edit/' . $program['id']) ?>" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= base_url('program/delete/' . $program['id']) ?>" 
                                       class="btn btn-sm btn-danger" title="Delete"
                                       onclick="return confirm('Are you sure you want to delete this program?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">No programs found.</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (isset($pager)): ?>
                <div class="mt-3">
                    <?= $pager->links() ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
