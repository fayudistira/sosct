<?php
$program = $program ?? [];
if (empty($program)) return;

$seed = crc32($program['id']);
$randomId = ($seed % 1000) + 1;

$shareUrl = urlencode(base_url('programs/' . $program['id']));
$shareText = "Program: " . $program['title'] . "%0A%0A";
$shareText .= "Registrasi: Rp " . number_format($program['registration_fee'], 0, ',', '.') . "%0A";
$shareText .= "Biaya Kursus: Rp " . number_format($program['tuition_fee'] * (1 - ($program['discount'] ?? 0) / 100), 0, ',', '.') . "%0A";
if (!empty($program['discount']) && $program['discount'] > 0) {
    $shareText .= "Diskon: " . $program['discount'] . "%25";
}
$whatsappShareUrl = 'https://wa.me/?text=' . $shareText . '%0A%0A' . $shareUrl;

$finalPrice = $program['tuition_fee'] * (1 - ($program['discount'] ?? 0) / 100);
?>
<div class="col-md-6 col-lg-4">
    <div class="card border-0 shadow-sm h-100 hover-lift overflow-hidden program-card-modern">
        <div class="position-relative overflow-hidden" style="height: 200px;">
            <?php if (!empty($program['thumbnail'])): ?>
                <img src="<?= base_url('uploads/programs/thumbs/' . $program['thumbnail']) ?>"
                    alt="<?= esc($program['title']) ?>"
                    class="w-100 h-100 object-fit-cover program-img-zoom">
            <?php else: ?>
                <img src="https://picsum.photos/seed/<?= $randomId ?>/800/600"
                    alt="<?= esc($program['title']) ?>"
                    class="w-100 h-100 object-fit-cover program-img-zoom"
                    loading="lazy">
            <?php endif ?>

            <div class="position-absolute top-0 end-0 m-3">
                <span class="badge bg-white text-dark shadow-sm py-2 px-3 rounded-pill fw-bold" style="font-size: 0.7rem;">
                    <?= strtoupper(esc($program['sub_category'] ?? 'Standard')) ?>
                </span>
            </div>
            <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark text-white">
                <div class="d-flex align-items-baseline">
                    <span class="h4 fw-bold mb-0">Rp <?= number_format($finalPrice, 0, ',', '.') ?></span>
                    <?php if (!empty($program['discount']) && $program['discount'] > 0): ?>
                        <span class="ms-2 text-white-50 text-decoration-line-through small">Rp <?= number_format($program['tuition_fee'], 0, ',', '.') ?></span>
                        <span class="ms-auto badge bg-danger rounded-pill">-<?= $program['discount'] ?>%</span>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="card-body d-flex flex-column p-4">
            <h5 class="fw-bold mb-2 text-dark"><?= esc($program['title']) ?></h5>
            <div class="d-flex flex-wrap gap-2 mb-2">
                <?php if (!empty($program['language'])): ?>
                    <span class="badge bg-info text-white"><i class="bi bi-translate me-1"></i><?= esc($program['language']) ?></span>
                <?php endif ?>
                <?php if (!empty($program['language_level'])): ?>
                    <span class="badge bg-secondary"><?= esc($program['language_level']) ?></span>
                <?php endif ?>
                <?php if (!empty($program['mode'])): ?>
                    <span class="badge bg-light text-muted border"><i class="bi bi-<?= ($program['mode'] === 'online' ? 'wifi' : 'building') ?> me-1"></i><?= ucfirst($program['mode']) ?></span>
                <?php endif ?>
            </div>
            <p class="text-muted small flex-grow-1 mb-4">
                <?= esc(strlen($program['description'] ?? '') > 120 ? substr($program['description'], 0, 120) . '...' : ($program['description'] ?? 'Unlock your potential with our immersive educational experience.')) ?>
            </p>
            <div class="d-flex align-items-center gap-2 pt-3 border-top mt-auto">
                <a href="<?= $whatsappShareUrl ?>" target="_blank" class="btn btn-outline-success btn-sm rounded" title="Share ke WhatsApp">
                    <i class="bi bi-share"></i>
                </a>
                <a href="<?= base_url('programs/' . $program['id']) ?>" class="btn btn-outline-dark btn-sm rounded flex-grow-1 fw-bold">
                    DETAILS
                </a>
                <a href="<?= base_url('apply/' . $program['id']) ?>" class="btn btn-dark-red btn-sm rounded flex-grow-1 fw-bold">
                    APPLY NOW
                </a>
            </div>
        </div>
    </div>
</div>
