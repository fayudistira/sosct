<?= $this->extend('Modules\Dashboard\Views\layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-translate me-2"></i>Hanzi Flashcard Manager
            </h1>
            <p class="text-muted mb-0">Manage Chinese characters for flashcard learning</p>
        </div>
        <div class="btn-group">
            <a href="<?= site_url('tools/hanzi/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Hanzi
            </a>
            <a href="<?= site_url('tools/hanzi/bulk-upload') ?>" class="btn btn-outline-primary">
                <i class="bi bi-upload me-1"></i> Bulk Upload
            </a>
            <a href="<?= site_url('tools/hanzi/flashcards') ?>" class="btn btn-success">
                <i class="bi bi-card-text me-1"></i> Practice
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <?php foreach ($categories as $cat): ?>
            <div class="col-lg-1 col-md-2 col-4 mb-2">
                <div class="card text-center h-100">
                    <div class="card-body py-2 px-1">
                        <div class="small text-muted"><?= $cat['category'] ?></div>
                        <div class="h5 mb-0 text-primary"><?= $cat['count'] ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search hanzi or pinyin..." 
                           value="<?= esc($search ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach (['HSK1', 'HSK2', 'HSK3', 'HSK4', 'HSK5', 'HSK6', 'OTHER'] as $cat): ?>
                            <option value="<?= $cat ?>" <?= ($currentCategory ?? '') === $cat ? 'selected' : '' ?>>
                                <?= $cat ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="<?= site_url('tools/hanzi') ?>" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Hanzi List -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Hanzi List</h6>
        </div>
        <div class="card-body">
            <?php if (empty($hanzi)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-journal-x display-1 text-muted"></i>
                    <p class="text-muted mt-3">No hanzi found. Start by adding some characters!</p>
                    <a href="<?= site_url('tools/hanzi/create') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Add First Hanzi
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="hanziTable">
                        <thead>
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th style="width: 80px;">Hanzi</th>
                                <th>Pinyin</th>
                                <th>Category</th>
                                <th>Translation</th>
                                <th>Example</th>
                                <th style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hanzi as $index => $h): ?>
                                <?php 
                                $translation = json_decode($h->translation ?? '{}', true);
                                $example = json_decode($h->example ?? '{}', true);
                                ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <span class="hanzi-display fs-3"><?= esc($h->hanzi) ?></span>
                                    </td>
                                    <td>
                                        <span class="text-primary"><?= esc($h->pinyin) ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= match($h->category) {
                                            'HSK1' => 'success',
                                            'HSK2' => 'info',
                                            'HSK3' => 'primary',
                                            'HSK4' => 'warning',
                                            'HSK5' => 'orange',
                                            'HSK6' => 'danger',
                                            default => 'secondary'
                                        } ?>">
                                            <?= esc($h->category) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($translation['en'])): ?>
                                            <div><small class="text-muted">EN:</small> <?= esc($translation['en']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($translation['id'])): ?>
                                            <div><small class="text-muted">ID:</small> <?= esc($translation['id']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($example['en'])): ?>
                                            <div><small class="text-muted">EN:</small> <?= esc($example['en']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($example['id'])): ?>
                                            <div><small class="text-muted">ID:</small> <?= esc($example['id']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= site_url('tools/hanzi/edit/' . $h->id) ?>" 
                                               class="btn btn-outline-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="deleteHanzi(<?= $h->id ?>, '<?= esc($h->hanzi) ?>')" 
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if (isset($pager)): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the hanzi <strong id="deleteHanziChar"></strong>?</p>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.hanzi-display {
    font-family: "Microsoft YaHei", "SimHei", sans-serif;
    line-height: 1.5;
}
.badge.bg-orange {
    background-color: #fd7e14 !important;
}
</style>

<script>
function deleteHanzi(id, hanzi) {
    document.getElementById('deleteHanziChar').textContent = hanzi;
    document.getElementById('deleteForm').action = '<?= site_url('tools/hanzi/') ?>' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?= $this->endSection() ?>
