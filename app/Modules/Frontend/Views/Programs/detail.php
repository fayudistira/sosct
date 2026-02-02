<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Breadcrumb -->
<div class="bg-light py-3 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('programs') ?>">Programs</a></li>
                <li class="breadcrumb-item active"><?= esc($program['title']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Program Detail -->
<div class="container py-5">
    <div class="row g-4">
        <!-- Left Column: Image and Quick Info -->
        <div class="col-lg-5">
            <!-- Program Image -->
            <div class="card border-0 shadow-sm mb-4 program-image-card">
                <?php if (!empty($program['thumbnail'])): ?>
                    <img src="<?= base_url('uploads/programs/thumbs/' . $program['thumbnail']) ?>" 
                         alt="<?= esc($program['title']) ?>" 
                         class="card-img-top rounded"
                         style="width: 100%; height: 400px; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                         style="height: 400px;">
                        <i class="bi bi-mortarboard" style="font-size: 5rem; color: #ccc;"></i>
                    </div>
                <?php endif ?>
            </div>
            
            <!-- Quick Info Card -->
            <div class="card border-0 shadow-sm sticky-info">
                <div class="card-header text-white" style="background: linear-gradient(135deg, var(--dark-red) 0%, var(--medium-red) 100%);">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Program Information</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($program['category'])): ?>
                        <div class="info-item mb-3">
                            <div class="info-label">
                                <i class="bi bi-tag me-2 text-primary"></i>Category
                            </div>
                            <div class="info-value"><?= esc($program['category']) ?></div>
                        </div>
                    <?php endif ?>
                    
                    <?php if (!empty($program['sub_category'])): ?>
                        <div class="info-item mb-3">
                            <div class="info-label">
                                <i class="bi bi-tags me-2 text-info"></i>Sub Category
                            </div>
                            <div class="info-value"><?= esc($program['sub_category']) ?></div>
                        </div>
                    <?php endif ?>
                    
                    <hr class="my-3">
                    
                    <div class="info-item mb-3">
                        <div class="info-label">
                            <i class="bi bi-cash-coin me-2 text-warning"></i>Registration Fee
                        </div>
                        <div class="info-value">Rp <?= number_format($program['registration_fee'], 0, ',', '.') ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-credit-card me-2 text-success"></i>Tuition Fee
                        </div>
                        <?php if ($program['discount'] > 0): ?>
                            <div class="mb-1">
                                <span class="text-decoration-line-through text-muted small">
                                    Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?>
                                </span>
                                <span class="badge bg-success ms-2"><?= number_format($program['discount'], 0) ?>% OFF</span>
                            </div>
                            <div class="h4 fw-bold mb-0" style="color: var(--dark-red);">
                                Rp <?= number_format($finalPrice, 0, ',', '.') ?>
                            </div>
                        <?php else: ?>
                            <div class="h4 fw-bold mb-0" style="color: var(--dark-red);">
                                Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?>
                            </div>
                        <?php endif ?>
                        <small class="text-muted d-block mt-1">per semester</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Program Details -->
        <div class="col-lg-7">
            <!-- Program Title -->
            <div class="mb-4">
                <h1 class="display-5 fw-bold mb-3" style="color: #2c3e50;"><?= esc($program['title']) ?></h1>
                <?php if (!empty($program['category'])): ?>
                    <span class="badge px-3 py-2" style="background-color: var(--dark-red); font-size: 0.9rem;">
                        <i class="bi bi-bookmark-fill me-1"></i><?= esc($program['category']) ?>
                    </span>
                <?php endif ?>
            </div>
            
            <!-- Description -->
            <?php if (!empty($program['description'])): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">
                            <i class="bi bi-file-text-fill me-2" style="color: var(--dark-red);"></i>Description
                        </h5>
                        <p class="card-text text-muted lh-lg"><?= nl2br(esc($program['description'])) ?></p>
                    </div>
                </div>
            <?php endif ?>
            
            <!-- Features -->
            <?php if (!empty($program['features']) && is_array($program['features'])): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">
                            <i class="bi bi-star-fill me-2" style="color: var(--dark-red);"></i>Program Features
                        </h5>
                        <div class="row g-3">
                            <?php foreach ($program['features'] as $feature): ?>
                                <div class="col-md-6">
                                    <div class="feature-item">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span><?= esc($feature) ?></span>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            
            <!-- Facilities -->
            <?php if (!empty($program['facilities']) && is_array($program['facilities'])): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">
                            <i class="bi bi-building-fill me-2" style="color: var(--dark-red);"></i>Facilities
                        </h5>
                        <div class="row g-3">
                            <?php foreach ($program['facilities'] as $facility): ?>
                                <div class="col-md-6">
                                    <div class="feature-item">
                                        <i class="bi bi-check-circle-fill text-primary me-2"></i>
                                        <span><?= esc($facility) ?></span>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            
            <!-- Extra Facilities -->
            <?php if (!empty($program['extra_facilities']) && is_array($program['extra_facilities'])): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">
                            <i class="bi bi-plus-circle-fill me-2" style="color: var(--dark-red);"></i>Extra Facilities
                        </h5>
                        <div class="row g-3">
                            <?php foreach ($program['extra_facilities'] as $extra): ?>
                                <div class="col-md-6">
                                    <div class="feature-item">
                                        <i class="bi bi-check-circle-fill text-warning me-2"></i>
                                        <span><?= esc($extra) ?></span>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            
            <!-- Action Buttons -->
            <div class="card border-0 shadow-lg action-card">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-center">
                        <i class="bi bi-rocket-takeoff-fill me-2" style="color: var(--dark-red);"></i>Ready to Join?
                    </h5>
                    <div class="d-grid gap-3">
                        <a href="<?= base_url('apply/' . $program['id']) ?>" 
                           class="btn btn-lg btn-apply-now">
                            <i class="bi bi-pencil-square me-2"></i>Apply for This Program
                        </a>
                        <a href="https://wa.me/<?= config('App')->adminWhatsApp ?? '6281234567890' ?>?text=<?= urlencode("Hello, I'm interested in the " . $program['title'] . " program. Can you provide more information?") ?>" 
                           target="_blank"
                           class="btn btn-success btn-lg btn-whatsapp">
                            <i class="bi bi-whatsapp me-2"></i>Ask Admin via WhatsApp
                        </a>
                        <a href="<?= base_url('programs') ?>" 
                           class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>Back to Programs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Breadcrumb Styling */
