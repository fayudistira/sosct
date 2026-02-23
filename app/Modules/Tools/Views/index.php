<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Tools</h4>
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
            $hasAccess = $user->can($tool['permission']);
        }
        ?>
        <?php if ($hasAccess): ?>
            <div class="col-md-6 col-lg-4">
                <a href="<?= base_url($tool['url']) ?>" class="text-decoration-none">
                    <div class="dashboard-card h-100 tool-card">
                        <div class="card-body text-center p-4">
                            <div class="tool-icon mb-3">
                                <i class="bi bi-<?= esc($tool['icon']) ?>" style="font-size: 3rem; color: var(--dark-red);"></i>
                            </div>
                            <h5 class="fw-bold mb-2"><?= esc($tool['name']) ?></h5>
                            <p class="text-muted mb-0 small"><?= esc($tool['description']) ?></p>
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
        <i class="bi bi-tools" style="font-size: 4rem; color: var(--light-text);"></i>
        <h5 class="mt-3 text-muted">No tools available</h5>
        <p class="text-muted">Check back later for new tools.</p>
    </div>
<?php endif ?>

<style>
    .tool-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .tool-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    .tool-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--light-red);
        border-radius: 50%;
    }
</style>

<?= $this->endSection() ?>