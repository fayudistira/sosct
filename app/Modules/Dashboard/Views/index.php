<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h3>Welcome to the Dashboard</h3>
            <p class="lead">Overview of your system statistics</p>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mt-4">
        <!-- Admission Statistics Card -->
        <?php if ($admissionStats): ?>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text"></i> Admission Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h2 class="text-primary mb-0"><?= $admissionStats['total'] ?></h2>
                                <small class="text-muted">Total Applications</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-warning bg-opacity-10 rounded">
                                <h2 class="text-warning mb-0"><?= $admissionStats['pending'] ?></h2>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-success bg-opacity-10 rounded">
                                <h2 class="text-success mb-0"><?= $admissionStats['approved'] ?></h2>
                                <small class="text-muted">Approved</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-danger bg-opacity-10 rounded">
                                <h2 class="text-danger mb-0"><?= $admissionStats['rejected'] ?></h2>
                                <small class="text-muted">Rejected</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-grid">
                        <a href="<?= base_url('admission') ?>" class="btn btn-primary">
                            <i class="bi bi-arrow-right-circle"></i> View All Admissions
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif ?>
        
        <!-- Program Statistics Card -->
        <?php if ($programStats && !empty($programStats)): ?>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-mortarboard"></i> Programs by Category
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th class="text-center">Programs</th>
                                    <th width="40%">Distribution</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalPrograms = array_sum(array_column($programStats, 'total'));
                                foreach ($programStats as $stat): 
                                    $percentage = ($stat['total'] / $totalPrograms) * 100;
                                ?>
                                <tr>
                                    <td><strong><?= esc($stat['category']) ?></strong></td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><?= $stat['total'] ?></span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: <?= $percentage ?>%" 
                                                 title="<?= number_format($percentage, 1) ?>%">
                                                <?php if ($percentage > 10): ?>
                                                    <?= number_format($percentage, 0) ?>%
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <th>Total</th>
                                    <th class="text-center">
                                        <span class="badge bg-primary"><?= $totalPrograms ?></span>
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <hr>
                    <div class="d-grid">
                        <a href="<?= base_url('program') ?>" class="btn btn-success">
                            <i class="bi bi-arrow-right-circle"></i> View All Programs
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif ?>
    </div>
</div>
<?= $this->endSection() ?>
