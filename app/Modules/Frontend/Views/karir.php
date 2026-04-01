<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Hero Showcase Header -->
<div class="hero-section position-relative overflow-hidden py-5 mb-5" style="background: linear-gradient(135deg, var(--dark-red) 0%, #600000 100%);">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 40%); pointer-events: none;"></div>
    <div class="container position-relative py-4 text-center">
        <div class="badge bg-white text-danger mb-3 p-2 px-3 rounded-pill shadow-sm animate-fade-in fw-bold">
            <i class="bi bi-briefcase-fill me-1"></i> JOIN OUR TEAM
        </div>
        <h1 class="display-3 fw-bold text-white mb-3 animate-slide-up">Karir & Lowongan <span class="text-white-50">Kerja</span></h1>
        <p class="lead text-white-50 mx-auto animate-slide-up-delay-1" style="max-width: 700px;">
            Bergabunglah dengan tim kami di Kampung Inggris Pare dan kembangkan kariermu dalam dunia pendidikan bahasa asing.
        </p>
    </div>
</div>

<div class="container mb-5">
    <!-- Introduction Section -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8 text-center animate-fade-in">
            <h2 class="fw-bold mb-4">Mengapa Bergabung Dengan Kami?</h2>
            <p class="text-muted lead">
                SOSCT (School of Social Communication and Training) adalah institusi pendidikan bahasa asing terkemuka di Kampung Inggris Pare. 
                Kami mencari individu yang passionate dalam dunia pendidikan dan ingin berkontribusi dalam membantu siswa mencapai kemampuan bahasa asing yang mereka impikan.
            </p>
        </div>
    </div>

    <!-- Job Vacancies -->
    <div class="row g-4">
        <?php foreach ($jobVacancies as $index => $job): ?>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100 hover-lift animate-slide-up" style="animation-delay: <?= $index * 0.1 ?>s;">
                <div class="card-body p-4">
                    <!-- Job Type Badge -->
                    <div class="mb-3">
                        <span class="badge rounded-pill" style="background: var(--dark-red);">
                            <i class="bi bi-clock me-1"></i><?= $job['type_label'] ?>
                        </span>
                    </div>
                    
                    <!-- Job Title -->
                    <h3 class="fw-bold mb-3" style="color: var(--dark-red);">
                        <?= $job['title'] ?>
                    </h3>
                    
                    <!-- Description -->
                    <p class="text-muted mb-4">
                        <?= $job['description'] ?>
                    </p>
                    
                    <!-- Requirements -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-check-circle-fill me-2" style="color: var(--dark-red);"></i>Persyaratan
                        </h6>
                        <ul class="list-unstyled">
                            <?php foreach ($job['requirements'] as $req): ?>
                            <li class="mb-2 d-flex align-items-start">
                                <i class="bi bi-check2 me-2 mt-1 text-muted small"></i>
                                <span class="text-muted small"><?= $req ?></span>
                            </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                    
                    <!-- Benefits -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-gift-fill me-2" style="color: var(--dark-red);"></i>Keuntungan
                        </h6>
                        <ul class="list-unstyled">
                            <?php foreach ($job['benefits'] as $benefit): ?>
                            <li class="mb-2 d-flex align-items-start">
                                <i class="bi bi-plus-circle me-2 mt-1 text-muted small"></i>
                                <span class="text-muted small"><?= $benefit ?></span>
                            </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                    
                    <!-- Apply Button -->
                    <a href="<?= base_url('contact') ?>" class="btn btn-danger w-100 py-2 fw-bold">
                        <i class="bi bi-send-fill me-2"></i>Lamar Sekarang
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    </div>

    <!-- CTA Section -->
    <div class="row justify-content-center mt-5 pt-5">
        <div class="col-lg-8 text-center animate-fade-in">
            <div class="card border-0 shadow-lg p-4 p-lg-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                <h3 class="fw-bold mb-3">Tidak menemukan posisi yang sesuai?</h3>
                <p class="text-muted mb-4">
                    Kami selalu mencari talenta-talenta baru yang berpotensi. Kirimkan CV-mu ke email kami dan kami akan menghubungi jika ada posisi yang sesuai.
                </p>
                <a href="mailto:fayudistiraasnan@gmail.com" class="btn btn-danger btn-lg px-5 fw-bold">
                    <i class="bi bi-envelope me-2"></i>Kirim CV via Email
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
}
.animate-fade-in {
    animation: fadeIn 0.6s ease-out forwards;
}
.animate-slide-up {
    animation: slideUp 0.6s ease-out forwards;
    opacity: 0;
}
.animate-slide-up-delay-1 {
    animation: slideUp 0.6s ease-out 0.2s forwards;
    opacity: 0;
}
.animate-slide-up-delay-2 {
    animation: slideUp 0.6s ease-out 0.4s forwards;
    opacity: 0;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
<?= $this->endSection() ?>
