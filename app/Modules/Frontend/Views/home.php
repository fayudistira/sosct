<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">Welcome to Our Institution</h1>
                <p class="lead mb-4">Start your journey with us today. Apply for admission and join our community of learners committed to excellence.</p>
                <a href="<?= base_url('apply') ?>" class="btn btn-light btn-lg me-2">
                    <i class="bi bi-pencil-square me-2"></i>Apply for Admission
                </a>
                <a href="<?= base_url('about') ?>" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-info-circle me-2"></i>Learn More
                </a>
            </div>
            <div class="col-lg-6">
                <img src="https://www.vecteezy.com/photo/50077160-group-of-students-young-university-students-consulting-doing-homework-cooperating-research-plan-discuss-concepts-and-strategies-prepare-presentations-on-laptops-and-tablet-computers" alt="Campus" class="img-fluid rounded shadow-lg">
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: var(--dark-red);">Why Choose Us</h2>
        <p class="text-muted">Discover what makes our institution stand out</p>
    </div>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card-custom card">
                <div class="card-body text-center p-4">
                    <div class="feature-icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Quality Education</h4>
                    <p class="text-muted">We provide world-class education with experienced faculty and modern facilities to ensure your success.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom card">
                <div class="card-body text-center p-4">
                    <div class="feature-icon">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Career Support</h4>
                    <p class="text-muted">Our career services help students achieve their professional goals with guidance and opportunities.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom card">
                <div class="card-body text-center p-4">
                    <div class="feature-icon">
                        <i class="bi bi-globe"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Global Network</h4>
                    <p class="text-muted">Join our alumni network spanning across the globe with connections that last a lifetime.</p>
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
                <h2 class="fw-bold" style="color: var(--dark-red);">5,000+</h2>
                <p class="text-muted">Students</p>
            </div>
            <div class="col-md-3 mb-4 mb-md-0">
                <h2 class="fw-bold" style="color: var(--dark-red);">300+</h2>
                <p class="text-muted">Faculty Members</p>
            </div>
            <div class="col-md-3 mb-4 mb-md-0">
                <h2 class="fw-bold" style="color: var(--dark-red);">50+</h2>
                <p class="text-muted">Programs</p>
            </div>
            <div class="col-md-3">
                <h2 class="fw-bold" style="color: var(--dark-red);">20+</h2>
                <p class="text-muted">Years of Excellence</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="container py-5">
    <div class="card-custom card">
        <div class="card-body text-center p-5">
            <h2 class="fw-bold mb-3" style="color: var(--dark-red);">Ready to Start Your Journey?</h2>
            <p class="lead text-muted mb-4">Join thousands of students who have transformed their lives through education.</p>
            <a href="<?= base_url('apply') ?>" class="btn btn-dark-red btn-lg">
                <i class="bi bi-pencil-square me-2"></i>Apply Now
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
