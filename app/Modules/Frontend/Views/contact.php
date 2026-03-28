<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Hero Showcase Header -->
<div class="hero-section position-relative overflow-hidden py-5 mb-5" style="background: linear-gradient(135deg, var(--dark-red) 0%, #600000 100%);">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 40%); pointer-events: none;"></div>
    <div class="container position-relative py-4 text-center">
        <div class="badge bg-white text-danger mb-3 p-2 px-3 rounded-pill shadow-sm animate-fade-in fw-bold">
            <i class="bi bi-chat-dots-fill me-1"></i> WE ARE HERE FOR YOU
        </div>
        <h1 class="display-3 fw-bold text-white mb-3 animate-slide-up">Get in <span class="text-white-50">Touch</span></h1>
        <p class="lead text-white-50 mx-auto animate-slide-up-delay-1" style="max-width: 700px;">
            Have questions about our programs or the ERP system? Our team is ready to assist you.
        </p>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">
        <!-- Contact Information Cards -->
        <div class="col-lg-4">
            <div class="row g-4">
                <div class="col-12 animate-slide-up">
                    <div class="card border-0 shadow-sm p-4 h-100 hover-lift text-center">
                        <div class="feature-icon mb-4 mx-auto" style="width: 60px; height: 60px; font-size: 1.5rem; background: var(--light-red); color: var(--dark-red);">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <h5 class="fw-bold">Phone & WhatsApp</h5>
                        <p class="text-muted small">Available for quick calls or chat via WhatsApp</p>
                        <a href="https://wa.me/6285810310950" class="h5 fw-bold text-decoration-none" style="color: var(--dark-red);">
                            +62 895 0977 8659
                        </a>
                    </div>
                </div>
                
                <div class="col-12 animate-slide-up-delay-1">
                    <div class="card border-0 shadow-sm p-4 h-100 hover-lift text-center">
                        <div class="feature-icon mb-4 mx-auto" style="width: 60px; height: 60px; font-size: 1.5rem; background: var(--light-red); color: var(--dark-red);">
                            <i class="bi bi-envelope-paper-fill"></i>
                        </div>
                        <h5 class="fw-bold">Email Support</h5>
                        <p class="text-muted small">The best way for detailed inquiries or technical support</p>
                        <a href="mailto:fayudistiraasnan@gmail.com" class="h6 fw-bold text-decoration-none email-link">
                            fayudistiraasnan@gmail.com
                        </a>
                    </div>
                </div>

                <div class="col-12 animate-slide-up-delay-2">
                    <div class="card border-0 shadow-sm p-4 h-100 hover-lift text-center">
                        <div class="feature-icon mb-4 mx-auto" style="width: 60px; height: 60px; font-size: 1.5rem; background: var(--light-red); color: var(--dark-red);">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <h5 class="fw-bold">Main Headquarters</h5>
                        <p class="text-muted small mb-0">Visit us in the heart of Kampung Inggris</p>
                        <address class="fw-bold small mt-2 mb-0">
                            Jl. Dahlia No. 123, Tulungrejo<br>
                            Pare, Kediri 64212, Jawa Timur
                        </address>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg overflow-hidden animate-fade-in">
                <div class="row g-0">
                    <div class="col-md-1 d-none d-md-block" style="background: var(--dark-red);"></div>
                    <div class="col-md-11 p-4 p-md-5">
                        <h2 class="fw-bold mb-4">Send a <span class="text-danger">Message</span></h2>
                        
                        <?php if (session('success')): ?>
                            <div class="alert alert-success border-0 shadow-sm mb-4 animate-fade-in">
                                <i class="bi bi-check-circle-fill me-2"></i><?= session('success') ?>
                            </div>
                        <?php endif ?>

                        <form action="<?= base_url('contact/submit') ?>" method="post" id="contactForm">
                            <?= csrf_field() ?>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
                                        <label for="name">Your Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                        <label for="email">Email Address</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="subject" name="subject" placeholder="General Inquiry" required>
                                        <label for="subject">Subject</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-4">
                                        <textarea class="form-control" placeholder="Leave a message here" id="message" name="message" style="height: 150px" required></textarea>
                                        <label for="message">Your Message</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-dark-red btn-lg px-5 py-3 rounded-pill fw-bold shadow">
                                        SEND MESSAGE <i class="bi bi-arrow-right-short ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Support Hours Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm p-5 text-center bg-white">
                    <h3 class="fw-bold mb-4">Support <span class="text-danger">Availability</span></h3>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <h6 class="fw-bold text-uppercase mb-2" style="letter-spacing: 1px;">Weekdays</h6>
                            <p class="text-muted mb-0">Monday - Friday<br><span class="fw-bold text-dark">07:00 - 21:00</span></p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold text-uppercase mb-2" style="letter-spacing: 1px;">Saturdays</h6>
                            <p class="text-muted mb-0">Saturday<br><span class="fw-bold text-dark">08:00 - 17:00</span></p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold text-uppercase mb-2" style="letter-spacing: 1px;">Sundays</h6>
                            <p class="text-muted mb-0">Closed<br><span class="text-danger small fw-bold">SELF-STUDY DAY</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .email-link {
        color: var(--dark-red);
        word-break: break-all;
    }
    
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
    }

    .form-control {
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        padding: 1rem;
    }

    .form-control:focus {
        border-color: var(--dark-red);
        box-shadow: 0 0 0 0.25rem rgba(139, 0, 0, 0.1);
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
    
    .animate-slide-up { animation: fadeInUp 0.8s ease-out forwards; }
    .animate-slide-up-delay-1 { animation: fadeInUp 0.8s ease-out 0.2s forwards; opacity: 0; }
    .animate-slide-up-delay-2 { animation: fadeInUp 0.8s ease-out 0.4s forwards; opacity: 0; }
    .animate-fade-in { animation: fadeIn 1s ease-out forwards; }
</style>

<?= $this->endSection() ?>
