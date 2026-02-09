<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- QRCode.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        :root {
            --dark-red: #8B0000;
            --medium-red: #6B0000;
            --light-red: #FFE5E5;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .invoice-container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .invoice-header {
            background: linear-gradient(135deg, var(--dark-red) 0%, var(--medium-red) 100%);
            color: white;
            padding: 30px;
        }

        .invoice-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 600;
        }

        .invoice-number {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: 5px;
        }

        .invoice-body {
            padding: 30px;
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-section h5 {
            color: var(--dark-red);
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-red);
        }

        .info-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #555;
            min-width: 180px;
        }

        .info-value {
            color: #333;
            flex: 1;
        }

        .amount-box {
            background: linear-gradient(135deg, var(--light-red) 0%, #fff 100%);
            border: 2px solid var(--dark-red);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }

        .amount-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }

        .amount-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-red);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-unpaid {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-expired {
            background-color: #dc3545;
            color: #ffffff;
        }

        .status-partially-paid {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .payments-table {
            margin-top: 20px;
        }

        .payments-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .payments-table th {
            background-color: var(--dark-red);
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        .payments-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        .payments-table tr:last-child td {
            border-bottom: none;
        }

        .footer-note {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 0.9rem;
        }

        .btn-whatsapp {
            background-color: #25D366;
            color: white;
            border: none;
            transition: all 0.3s ease;
            padding: 12px 24px;
            border-radius: 30px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-weight: 600;
        }

        .btn-whatsapp:hover {
            background-color: #128C7E;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3);
        }

        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }

            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .invoice-container {
                box-shadow: none !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                border: none !important;
            }

            .invoice-body {
                padding: 10mm !important;
                font-size: 11px !important;
                line-height: 1.2 !important;
            }

            .info-section {
                margin-bottom: 12px !important;
                page-break-inside: avoid !important;
            }

            .info-section h5 {
                font-size: 12px !important;
                margin-bottom: 8px !important;
                padding-bottom: 5px !important;
            }

            .info-row {
                padding: 3px 0 !important;
            }

            .info-label {
                min-width: 120px !important;
            }

            .no-print {
                display: none !important;
            }

            .amount-box {
                background: #f8f8f8 !important;
                border: 2px solid var(--dark-red) !important;
                margin: 10px 0 !important;
                padding: 12px !important;
            }

            .amount-label {
                font-size: 10px !important;
            }

            .amount-value {
                font-size: 1.8rem !important;
            }

            .invoice-header {
                padding: 10px 30px !important;
            }

            .invoice-header h1 {
                font-size: 1.5rem !important;
            }

            /* Two Column Print Layout */
            .print-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }

            /* Force QR code to be smaller if needed to fit */
            #qrcode img {
                width: 100px !important;
                height: 100px !important;
            }

            .footer-note {
                margin-top: 15px !important;
                padding: 10px !important;
                font-size: 10px !important;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1><i class="bi bi-receipt"></i> INVOICE</h1>
                    <div class="invoice-number"><?= esc((string)($invoice['invoice_number'] ?? 'N/A')) ?></div>
                </div>
                <div class="text-end no-print">
                    <a href="<?= base_url('/') ?>" class="btn btn-outline-light me-2">
                        <i class="bi bi-house"></i> Home
                    </a>
                    <button onclick="window.print()" class="btn btn-light">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="invoice-body">
            <?php if (session()->has('success')): ?>
                <!-- ... Session Alert Content Hidden for brevity ... -->
                <div class="alert alert-success border-0 shadow-sm mb-4 no-print" style="border-left: 5px solid #198754 !important;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                        <div>
                            <h6 class="alert-heading fw-bold mb-1">Application Submitted!</h6>
                            <p class="mb-0 small"><?= session('success') ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-light p-4 rounded-3 text-center mb-5 no-print border border-success border-2">
                    <h6 class="fw-bold mb-2">Registration Confirmation</h6>
                    <p class="small text-muted mb-3">Please click the button below to notify our admin via WhatsApp for fast processing.</p>

                    <?php
                    $waNumber = '6289509778659';
                    $message = "Hello Admin, I have filled the application form.\n\n";
                    $message .= "Registration No: " . ($invoice['registration_number'] ?? 'N/A') . "\n";
                    $message .= "Name: " . ($invoice['student']['full_name'] ?? 'N/A') . "\n";
                    $message .= "Program: " . ($invoice['student']['program_title'] ?? 'N/A') . "\n";
                    $message .= "Total Fees: Rp " . number_format((float)($invoice['amount'] ?? 0), 0, ',', '.') . "\n";
                    $message .= "Phone: " . ($invoice['student']['phone'] ?? 'N/A') . "\n";
                    $message .= "Email: " . ($invoice['student']['email'] ?? 'N/A') . "\n\n";
                    $message .= "Please help me to process my application. Thank you!";
                    $waUrl = "https://wa.me/" . $waNumber . "?text=" . urlencode($message);
                    ?>

                    <a href="<?= $waUrl ?>" target="_blank" class="btn btn-whatsapp btn-lg">
                        <i class="bi bi-whatsapp me-2"></i>Confirm via WhatsApp
                    </a>
                </div>
            <?php endif; ?>

            <div class="print-grid">
                <div>
                    <!-- Invoice Details -->
                    <div class="info-section">
                        <h5><i class="bi bi-file-text"></i> Invoice Details</h5>
                        <div class="info-row">
                            <div class="info-label">Invoice Type:</div>
                            <div class="info-value"><?= ucwords(str_replace('_', ' ', (string)($invoice['invoice_type'] ?? ''))) ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Issue Date:</div>
                            <div class="info-value"><?= date('F d, Y', strtotime($invoice['created_at'] ?? 'now')) ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Due Date:</div>
                            <div class="info-value"><?= date('F d, Y', strtotime($invoice['due_date'] ?? 'now')) ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Status:</div>
                            <div class="info-value">
                                <div class="status-badge status-<?= str_replace('_', '-', (string)($invoice['status'] ?? '')) ?>">
                                    <?= str_replace('_', ' ', ucfirst((string)($invoice['status'] ?? ''))) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <!-- Student Information -->
                    <div class="info-section">
                        <h5><i class="bi bi-person"></i> Student Information</h5>
                        <div class="info-row">
                            <div class="info-label">Name:</div>
                            <div class="info-value"><?= esc($invoice['student']['full_name'] ?? 'N/A') ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Reg No:</div>
                            <div class="info-value"><?= esc((string)($invoice['registration_number'] ?? 'N/A')) ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email:</div>
                            <div class="info-value"><?= esc($invoice['student']['email'] ?? 'N/A') ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="print-grid">
                <div>
                    <!-- Description -->
                    <?php if (!empty($invoice['description'])): ?>
                        <div class="info-section">
                            <h5><i class="bi bi-card-text"></i> Description</h5>
                            <p class="mb-0 small"><?= nl2br(esc((string)($invoice['description'] ?? ''))) ?></p>
                        </div>
                    <?php endif ?>
                </div>
                <div class="text-end">
                    <!-- QR Code moved here for print efficiency -->
                    <div class="info-section">
                        <div id="qrcode-print" style="display: inline-block;"></div>
                        <p class="small text-muted mt-1" style="font-size: 8px !important;">Scan to verify</p>
                    </div>
                </div>
            </div>

            <!-- Amount -->
            <div class="amount-box">
                <div class="amount-label">Total Amount</div>
                <div class="amount-value">Rp <?= number_format((float)($invoice['amount'] ?? 0), 0, ',', '.') ?></div>
            </div>

            <!-- Payments -->
            <?php if (!empty($invoice['payments'])): ?>
                <div class="info-section">
                    <h5><i class="bi bi-credit-card"></i> Payment History</h5>
                    <div class="payments-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($invoice['payments'] as $payment): ?>
                                    <tr>
                                        <td><?= date('M d, Y', strtotime($payment['payment_date'] ?? 'now')) ?></td>
                                        <td>Rp <?= number_format((float)($payment['amount'] ?? 0), 0, ',', '.') ?></td>
                                        <td><?= ucwords(str_replace('_', ' ', (string)($payment['payment_method'] ?? ''))) ?></td>
                                        <td>
                                            <span class="status-badge status-<?= (string)($payment['status'] ?? '') ?>">
                                                <?= ucfirst((string)($payment['status'] ?? '')) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <!-- QR Code Section -->
            <div class="info-section">
                <h5><i class="bi bi-qr-code"></i> Quick Access</h5>
                <div class="text-center">
                    <p class="mb-2">Scan QR code to view this invoice online</p>
                    <div id="qrcode" style="display: inline-block;"></div>
                    <p class="small text-muted mt-2">Invoice #<?= esc((string)($invoice['invoice_number'] ?? 'N/A')) ?></p>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="footer-note">
                <i class="bi bi-info-circle"></i> This is a computer-generated invoice.
                For any inquiries, please contact our administration office.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Generate QR Code when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const invoiceUrl = '<?= base_url('invoice/public/' . ($invoice['id'] ?? 0)) ?>';

            // Generate QR code for the main section
            const qrcodeElement = document.getElementById('qrcode');
            if (qrcodeElement) {
                new QRCode(qrcodeElement, {
                    text: invoiceUrl,
                    width: 200,
                    height: 200,
                    colorDark: '#8B0000',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
            }

            // Generate QR code for the print section
            const qrcodePrintElement = document.getElementById('qrcode-print');
            if (qrcodePrintElement) {
                new QRCode(qrcodePrintElement, {
                    text: invoiceUrl,
                    width: 200,
                    height: 200,
                    colorDark: '#8B0000',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
        });
    </script>
</body>

</html>