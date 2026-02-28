<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">
            <i class="bi bi-tools me-2"></i>Tools
        </h4>
        <p class="text-muted mb-0">Various utility tools to help with your daily tasks.</p>
    </div>
</div>

<!-- Tools Grid -->
<div class="row g-4">
    <?php foreach ($tools as $tool): ?>
        <?php 
        // Check permission
        $hasAccess = true;
        if (!empty($tool['permission'])) {
            $hasAccess = $user && $user->can($tool['permission']);
        }
        ?>
        <?php if ($hasAccess): ?>
            <div class="col-md-6 col-lg-4">
                <a href="<?= base_url($tool['url']) ?>" class="text-decoration-none">
                    <div class="card h-100 tool-card border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="tool-icon mb-3 mx-auto">
                                <i class="bi bi-<?= esc($tool['icon']) ?>"></i>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark"><?= esc($tool['name']) ?></h5>
                            <p class="text-muted mb-0 small"><?= esc($tool['description']) ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pb-4 pt-0">
                            <span class="btn btn-outline-primary btn-sm">
                                Open Tool <i class="bi bi-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        <?php endif ?>
    <?php endforeach ?>
</div>

<!-- Empty State -->
<?php if (empty($tools)): ?>
    <div class="text-center py-5">
        <div class="empty-state-icon mb-4">
            <i class="bi bi-tools"></i>
        </div>
        <h5 class="mt-3 text-muted">No tools available</h5>
        <p class="text-muted">Check back later for new tools.</p>
    </div>
<?php endif ?>

<!-- Quick Stats -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card bg-light border-0">
            <div class="card-body">
                <h6 class="text-muted mb-3">
                    <i class="bi bi-info-circle me-2"></i>Available Tools
                </h6>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="h4 mb-0 text-primary"><?= count($tools) ?></div>
                        <small class="text-muted">Total Tools</small>
                    </div>
                    <div class="col-4">
                        <div class="h4 mb-0 text-success">
                            <?= count(array_filter($tools, fn($t) => empty($t['permission']) || ($user && $user->can($t['permission'])))) ?>
                        </div>
                        <small class="text-muted">Accessible</small>
                    </div>
                    <div class="col-4">
                        <div class="h4 mb-0 text-info">3</div>
                        <small class="text-muted">Categories</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tool-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 12px;
        overflow: hidden;
    }
    .tool-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }
    .tool-card:hover .tool-icon {
        transform: scale(1.1);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .tool-card:hover .tool-icon i {
        color: white;
    }
    .tool-icon {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    .tool-icon i {
        font-size: 2.5rem;
        color: #667eea;
        transition: all 0.3s ease;
    }
    .empty-state-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
        border-radius: 50%;
    }
    .empty-state-icon i {
        font-size: 4rem;
        color: #adb5bd;
    }
    .card-footer .btn {
        transition: all 0.3s ease;
    }
    .tool-card:hover .btn {
        background-color: #667eea;
        border-color: #667eea;
        color: white;
    }
</style>

<?= $this->endSection() ?>
