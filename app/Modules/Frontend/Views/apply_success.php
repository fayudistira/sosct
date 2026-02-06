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

    /* Invoice Specific Styling */
    .invoice-section {
        border: 2px solid #f0f0f0;
        border-radius: 15px;
        padding: 30px;
        background: #fff;
    }

    .invoice-info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.95rem;
    }

    .invoice-label {
        color: #666;
        font-weight: 500;
    }

    .invoice-value {
        color: #333;
        font-weight: 700;
        text-align: right;
    }

    .invoice-divider {
        height: 1px;
        background: #eee;
        margin: 15px 0;
    }

    .total-row {
        background: var(--light-red);
        padding: 15px;
        border-radius: 10px;
        margin-top: 15px;
    }

    @media print {

        .navbar,
        .footer,
        .no-print,
        .btn-whatsapp,
        .row.justify-content-center>.col-lg-8>.text-center {
            display: none !important;
        }

        .container {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .success-card {
            box-shadow: none !important;
            border: none !important;
        }

        .success-header {
            background: #fff !important;
            color: #000 !important;
            padding: 20px 0 !important;
            border-bottom: 2px solid var(--dark-red);
        }

        .feature-icon-success {
            border-color: var(--dark-red) !important;
            color: var(--dark-red) !important;
        }
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card success-card">
                <div class="success-header text-center">
                    <div class="feature-icon-success no-print">
                        <i class="bi bi-check2-all"></i>
                    </div>
                    <h1 class="fw-bold mb-2">Registration Receipt</h1>
                    <p class="mb-0 opacity-75 no-print">Submission Successful! Thank you for choosing our institution.</p>
                    <p class="d-none d-print-block">Official Preliminary Invoice / Application Summary</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h5 class="fw-bold mb-3 text-danger"><i class="bi bi-person-badge me-2"></i>Applicant Details</h5>
                            <p class="mb-1"><strong>Name:</strong> <?= esc($admission['full_name'] ?? 'N/A') ?></p>
                            <p class="mb-1"><strong>Program:</strong> <?= esc($admission['program_title'] ?? 'N/A') ?></p>
                            <p class="mb-1"><strong>Email:</strong> <?= esc($admission['email'] ?? 'N/A') ?></p>
                            <p class="mb-1"><strong>Phone:</strong> <?= esc($admission['phone'] ?? 'N/A') ?></p>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <h5 class="fw-bold mb-3 text-danger"><i class="bi bi-calendar-event me-2"></i>Application Info</h5>
                            <p class="mb-1"><strong>Reg No:</strong> <span class="text-danger"><?= esc($registrationNumber) ?></span></p>
                            <p class="mb-1"><strong>Date:</strong> <?= date('F d, Y') ?></p>
                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-warning text-dark">Pending Review</span></p>
                        </div>
                    </div>

                    <?php if (!empty($invoices)): ?>
                        <div class="invoice-section mb-5">
                            <h5 class="fw-bold mb-4"><i class="bi bi-receipt-cutoff me-2"></i>Fee Breakdown</h5>

                            <?php
                            $total = 0;
                            foreach ($invoices as $inv):
                                $total += $inv['amount'];
                            ?>
                                <div class="invoice-info-row d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="invoice-label"><?= esc($inv['description']) ?> (<?= esc($inv['invoice_number']) ?>)</span>
                                        <a href="<?= base_url('invoice/public/' . $inv['id']) ?>" target="_blank" class="text-danger ms-2 no-print">
                                            <i class="bi bi-eye me-1"></i><small>View Invoice</small>
                                        </a>
                                    </div>
                                    <span class="invoice-value">Rp <?= number_format($inv['amount'], 0, ',', '.') ?></span>
                                </div>
                            <?php endforeach; ?>

                            <div class="total-row d-flex justify-content-between align-items-center">
                                <span class="fw-bold h5 mb-0">Total Payable Amount</span>
                                <span class="fw-bold h4 mb-0 text-danger">Rp <?= number_format($total, 0, ',', '.') ?></span>
                            </div>

                            <div class="mt-4 p-3 bg-light rounded-3 small text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                <strong>Note:</strong> Please complete the payment within 3 business days to secure your enrollment. Retain this document for your records.
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-4 no-print">
                        <div class="col-md-6 order-2 order-md-1 mt-4 mt-md-0">
                            <h5 class="fw-bold mb-3"><i class="bi bi-gear-wide-connected me-2 text-danger"></i>Next Steps</h5>
                            <ul class="next-steps-list small">
                                <li><strong>Download</strong> your invoice/receipt for reference.</li>
                                <li><strong>Confirm</strong> your registration via WhatsApp button.</li>
                                <li>Our team will <strong>Validate</strong> your documents within 3-5 days.</li>
                                <li>You will receive <strong>Email Instructions</strong> for payment and enrollment.</li>
                            </ul>

                            <div class="mt-4">
                                <button onclick="window.print()" class="btn btn-dark-red btn-lg w-100 rounded-pill shadow-sm">
                                    <i class="bi bi-printer me-2"></i>Download / Print Invoice
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 order-1 order-md-2">
                            <div class="p-4 bg-light rounded-4 text-center h-100 d-flex flex-column justify-content-center border border-success border-2">
                                <h6 class="fw-bold mb-2 text-success"><i class="bi bi-whatsapp me-1"></i>Admin Confirmation</h6>
                                <p class="small text-muted mb-4">Click below to notify our admission team via WhatsApp.</p>

                                <?php
                                $waNumber = '6289509778659';
                                $message = "Hello Admin, I have filled the application form.\n\n";

                                if (isset($admission)) {
                                    $message .= "Registration No: " . $admission['registration_number'] . "\n";
                                    $message .= "Name: " . $admission['full_name'] . "\n";
                                    $message .= "Program: " . $admission['program_title'] . "\n";

                                    if (!empty($invoices)) {
                                        $totalFee = 0;
                                        foreach ($invoices as $inv) {
                                            $totalFee += $inv['amount'];
                                        }
                                        $message .= "Total Fees: Rp " . number_format($totalFee, 0, ',', '.') . "\n";
                                    }

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

                    <hr class="my-4 opacity-50 no-print">

                    <div class="row g-3 no-print">
                        <div class="col-md-6">
                            <a href="<?= base_url('/') ?>" class="btn btn-outline-dark btn-lg w-100 rounded-pill">
                                <i class="bi bi-house me-2"></i>Back to Home
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?= base_url('contact') ?>" class="btn btn-outline-danger btn-lg w-100 rounded-pill">
                                <i class="bi bi-envelope me-2"></i>Contact Us
                            </a>
                        </div>
                    </div>

                    <!-- Print Only Footer -->
                    <div class="d-none d-print-block mt-5 text-center pt-5 border-top">
                        <p class="text-muted small">Generated on <?= date('Y-m-d H:i:s') ?> | ERP.v1.0 Institutional System</p>
                        <p class="fw-bold">Thank you for your application!</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4 no-print">
                <p class="text-muted small">
                    <i class="bi bi-shield-check me-1"></i>
                    Your data is safe with us. We follow strict privacy protocols.
                </p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>