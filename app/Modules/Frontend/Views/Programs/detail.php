<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<?php
// Calculate final price with discount
$finalPrice = $program['tuition_fee'];
if (!empty($program['discount']) && $program['discount'] > 0) {
    $finalPrice = $program['tuition_fee'] * (1 - $program['discount'] / 100);
}
?>
<!-- Breadcrumb -->
<div class="bg-light py-2 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('programs') ?>">Programs</a></li>
                <li class="breadcrumb-item active text-truncate" style="max-width: 300px;"><?= esc($program['title']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Program Detail -->
<div class="container py-4">
    <div class="row g-3">
        <!-- Left Column: Image and Quick Info -->
        <div class="col-lg-4">
            <!-- Program Image -->
            <div class="card border-0 shadow-sm mb-3 overflow-hidden">
                <?php if (!empty($program['thumbnail'])): ?>
                    <img src="<?= base_url('uploads/programs/thumbs/' . $program['thumbnail']) ?>"
                        alt="<?= esc($program['title']) ?>"
                        class="card-img-top"
                        style="width: 100%; height: 220px; object-fit: cover;">
                <?php else: ?>
                    <?php
                    // Generate a consistent random seed based on program ID for consistent images
                    $seed = crc32($program['id']);
                    $randomId = ($seed % 1000) + 1;
                    ?>
                    <img src="https://picsum.photos/seed/<?= $randomId ?>/800/600"
                        alt="<?= esc($program['title']) ?>"
                        class="card-img-top"
                        style="width: 100%; height: 220px; object-fit: cover;"
                        loading="lazy">
                <?php endif ?>
            </div>

            <!-- Quick Info Card -->
            <div class="card border-0 shadow-sm sticky-info mb-3">
                <div class="card-header text-white py-2" style="background: linear-gradient(135deg, var(--dark-red) 0%, var(--medium-red) 100%);">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Biaya</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                        <div class="flex-shrink-0">
                            <i class="bi bi-cash-coin fs-5 text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Registrasi</small>
                            <span class="fw-bold small">Rp <?= number_format($program['registration_fee'], 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-credit-card fs-5 text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Biaya Kursus</small>
                            <?php if ($program['discount'] > 0): ?>
                                <div>
                                    <span class="text-decoration-line-through text-muted" style="font-size: 0.75rem;">
                                        Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?>
                                    </span>
                                    <span class="badge bg-success ms-1" style="font-size: 0.65rem;"><?= number_format($program['discount'], 0) ?>% OFF</span>
                                </div>
                                <div class="fw-bold" style="color: var(--dark-red); font-size: 1.1rem;">
                                    Rp <?= number_format($finalPrice, 0, ',', '.') ?>
                                </div>
                            <?php else: ?>
                                <div class="fw-bold" style="color: var(--dark-red); font-size: 1.1rem;">
                                    Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?>
                                </div>
                            <?php endif ?>
                            <small class="text-muted" style="font-size: 0.7rem;">per program</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card border-0 shadow-sm action-card-compact">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('apply/' . $program['id']) ?>"
                            class="btn btn-apply-compact rounded">
                            <i class="bi bi-pencil-square me-1"></i>Daftar Sekarang
                        </a>
                        <?php
                        // Prepare WhatsApp share message
                        $shareUrl = urlencode(base_url('programs/' . $program['id']));
                        $shareText = "Check out this program:%0A" . urlencode($program['title']) . "%0A%0A";
                        $shareText .= "Registration Fee: Rp " . number_format($program['registration_fee'], 0, ',', '.') . "%0A";
                        $shareText .= "Tuition Fee: Rp " . number_format($program['tuition_fee'], 0, ',', '.') . "%0A";
                        if ($program['discount'] > 0) {
                            $shareText .= "Discount: " . $program['discount'] . "%25";
                        }
                        $whatsappShareUrl = 'https://wa.me/?text=' . $shareText . '%0A%0A' . $shareUrl;
                        ?>
                        <a href="<?= $whatsappShareUrl ?>"
                            target="_blank"
                            class="btn btn-outline-success btn-sm rounded">
                            <i class="bi bi-share me-1"></i>Share ke WhatsApp
                        </a>
                        <a href="https://wa.me/<?= config('App')->adminWhatsApp ?? '6281234567890' ?>?text=<?= urlencode("Hello, I'm interested in the " . $program['title'] . " program.") ?>"
                            target="_blank"
                            class="btn btn-success-compact rounded">
                            <i class="bi bi-whatsapp me-1"></i>Konsultasi dengan Admin
                        </a>
                        <a href="<?= base_url('programs') ?>"
                            class="btn btn-outline-secondary btn-sm rounded">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Program Details -->
        <div class="col-lg-8">
            <!-- Program Title -->
            <div class="mb-3">
                <h2 class="fw-bold mb-2" style="color: #2c3e50; font-size: 1.75rem;"><?= esc($program['title']) ?></h2>

                <!-- Meta Badges -->
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <?php if (!empty($program['language'])): ?>
                        <span class="badge bg-info px-2 py-1" style="font-size: 0.75rem;">
                            <i class="bi bi-translate me-1"></i><?= esc($program['language']) ?>
                        </span>
                    <?php endif ?>

                    <?php if (!empty($program['language_level'])): ?>
                        <span class="badge bg-secondary px-2 py-1" style="font-size: 0.75rem;">
                            <i class="bi bi-bar-chart-fill me-1"></i><?= esc($program['language_level']) ?>
                        </span>
                    <?php endif ?>

                    <?php if (!empty($program['mode'])): ?>
                        <?php if ($program['mode'] === 'online'): ?>
                            <span class="badge bg-primary px-2 py-1" style="font-size: 0.75rem;">
                                <i class="bi bi-laptop me-1"></i>Online
                            </span>
                        <?php else: ?>
                            <span class="badge bg-success px-2 py-1" style="font-size: 0.75rem;">
                                <i class="bi bi-building me-1"></i>Tatap Muka
                            </span>
                        <?php endif ?>
                    <?php endif ?>

                    <?php if (!empty($program['category'])): ?>
                        <span class="badge px-2 py-1" style="background-color: var(--dark-red); font-size: 0.75rem;">
                            <i class="bi bi-bookmark-fill me-1"></i><?= esc($program['category']) ?>
                        </span>
                    <?php endif ?>

                    <?php if (!empty($program['sub_category'])): ?>
                        <span class="badge bg-secondary px-2 py-1" style="font-size: 0.75rem;">
                            <i class="bi bi-tag me-1"></i><?= esc($program['sub_category']) ?>
                        </span>
                    <?php endif ?>

                    <?php if (!empty($program['duration'])): ?>
                        <span class="badge bg-dark px-2 py-1" style="font-size: 0.75rem;">
                            <i class="bi bi-clock me-1"></i><?= esc($program['duration']) ?>
                        </span>
                    <?php endif ?>
                </div>
            </div>

            <!-- Description -->
            <?php if (!empty($program['description'])): ?>
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-3">
                        <h6 class="card-title fw-bold mb-2">
                            <i class="bi bi-file-text-fill me-2" style="color: var(--dark-red);"></i>Tentang Program
                        </h6>
                        <p class="card-text text-muted mb-0 small lh-base"><?= nl2br(esc((string)($program['description'] ?? ''))) ?></p>
                    </div>
                </div>
            <?php endif ?>

            <!-- Features, Facilities, Extra Facilities in Tabs -->
            <?php if ((!empty($program['features']) && is_array($program['features'])) ||
                (!empty($program['facilities']) && is_array($program['facilities'])) ||
                (!empty($program['extra_facilities']) && is_array($program['extra_facilities']))
            ): ?>
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-3">
                        <ul class="nav nav-pills nav-fill mb-3" id="programTabs" role="tablist">
                            <?php if (!empty($program['features']) && is_array($program['features'])): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active small py-2" id="features-tab" data-bs-toggle="tab" data-bs-target="#features" type="button">
                                        <i class="bi bi-star-fill me-1"></i>Keunggulan
                                    </button>
                                </li>
                            <?php endif ?>
                            <?php if (!empty($program['facilities']) && is_array($program['facilities'])): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?= empty($program['features']) ? 'active' : '' ?> small py-2" id="facilities-tab" data-bs-toggle="tab" data-bs-target="#facilities" type="button">
                                        <i class="bi bi-building-fill me-1"></i>Fasilitas
                                    </button>
                                </li>
                            <?php endif ?>
                            <?php if (!empty($program['extra_facilities']) && is_array($program['extra_facilities'])): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?= empty($program['features']) && empty($program['facilities']) ? 'active' : '' ?> small py-2" id="extra-tab" data-bs-toggle="tab" data-bs-target="#extra" type="button">
                                        <i class="bi bi-plus-circle-fill me-1"></i>Extra
                                    </button>
                                </li>
                            <?php endif ?>
                        </ul>

                        <div class="tab-content" id="programTabsContent">
                            <?php if (!empty($program['features']) && is_array($program['features'])): ?>
                                <div class="tab-pane fade show active" id="features" role="tabpanel">
                                    <div class="row g-2">
                                        <?php foreach ($program['features'] as $feature): ?>
                                            <div class="col-md-6">
                                                <div class="feature-item-compact">
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                    <span><?= esc($feature) ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            <?php endif ?>

                            <?php if (!empty($program['facilities']) && is_array($program['facilities'])): ?>
                                <div class="tab-pane fade <?= empty($program['features']) ? 'show active' : '' ?>" id="facilities" role="tabpanel">
                                    <div class="row g-2">
                                        <?php foreach ($program['facilities'] as $facility): ?>
                                            <div class="col-md-6">
                                                <div class="feature-item-compact">
                                                    <i class="bi bi-check-circle-fill text-primary"></i>
                                                    <span><?= esc($facility) ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            <?php endif ?>

                            <?php if (!empty($program['extra_facilities']) && is_array($program['extra_facilities'])): ?>
                                <div class="tab-pane fade <?= empty($program['features']) && empty($program['facilities']) ? 'show active' : '' ?>" id="extra" role="tabpanel">
                                    <div class="row g-2">
                                        <?php foreach ($program['extra_facilities'] as $extra): ?>
                                            <div class="col-md-6">
                                                <div class="feature-item-compact">
                                                    <i class="bi bi-check-circle-fill text-warning"></i>
                                                    <span><?= esc($extra) ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <!-- Curriculum -->
            <?php if (!empty($program['curriculum']) && is_array($program['curriculum'])): ?>
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-3">
                        <h6 class="card-title fw-bold mb-3">
                            <i class="bi bi-journal-text me-2" style="color: var(--dark-red);"></i>Course Curriculum
                        </h6>
                        <div class="accordion accordion-flush" id="curriculumAccordion">
                            <?php foreach ($program['curriculum'] as $index => $chapter): ?>
                                <div class="accordion-item border rounded mb-2">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button <?= $index !== 0 ? 'collapsed' : '' ?> py-2 small"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#chapter-<?= $index ?>"
                                            aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>">
                                            <span class="badge bg-dark me-2" style="font-size: 0.7rem;"><?= $index + 1 ?></span>
                                            <strong style="font-size: 0.9rem;"><?= esc($chapter['chapter']) ?></strong>
                                        </button>
                                    </h2>
                                    <div id="chapter-<?= $index ?>"
                                        class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>"
                                        data-bs-parent="#curriculumAccordion">
                                        <div class="accordion-body py-2 px-3">
                                            <p class="text-muted mb-0 small">
                                                <i class="bi bi-info-circle me-1"></i>
                                                <?= esc($chapter['description']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<style>
    /* Breadcrumb Styling */
    .breadcrumb {
        background-color: transparent;
        padding: 0;
        font-size: 0.85rem;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        content: "›";
        color: #6c757d;
    }

    .breadcrumb-item a {
        color: var(--dark-red);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .breadcrumb-item a:hover {
        color: var(--medium-red);
    }

    .breadcrumb-item.active {
        color: #6c757d;
    }

    /* Sticky Info Card */
    .sticky-info {
        position: sticky;
        top: 20px;
    }

    /* Feature Items Compact */
    .feature-item-compact {
        display: flex;
        align-items: start;
        padding: 0.4rem 0.6rem;
        background-color: #f8f9fa;
        border-radius: 6px;
        transition: all 0.2s ease;
        font-size: 0.85rem;
    }

    .feature-item-compact:hover {
        background-color: #e9ecef;
        transform: translateX(3px);
    }

    .feature-item-compact i {
        font-size: 0.9rem;
        margin-top: 2px;
        margin-right: 0.5rem;
    }

    .feature-item-compact span {
        flex: 1;
        color: #495057;
    }

    /* Action Card Compact */
    .action-card-compact {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 1px solid #e0e0e0 !important;
    }

    /* Apply Button Compact */
    .btn-apply-compact {
        background: linear-gradient(135deg, var(--dark-red) 0%, var(--medium-red) 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(139, 0, 0, 0.2);
        font-size: 0.9rem;
    }

    .btn-apply-compact:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 0, 0, 0.3);
        color: white;
    }

    /* WhatsApp Button Compact */
    .btn-success-compact {
        background-color: #25d366;
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(37, 211, 102, 0.2);
        font-size: 0.9rem;
    }

    .btn-success-compact:hover {
        background-color: #20ba5a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
        color: white;
    }

    /* Card Hover Effects */
    .card {
        transition: all 0.2s ease;
    }

    .card:hover {
        transform: translateY(-1px);
    }

    /* Tabs Styling */
    .nav-pills .nav-link {
        color: #6c757d;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .nav-pills .nav-link:hover {
        background-color: #f8f9fa;
    }

    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, var(--dark-red) 0%, var(--medium-red) 100%);
    }

    /* Curriculum Accordion Compact */
    .accordion-item {
        border: 1px solid #e0e0e0 !important;
    }

    .accordion-button {
        background-color: #f8f9fa;
        color: #2c3e50;
        font-weight: 500;
    }

    .accordion-button:not(.collapsed) {
        background-color: var(--light-red);
        color: var(--dark-red);
        box-shadow: none;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: var(--dark-red);
    }

    .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%238B0000'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .accordion-body {
        background-color: white;
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