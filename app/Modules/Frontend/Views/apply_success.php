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
                                // Get WhatsApp URL from session or use default
                                $waUrl = session('waUrl') ?? '';
                                
                                if (!$waUrl && isset($admission)) {
                                    $waNumber = '6282240781299';
                                    
                                    $message = "Halo Xihuan Mandarin Indonesia, saya ingin mendaftar kursus dengan data berikut:\n\n";
                                    $message .= "DATA PRIBADI\n";
                                    $message .= "Nama Lengkap: " . ($admission['full_name'] ?? '-') . "\n";
                                    $message .= "Nomor KTP: " . ($admission['citizen_id'] ?? '-') . "\n";
                                    $message .= "Jenis Kelamin: " . ($admission['gender'] ?? '-') . "\n";
                                    $message .= "Agama: " . ($admission['religion'] ?? '-') . "\n";
                                    
                                    $dob = $admission['date_of_birth'] ?? '-';
                                    if ($dob !== '-' && strpos($dob, 'T') !== false) {
                                        $dob = explode('T', $dob)[0];
                                    }
                                    $message .= "Tempat, Tanggal Lahir: " . ($admission['place_of_birth'] ?? '-') . ", " . $dob . "\n";
                                    $message .= "Alamat: " . ($admission['street_address'] ?? '-') . ", " . ($admission['district'] ?? '-') . ", " . ($admission['regency'] ?? '-') . ", " . ($admission['province'] ?? '-') . ", " . ($admission['postal_code'] ?? '-') . "\n";
                                    $message .= "No. Telp: " . ($admission['phone'] ?? '-') . "\n";
                                    $message .= "Email: " . ($admission['email'] ?? '-') . "\n\n";
                                    
                                    $message .= "KONTAK DARURAT\n";
                                    $message .= "Nama: " . ($admission['emergency_contact_name'] ?? '-') . " (" . ($admission['emergency_contact_phone'] ?? '-') . ") - " . ($admission['emergency_contact_relation'] ?? '-') . "\n\n";
                                    
                                    $message .= "DATA DAPODIK\n";
                                    $message .= "Ayah: " . ($admission['father_name'] ?? '-') . "\n";
                                    $message .= "Ibu: " . ($admission['mother_name'] ?? '-') . "\n\n";
                                    
                                    $message .= "PROGRAM KURSUS\n";
                                    $message .= "Program: " . ($admission['program_title'] ?? '-') . "\n";
                                    $message .= "Detail: " . ($admission['category'] ?? '-') . "\n";
                                    $message .= "Mulai Kursus: " . ($admission['start_date'] ?? '-') . "\n\n";
                                    
                                    $programFee = (int) ($admission['tuition_fee'] ?? 0);
                                    $registrationFee = 500000;
                                    $totalFee = $programFee + $registrationFee;
                                    
                                    $message .= "INFORMASI HARGA\n";
                                    $message .= "Biaya Program: Rp " . number_format($programFee, 0, ',', '.') . ",-\n";
                                    $message .= "Biaya Registrasi : Rp " . number_format($registrationFee, 0, ',', '.') . ",-\n";
                                    $message .= "Total : Rp " . number_format($totalFee, 0, ',', '.') . ",-\n\n";
                                    
                                    $message .= "CATATAN: Biaya registrasi Rp 500.000 dibayarkan setelah mengisi formulir ini.\n\n";
                                    $message .= "Terima kasih.";
                                    
                                    $waUrl = "https://wa.me/" . $waNumber . "?text=" . urlencode($message);
                                }
                                ?>

                                <a href="<?= $waUrl ?>" target="_blank" id="wa-confirm-btn" class="btn btn-whatsapp btn-lg w-100 py-3 rounded-pill fw-bold">
                                    <i class="bi bi-whatsapp me-2"></i>Confirm via WA
                                </a>

                                <!-- Auto-redirect countdown -->
                                <div id="wa-countdown" class="mt-3 small text-muted">
                                    <span id="countdown-text">Mengalihkan ke WhatsApp dalam 3 detik...</span>
                                </div>
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