.breadcrumb {
    background-color: transparent;
    padding: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #6c757d;
    font-size: 1.2rem;
}

.breadcrumb-item a {
    color: var(--dark-red);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.breadcrumb-item a:hover {
    color: var(--medium-red);
    text-decoration: underline;
}

.breadcrumb-item.active {
    color: #6c757d;
    font-weight: 500;
}

/* Program Image Card */
.program-image-card {
    overflow: hidden;
    transition: transform 0.3s ease;
}

.program-image-card:hover {
    transform: scale(1.02);
}

.program-image-card img {
    transition: transform 0.5s ease;
}

.program-image-card:hover img {
    transform: scale(1.05);
}

/* Sticky Info Card */
.sticky-info {
    position: sticky;
    top: 20px;
}

/* Info Items */
.info-item {
    padding: 0.5rem 0;
}

.info-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.info-value {
    font-size: 1rem;
    font-weight: 600;
    color: #2c3e50;
}

/* Feature Items */
.feature-item {
    display: flex;
    align-items: start;
    padding: 0.5rem;
    background-color: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.feature-item:hover {
    background-color: #e9ecef;
    transform: translateX(5px);
}

.feature-item i {
    font-size: 1.1rem;
    margin-top: 2px;
}

.feature-item span {
    flex: 1;
    color: #495057;
    font-weight: 500;
}

/* Action Card */
.action-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 2px solid var(--dark-red) !important;
}

/* Apply Now Button */
.btn-apply-now {
    background: linear-gradient(135deg, var(--dark-red) 0%, var(--medium-red) 100%);
    border: none;
    color: white;
    font-weight: 600;
    padding: 1rem 2rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(139, 0, 0, 0.3);
}

.btn-apply-now:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(139, 0, 0, 0.4);
    color: white;
}

/* WhatsApp Button */
.btn-whatsapp {
    font-weight: 600;
    padding: 1rem 2rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
}

.btn-whatsapp:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
}

/* Card Hover Effects */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 991px) {
    .sticky-info {
        position: relative;
        top: 0;
    }
}
</style>

<?= $this->endSection() ?>
