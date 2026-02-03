<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="hero-section py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">About FEEC</h1>
        <p class="lead">Future English Education Center: Leading Innovation in Kampung Inggris Pare</p>
    </div>
</div>

<!-- Main Content -->
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card-custom card mb-4">
                <div class="card-header">
                    <h3 class="mb-0"><i class="bi bi-bullseye me-2"></i>Our Mission</h3>
                </div>
                <div class="card-body">
                    <p class="lead">To revolutionize English language learning in Indonesia by combining immersive environments, advanced technology, and personalized teaching methods that empower students for global success.</p>
                </div>
            </div>
            
            <div class="card-custom card mb-4">
                <div class="card-header">
                    <h3 class="mb-0"><i class="bi bi-clock-history me-2"></i>Our Journey</h3>
                </div>
                <div class="card-body">
                    <p>Founded in the heart of Kampung Inggris Pare, FEEC (Future English Education Center) began with a simple vision: to make high-quality English education accessible and effective. Over the years, we have evolved from a local tutoring group into a premier language center equipped with dedicated ERP management and digital learning tools.</p>
                    <p>Today, FEEC stands as a beacon of modern education in Kediri, serving thousands of students from across Indonesia who seek intensive, result-oriented English programs in an immersive 24-hour English area environment.</p>
                </div>
            </div>
            
            <div class="card-custom card">
                <div class="card-header">
                    <h3 class="mb-0"><i class="bi bi-heart me-2"></i>FEEC Core Values</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                    <i class="bi bi-lightning-charge"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Intensive Growth</h5>
                                    <p class="text-muted mb-0">We focus on rapid, measurable improvement through dedicated focus.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                    <i class="bi bi-stars"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Innovation</h5>
                                    <p class="text-muted mb-0">Blending traditional Kampung Inggris vibes with modern learning tech.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Community</h5>
                                    <p class="text-muted mb-0">Building a supportive family of learners and mentors.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                    <i class="bi bi-chat-quote"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Real Fluency</h5>
                                    <p class="text-muted mb-0">Moving beyond grammar to practical, confident communication.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card-custom card mb-4">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-info-circle me-2"></i>Quick Facts</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <strong style="color: var(--dark-red);">Location:</strong><br>
                            <span class="text-muted">Kampung Inggris Pare, Kediri</span>
                        </li>
                        <li class="mb-3">
                            <strong style="color: var(--dark-red);">Alumni:</strong><br>
                            <span class="text-muted">10,000+ Students</span>
                        </li>
                        <li class="mb-3">
                            <strong style="color: var(--dark-red);">Tutors:</strong><br>
                            <span class="text-muted">50+ Certified Experts</span>
                        </li>
                        <li class="mb-3">
                            <strong style="color: var(--dark-red);">Programs:</strong><br>
                            <span class="text-muted">20+ Intensive Tracks</span>
                        </li>
                        <li>
                            <strong style="color: var(--dark-red);">Focus:</strong><br>
                            <span class="text-muted">Speaking & Fluency</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card-custom card">
                <div class="card-body text-center p-4">
                    <h5 class="fw-bold mb-3">Join the FEEC Family</h5>
                    <p class="text-muted mb-3">Ready to transform your English skills in the most supportive environment?</p>
                    <a href="<?= base_url('apply') ?>" class="btn btn-dark-red w-100">
                        <i class="bi bi-pencil-square me-2"></i>Apply Today
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
