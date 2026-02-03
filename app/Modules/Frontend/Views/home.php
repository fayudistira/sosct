<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">Master English at FEEC Kampung Inggris</h1>
                <p class="lead mb-4">The most advanced English learning system in Pare. Experience immersive learning with professional tutors and modern methods tailored for your success.</p>
                <div class="d-flex gap-3">
                    <a href="<?= base_url('apply') ?>" class="btn btn-light btn-lg">
                        <i class="bi bi-pencil-square me-2"></i>Registration
                    </a>
                    <a href="<?= base_url('programs') ?>" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-grid me-2"></i>Our Programs
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?= base_url('assets/images/campus_hero.png') ?>" alt="FEEC Campus" class="img-fluid rounded shadow-lg img-landscape">
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: var(--dark-red);">Why FEEC?</h2>
        <p class="text-muted">Empowering your future with superior English skills in Kampung Inggris Pare</p>
    </div>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card-custom card">
                <div class="card-body text-center p-4">
                    <div class="feature-icon">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Immersive Speaking</h4>
                    <p class="text-muted">Our core method focus on active speaking environment, ensuring you gain confidence to speak naturally from day one.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom card">
                <div class="card-body text-center p-4">
                    <div class="feature-icon">
                        <i class="bi bi-cpu"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Tech-Driven Learning</h4>
                    <p class="text-muted">Utilizing the latest educational ERP and digital resources to track your progress and personalize your learning journey.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom card">
                <div class="card-body text-center p-4">
                    <div class="feature-icon">
                        <i class="bi bi-cup-hot"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Full Camp Facility</h4>
                    <p class="text-muted">Conducive 24-hour English area dormitory with comfortable facilities to keep you focused on your intensive goals.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="py-5" style="background-color: var(--light-red);">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4 mb-md-0">
                <h2 class="fw-bold" style="color: var(--dark-red);">10,000+</h2>
                <p class="text-muted">Successful Alumni</p>
            </div>
            <div class="col-md-3 mb-4 mb-md-0">
                <h2 class="fw-bold" style="color: var(--dark-red);">50+</h2>
                <p class="text-muted">Professional Tutors</p>
            </div>
            <div class="col-md-3 mb-4 mb-md-0">
                <h2 class="fw-bold" style="color: var(--dark-red);">20+</h2>
                <p class="text-muted">Intensive Programs</p>
            </div>
            <div class="col-md-3">
                <h2 class="fw-bold" style="color: var(--dark-red);">Pare</h2>
                <p class="text-muted">Strategic Location</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="container py-5">
    <div class="card-custom card">
        <div class="card-body text-center p-5">
            <h2 class="fw-bold mb-3" style="color: var(--dark-red);">Your Future Starts at FEEC</h2>
            <p class="lead text-muted mb-4">Don't wait for your dreams. Join the most advanced language center in Kampung Inggris Pare today.</p>
            <a href="<?= base_url('apply') ?>" class="btn btn-dark-red btn-lg">
                <i class="bi bi-pencil-square me-2"></i>Book Your Seat Now
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
