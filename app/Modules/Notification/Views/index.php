<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-bell me-2"></i>Notifications
                    </h5>
                    <button class="btn btn-sm btn-outline-primary" id="markAllReadBtn">
                        <i class="bi bi-check-all me-1"></i>Mark All as Read
                    </button>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($notifications)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-bell-slash fs-1 text-muted"></i>
                            <p class="text-muted mt-3">No notifications yet</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($notifications as $notification): ?>
                                <div class="list-group-item notification-item" data-id="<?= $notification['id'] ?>">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="feature-icon-sm" style="width: 40px; height: 40px; font-size: 1rem;">
                                                <i class="bi <?= $notification['type'] === 'new_admission' ? 'bi-person-plus-fill' : 'bi-bell-fill' ?>"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-1"><?= esc($notification['title']) ?></h6>
                                                <small class="text-muted">
                                                    <?= \CodeIgniter\I18n\Time::parse($notification['created_at'])->humanize() ?>
                                                </small>
                                            </div>
                                            <p class="mb-1 text-muted"><?= esc($notification['message']) ?></p>
                                            <?php if (!empty($notification['data']['registration_number'])): ?>
                                                <a href="<?= base_url('admission/view/' . ($notification['data']['admission_id'] ?? '')) ?>" 
                                                   class="btn btn-sm btn-outline-primary mt-2">
                                                    <i class="bi bi-eye me-1"></i>View Admission
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .feature-icon-sm {
        background: linear-gradient(135deg, var(--dark-red) 0%, var(--medium-red) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .notification-item {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .notification-item:hover {
        background-color: var(--light-red);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
        fetch('<?= base_url('notifications/api/mark-all-read') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });
</script>
<?= $this->endSection() ?>