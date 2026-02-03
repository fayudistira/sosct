<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<style>
    .success-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    .success-header {
        background: linear-gradient(135deg, var(--dark-red) 0%, #8b0000 100%);
        padding: 40px 20px;
        color: white;
    }
    .feature-icon-success {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2.5rem;
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    .reg-number-box {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
    }
    .btn-whatsapp {
        background-color: #25D366;
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-whatsapp:hover {
        background-color: #128C7E;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3);
    }
    .next-steps-list {
        padding-left: 0;
        list-style: none;
    }
    .next-steps-list li {
        position: relative;
        padding-left: 35px;
        margin-bottom: 15px;
    }
    .next-steps-list li::before {
        content: "\F272";
        font-family: "bootstrap-icons";
        position: absolute;
        left: 0;
        top: 0;
        color: var(--dark-red);
        font-weight: bold;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card success-card">
                <div class="success-header text-center">
                    <div class="feature-icon-success">
                        <i class="bi bi-check2-all"></i>
                    </div>
                    <h1 class="fw-bold mb-2">Submission Successful!</h1>
                    <p class="mb-0 opacity-75">Thank you for choosing our institution for your education.</p>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <?php if (isset($registrationNumber)): ?>
                        <div class="text-center mb-4">
                            <p class="text-muted mb-2">Your Registration Number</p>
                            <div class="reg-number-box">
                                <h2 class="fw-bold mb-0 text-dark"><?= esc($registrationNumber) ?></h2>
                            </div>
                            <p class="small text-muted"><i class="bi bi-info-circle me-1"></i>Please take a screenshot or save this number for future reference.</p>
                        </div>
                    <?php endif ?>

                    <div class="row mb-5">
                        <div class="col-md-7">
                            <h5 class="fw-bold mb-4"><i class="bi bi-arrow-right-circle me-2 text-danger"></i>What's Next?</h5>
                            <ul class="next-steps-list">
                                <li>Our admissions team will review your application within 3-5 business days.</li>
                                <li>You will receive an email notification regarding your status.</li>
                                <li>If approved, we will send you enrollment instructions.</li>
                                <li>You can now confirm your application via WhatsApp.</li>
                            </ul>
                        </div>
                        <div class="col-md-5">
                            <div class="p-4 bg-light rounded-4 text-center h-100 d-flex flex-column justify-content-center">
                                <h6 class="fw-bold mb-3">Fast Confirmation</h6>
                                <p class="small text-muted mb-4">Click below to notify our admin immediately via WhatsApp.</p>
                                
                                <?php
                                $waNumber = '6289509778659';
                                $message = "Hello Admin, I have filled the application form.\n\n";
                                if (isset($admission)) {
                                    $message .= "Registration No: " . $admission['registration_number'] . "\n";
                                    $message .= "Name: " . $admission['full_name'] . "\n";
                                    $message .= "Program: " . $admission['program_title'] . "\n";
                                    $message .= "Phone: " . $admission['phone'] . "\n";
                                    $message .= "Email: " . $admission['email'] . "\n\n";
                                } else {
                                    $message .= "Registration No: " . ($registrationNumber ?? '-') . "\n\n";
                                }
                                $message .= "Please help me to process my application. Thank you!";
                                $waUrl = "https://wa.me/" . $waNumber . "?text=" . urlencode($message);
                                ?>
                                
                                <a href="<?= $waUrl ?>" target="_blank" class="btn btn-whatsapp btn-lg w-100 py-3 rounded-pill fw-bold">
                                    <i class="bi bi-whatsapp me-2"></i>Confirm via WA
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4 opacity-50">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="<?= base_url('/') ?>" class="btn btn-outline-dark btn-lg w-100 rounded-pill">
                                <i class="bi bi-house me-2"></i>Back to Home
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?= base_url('contact') ?>" class="btn btn-dark-red btn-lg w-100 rounded-pill shadow-sm">
                                <i class="bi bi-envelope me-2"></i>Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted small">
                    <i class="bi bi-shield-check me-1"></i>
                    Your data is safe with us. We follow strict privacy protocols.
                </p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