<script>
// Auto-redirect ke WhatsApp setelah 3 detik
document.addEventListener('DOMContentLoaded', function() {
    const waBtn = document.getElementById('wa-confirm-btn');
    const countdownText = document.getElementById('countdown-text');
    
    // Get WhatsApp URL - regenerate from admission data if not in session
    let waUrl = '<?= session('waUrl') ?? '' ?>';
    
    // If no waUrl in session, regenerate from admission data
    if (!waUrl || waUrl === '') {
        const admissionData = <?= json_encode($admission ?? []) ?>;
        if (admissionData && admissionData.full_name) {
            let message = "Halo Xihuan Mandarin Indonesia, saya ingin mendaftar kursus dengan data berikut:\n\n";
            message += "DATA PRIBADI\n";
            message += "Nama Lengkap: " + (admissionData.full_name || '-') + "\n";
            message += "Nomor KTP: " + (admissionData.citizen_id || '-') + "\n";
            message += "Jenis Kelamin: " + (admissionData.gender || '-') + "\n";
            message += "Agama: " + (admissionData.religion || '-') + "\n";
            
            let dob = admissionData.date_of_birth || '-';
            if (dob !== '-' && dob.includes('T')) {
                dob = dob.split('T')[0];
            }
            message += "Tempat, Tanggal Lahir: " + (admissionData.place_of_birth || '-') + ", " + dob + "\n";
            message += "Alamat: " + (admissionData.street_address || '-') + ", " + (admissionData.district || '-') + ", " + (admissionData.regency || '-') + ", " + (admissionData.province || '-') + ", " + (admissionData.postal_code || '-') + "\n";
            message += "No. Telp: " + (admissionData.phone || '-') + "\n";
            message += "Email: " + (admissionData.email || '-') + "\n\n";
            
            message += "KONTAK DARURAT\n";
            message += "Nama: " + (admissionData.emergency_contact_name || '-') + " (" + (admissionData.emergency_contact_phone || '-') + ") - " + (admissionData.emergency_contact_relation || '-') + "\n\n";
            
            message += "DATA DAPODIK\n";
            message += "Ayah: " + (admissionData.father_name || '-') + "\n";
            message += "Ibu: " + (admissionData.mother_name || '-') + "\n\n";
            
            message += "PROGRAM KURSUS\n";
            message += "Program: " + (admissionData.program_title || '-') + "\n";
            message += "Detail: " + (admissionData.category || '-') + "\n";
            message += "Mulai Kursus: " + (admissionData.start_date || '-') + "\n\n";
            
            const programFee = Number(admissionData.tuition_fee) || 0;
            const registrationFee = 500000;
            const totalFee = programFee + registrationFee;
            
            message += "INFORMASI HARGA\n";
            message += "Biaya Program: Rp " + programFee.toLocaleString('id-ID') + ",-\n";
            message += "Biaya Registrasi : Rp " + registrationFee.toLocaleString('id-ID') + ",-\n";
            message += "Total : Rp " + totalFee.toLocaleString('id-ID') + ",-\n\n";
            
            message += "CATATAN: Biaya registrasi Rp 500.000 dibayarkan setelah mengisi formulir ini.\n\n";
            message += "Terima kasih.";
            
            waUrl = 'https://wa.me/6282240781299?text=' + encodeURIComponent(message);
            
            if (waBtn) {
                waBtn.href = waUrl;
            }
        }
    }
    
    // Auto-redirect after 3 seconds only on first load (not on page revisit)
    let hasAutoRedirected = sessionStorage.getItem('waAutoRedirected');
    
    if (waUrl && waUrl !== '' && !hasAutoRedirected) {
        if (waBtn && countdownText) {
            let secondsLeft = 3;
            
            const countdownInterval = setInterval(function() {
                secondsLeft--;
                if (secondsLeft > 0) {
                    countdownText.textContent = 'Mengalihkan ke WhatsApp dalam ' + secondsLeft + ' detik...';
                } else {
                    clearInterval(countdownInterval);
                    countdownText.textContent = 'Mengalihkan...';
                    sessionStorage.setItem('waAutoRedirected', 'true');
                    window.open(waUrl, '_blank');
                }
            }, 1000);
        }
    }
    
    // Handle manual button click
    if (waBtn) {
        waBtn.addEventListener('click', function() {
            sessionStorage.setItem('waAutoRedirected', 'true');
            if (countdownText) {
                countdownText.style.display = 'none';
            }
        });
    }
});
</script>
<?= $this->endSection() ?>