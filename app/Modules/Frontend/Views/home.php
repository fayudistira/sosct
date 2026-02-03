<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Hero Showcase Section -->
<div class="hero-section position-relative overflow-hidden py-5 d-flex align-items-center" style="min-height: 80vh;">
    <!-- Abstract background elements -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 10% 20%, rgba(139, 0, 0, 0.1) 0%, transparent 40%); pointer-events: none;"></div>
    <div class="position-absolute bottom-0 end-0 w-100 h-100" style="background: radial-gradient(circle at 90% 80%, rgba(139, 0, 0, 0.1) 0%, transparent 40%); pointer-events: none;"></div>
    
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="badge bg-white text-dark mb-3 p-2 px-3 rounded-pill shadow-sm animate-fade-in">
                    <span class="text-danger fw-bold">NEW</span> ERP V 1.1 PRO IS HERE
                </div>
                <h1 class="display-3 fw-bold mb-4 animate-slide-up">
                    Revolutionize Your <span class="text-white">Education Center</span> Center Management
                </h1>
                <p class="lead mb-4 text-white-50 animate-slide-up-delay-1" style="font-size: 1.25rem;">
                    A powerful, modular HMVC system designed to handle everything from recruitment and smart payments to academic tracking. Built for speed, security, and scalability.
                </p>
                <div class="d-flex flex-wrap gap-3 animate-slide-up-delay-2">
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-light btn-lg px-4 shadow">
                        <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                    </a>
                    <a href="<?= base_url('about') ?>" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-book me-2"></i>Read Docs
                    </a>
                </div>
                
                <div class="mt-5 d-flex align-items-center gap-4 animate-fade-in-delay-3">
                    <div class="d-flex flex-column">
                        <span class="h4 fw-bold mb-0 text-white">7+</span>
                        <span class="small text-white-50">Active Modules</span>
                    </div>
                    <div class="vr bg-white opacity-25" style="height: 40px;"></div>
                    <div class="d-flex flex-column">
                        <span class="h4 fw-bold mb-0 text-white">99.9%</span>
                        <span class="small text-white-50">System Uptime</span>
                    </div>
                    <div class="vr bg-white opacity-25" style="height: 40px;"></div>
                    <div class="d-flex flex-column">
                        <span class="h4 fw-bold mb-0 text-white">CI 4.x</span>
                        <span class="small text-white-50">Legacy Ready</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 text-center animate-pulse">
                <div class="position-relative">
                    <div class="position-absolute top-50 start-50 translate-middle w-100 h-100 bg-white opacity-10 rounded-circle blur-3xl shadow-lg" style="filter: blur(80px);"></div>
                    <i class="bi bi-shield-lock-fill display-1" style="font-size: 12rem; color: rgba(255,255,255,0.2);"></i>
                    <div class="position-absolute bottom-0 start-50 translate-middle-x bg-white text-dark p-3 rounded-4 shadow-lg border border-light" style="width: 280px;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="feature-icon m-0" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div class="text-start">
                                <div class="fw-bold small">Encrypted & Secure</div>
                                <div class="text-muted" style="font-size: 0.7rem;">Automated Backup System Active</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modules Grid Showcase -->
<div class="container py-5 mt-n5 position-relative" style="z-index: 5;">
    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="card-custom border-0 shadow-lg p-3 hover-lift">
                <div class="card-body">
                    <div class="feature-icon mb-4" style="background: linear-gradient(135deg, #FF6B6B 0%, #EE5253 100%);">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <h4 class="fw-bold">Smart Admission</h4>
                    <p class="text-muted small">Multi-step recruitment pipeline with automated status updates and JSON-based dynamic form data handling.</p>
                    <hr class="my-3 opacity-10">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-light text-dark small">v1.2 Stable</span>
                        <a href="<?= base_url('apply') ?>" class="text-danger text-decoration-none small fw-bold">Explore <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom border-0 shadow-lg p-3 hover-lift">
                <div class="card-body">
                    <div class="feature-icon mb-4" style="background: linear-gradient(135deg, #1dd1a1 0%, #10ac84 100%);">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <h4 class="fw-bold">Finance Control</h4>
                    <p class="text-muted small">AJAX-powered payment engine. Instant student searches, dynamic invoice population, and real-time ledger updates.</p>
                    <hr class="my-3 opacity-10">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-light text-dark small">Integrated</span>
                        <a href="#" class="text-success text-decoration-none small fw-bold">View Ledger <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom border-0 shadow-lg p-3 hover-lift">
                <div class="card-body">
                    <div class="feature-icon mb-4" style="background: linear-gradient(135deg, #54a0ff 0%, #2e86de 100%);">
                        <i class="bi bi-layers-half"></i>
                    </div>
                    <h4 class="fw-bold">HMVC Core</h4>
                    <p class="text-muted small">The system is horizontally scalable. Add new departments or features as isolated modules without code collision.</p>
                    <hr class="my-3 opacity-10">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-light text-dark small">Scalable</span>
                        <a href="<?= base_url('about#command') ?>" class="text-primary text-decoration-none small fw-bold">CLI Tools <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Showcase Section -->
