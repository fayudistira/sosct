<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h3>Program Details</h3>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= base_url('program') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
            <a href="<?= base_url('program/edit/' . $program['id']) ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="card-title"><?= esc($program['title']) ?></h5>
                    <p class="text-muted"><?= esc($program['description'] ?? 'No description') ?></p>
                </div>
                <div class="col-md-4 text-end">
                    <?php if (!empty($program['thumbnail'])): ?>
                        <img src="<?= base_url('writable/uploads/programs/thumbs/' . $program['thumbnail']) ?>" 
                             alt="<?= esc($program['title']) ?>" 
                             class="img-thumbnail mb-2" 
                             style="max-width: 200px; max-height: 200px; object-fit: cover;">
                        <br>
                    <?php endif ?>
                    <?php if ($program['status'] === 'active'): ?>
                        <span class="badge bg-success fs-6">Active</span>
                    <?php else: ?>
                        <span class="badge bg-secondary fs-6">Inactive</span>
                    <?php endif ?>
                </div>
            </div>
            
            <hr>
            
            <div class="row">
                <div class="col-md-6">
                    <h6>Category Information</h6>
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Category:</th>
                            <td><?= esc($program['category'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <th>Sub Category:</th>
                            <td><?= esc($program['sub_category'] ?? '-') ?></td>
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-6">
                    <h6>Fee Information</h6>
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Registration Fee:</th>
                            <td>Rp <?= number_format($program['registration_fee'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <th>Tuition Fee:</th>
                            <td>Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <th>Discount:</th>
                            <td><?= number_format($program['discount'], 2) ?>%</td>
                        </tr>
                        <tr>
                            <th>Final Tuition Fee:</th>
                            <td class="fw-bold text-success">
                                Rp <?= number_format($program['tuition_fee'] * (1 - $program['discount'] / 100), 0, ',', '.') ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <hr>
            
            <div class="row">
                <div class="col-md-4">
                    <h6>Features</h6>
                    <?php if (!empty($program['features']) && is_array($program['features'])): ?>
                        <ul class="list-group">
                            <?php foreach ($program['features'] as $feature): ?>
                                <li class="list-group-item">
                                    <i class="bi bi-check-circle text-success"></i> <?= esc($feature) ?>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No features listed</p>
                    <?php endif ?>
                </div>
                
                <div class="col-md-4">
                    <h6>Facilities</h6>
                    <?php if (!empty($program['facilities']) && is_array($program['facilities'])): ?>
                        <ul class="list-group">
                            <?php foreach ($program['facilities'] as $facility): ?>
                                <li class="list-group-item">
                                    <i class="bi bi-building text-primary"></i> <?= esc($facility) ?>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No facilities listed</p>
                    <?php endif ?>
                </div>
                
                <div class="col-md-4">
                    <h6>Extra Facilities</h6>
                    <?php if (!empty($program['extra_facilities']) && is_array($program['extra_facilities'])): ?>
                        <ul class="list-group">
                            <?php foreach ($program['extra_facilities'] as $extra): ?>
                                <li class="list-group-item">
                                    <i class="bi bi-star text-warning"></i> <?= esc($extra) ?>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No extra facilities listed</p>
                    <?php endif ?>
                </div>
            </div>
            
            <hr>
            
            <div class="row">
                <div class="col-md-12">
                    <small class="text-muted">
                        Created: <?= date('d M Y H:i', strtotime($program['created_at'])) ?> | 
                        Updated: <?= date('d M Y H:i', strtotime($program['updated_at'])) ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
