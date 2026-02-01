<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h3>Welcome to the Dashboard</h3>
            <p class="lead">Select a module from the sidebar to get started.</p>
        </div>
    </div>
    
    <!-- Dashboard Stats -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-file-earmark-text"></i> Total Applications
                    </h5>
                    <p class="card-text display-6"><?= $admissionStats ? $admissionStats['total'] : '-' ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-clock-history"></i> Pending
                    </h5>
                    <p class="card-text display-6"><?= $admissionStats ? $admissionStats['pending'] : '-' ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-check-circle"></i> Approved
                    </h5>
                    <p class="card-text display-6"><?= $admissionStats ? $admissionStats['approved'] : '-' ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-x-circle"></i> Rejected
                    </h5>
                    <p class="card-text display-6"><?= $admissionStats ? $admissionStats['rejected'] : '-' ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Course Statistics -->
    <?php if ($courseStats && !empty($courseStats)): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-mortarboard"></i> Admissions by Course
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Pending</th>
                                    <th class="text-center">Approved</th>
                                    <th class="text-center">Rejected</th>
                                    <th>Status Distribution</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courseStats as $stat): ?>
                                <tr>
                                    <td><strong><?= esc($stat['course']) ?></strong></td>
                                    <td class="text-center">
                                        <span class="badge bg-primary"><?= $stat['total'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark"><?= $stat['pending'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><?= $stat['approved'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger"><?= $stat['rejected'] ?></span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 25px;">
                                            <?php 
                                            $pendingPercent = ($stat['pending'] / $stat['total']) * 100;
                                            $approvedPercent = ($stat['approved'] / $stat['total']) * 100;
                                            $rejectedPercent = ($stat['rejected'] / $stat['total']) * 100;
                                            ?>
                                            <?php if ($stat['pending'] > 0): ?>
                                            <div class="progress-bar bg-warning" role="progressbar" 
                                                 style="width: <?= $pendingPercent ?>%" 
                                                 title="Pending: <?= $stat['pending'] ?> (<?= number_format($pendingPercent, 1) ?>%)">
                                                <?php if ($pendingPercent > 15): ?>
                                                    <?= number_format($pendingPercent, 0) ?>%
                                                <?php endif ?>
                                            </div>
                                            <?php endif ?>
                                            <?php if ($stat['approved'] > 0): ?>
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: <?= $approvedPercent ?>%" 
                                                 title="Approved: <?= $stat['approved'] ?> (<?= number_format($approvedPercent, 1) ?>%)">
                                                <?php if ($approvedPercent > 15): ?>
                                                    <?= number_format($approvedPercent, 0) ?>%
                                                <?php endif ?>
                                            </div>
                                            <?php endif ?>
                                            <?php if ($stat['rejected'] > 0): ?>
                                            <div class="progress-bar bg-danger" role="progressbar" 
                                                 style="width: <?= $rejectedPercent ?>%" 
                                                 title="Rejected: <?= $stat['rejected'] ?> (<?= number_format($rejectedPercent, 1) ?>%)">
                                                <?php if ($rejectedPercent > 15): ?>
                                                    <?= number_format($rejectedPercent, 0) ?>%
                                                <?php endif ?>
                                            </div>
                                            <?php endif ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif ?>
    
    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <h4>Quick Actions</h4>
            <div class="list-group">
                <?php if (isset($menuItems) && !empty($menuItems)): ?>
                    <?php foreach ($menuItems as $item): ?>
                        <?php if ($item['url'] !== 'dashboard'): ?>
                            <a href="<?= base_url($item['url']) ?>" class="list-group-item list-group-item-action">
                                <i class="bi bi-<?= esc($item['icon'] ?? 'circle') ?>"></i>
                                <?= esc($item['title']) ?>
                            </a>
                        <?php endif ?>
                    <?php endforeach ?>
                <?php else: ?>
                    <div class="list-group-item">
                        <p class="mb-0 text-muted">No modules available. Please contact your administrator.</p>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