<div class="container py-5">
    <div class="row align-items-center g-5 py-5">
        <div class="col-lg-6">
            <h2 class="display-5 fw-bold mb-4" style="color: var(--dark-red);">Engineered for Efficiency</h2>
            <p class="lead text-muted mb-4">The ERP V 1.1 isn't just a management tool; it's a productivity engine designed to eliminate administrative bottlenecks.</p>
            
            <div class="d-flex gap-4 mb-4">
                <div class="bg-light p-3 rounded-4">
                    <i class="bi bi-lightning-fill text-warning fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold">High Performance</h5>
                    <p class="text-muted small mb-0">Sub-second page loads using optimized CodeIgniter query builders and caching mechanisms.</p>
                </div>
            </div>

            <div class="d-flex gap-4 mb-4">
                <div class="bg-light p-3 rounded-4">
                    <i class="bi bi-palette-fill text-info fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold">Modern Interface</h5>
                    <p class="text-muted small mb-0">Clean, Bootstrap 5.3 based UI with a custom design system that prioritizes user focus.</p>
                </div>
            </div>
            
            <a href="<?= base_url('programs') ?>" class="btn btn-dark-red btn-lg mt-3 px-5">Get Started</a>
        </div>
        <div class="col-lg-6">
            <div class="bg-light rounded-5 p-5 border border-white shadow-sm overflow-hidden position-relative">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-white p-4 rounded-4 shadow-sm text-center">
                            <div class="h2 fw-bold text-danger">90%</div>
                            <div class="text-muted small">Less Paperwork</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white p-4 rounded-4 shadow-sm text-center">
                            <div class="h2 fw-bold text-success">2x</div>
                            <div class="text-muted small">Faster Processing</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-white p-4 rounded-4 shadow-sm">
                            <div class="fw-bold mb-3 d-flex justify-content-between">
                                <span>Module Stability</span>
                                <span class="text-success">Perfect</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success w-100" role="progressbar"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Decorative Circle -->
                <div class="position-absolute top-100 start-100 translate-middle bg-danger opacity-10 rounded-circle" style="width: 200px; height: 200px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Tech Stack Ribbon -->
<div class="py-4 border-top border-bottom bg-light">
    <div class="container overflow-hidden text-center">
        <p class="text-muted small fw-bold text-uppercase mb-4">Built with Modern Technology</p>
        <div class="d-flex justify-content-center align-items-center gap-5 flex-wrap opacity-50 grayscale">
            <span class="h4 fw-bold">CODEIGNITER 4</span>
            <span class="h4 fw-bold">BOOTSTRAP 5.3</span>
            <span class="h4 fw-bold">MARIADB</span>
            <span class="h4 fw-bold">JQUERY AJAX</span>
            <span class="h4 fw-bold">HMVC ARCH</span>
        </div>
    </div>
</div>

<style>
    .hero-section {
        background: linear-gradient(135deg, var(--dark-red) 0%, #500000 100%);
        color: white;
    }
    
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    
    .grayscale {
        filter: grayscale(1);
        transition: filter 0.3s;
    }
    
    .grayscale:hover {
        filter: grayscale(0);
    }
    
    /* Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes pulse-soft {
        0% { transform: scale(1); opacity: 0.2; }
        50% { transform: scale(1.05); opacity: 0.3; }
        100% { transform: scale(1); opacity: 0.2; }
    }
    
    .animate-slide-up { animation: fadeInUp 0.8s ease out forwards; }
    .animate-slide-up-delay-1 { animation: fadeInUp 0.8s ease-out 0.2s forwards; opacity: 0; }
    .animate-slide-up-delay-2 { animation: fadeInUp 0.8s ease-out 0.4s forwards; opacity: 0; }
    .animate-fade-in { animation: fadeIn 1s ease-out forwards; }
    .animate-fade-in-delay-3 { animation: fadeIn 1s ease-out 0.6s forwards; opacity: 0; }
    
    .animate-pulse {
        animation: pulse-soft 3s infinite ease-in-out;
    }
    
    .mt-n5 {
        margin-top: -3rem !important;
    }
    
    .rounded-5 { border-radius: 2rem !important; }
    .rounded-4 { border-radius: 1.5rem !important; }
</style>

<?= $this->endSection() ?>
