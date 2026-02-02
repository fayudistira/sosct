<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
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
        @media print {
            body {
                background: white;
            }
            .invoice-container {
                box-shadow: none;
                margin: 0;
            }
            .no-print {
                display: none;
            }
            /* Ensure QR code prints */
            img {
                max-width: 100%;
                page-break-inside: avoid;
            }
            .info-section {
                page-break-inside: avoid;
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
                    <div class="invoice-number"><?= esc($invoice['invoice_number']) ?></div>
                </div>
                <div class="text-end no-print">
                    <button onclick="window.print()" class="btn btn-light">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="invoice-body">
            <!-- Invoice Details -->
            <div class="info-section">
                <h5><i class="bi bi-file-text"></i> Invoice Details</h5>
                <div class="info-row">
                    <div class="info-label">Invoice Type:</div>
                    <div class="info-value"><?= ucwords(str_replace('_', ' ', $invoice['invoice_type'])) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Issue Date:</div>
                    <div class="info-value"><?= date('F d, Y', strtotime($invoice['created_at'])) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Due Date:</div>
                    <div class="info-value"><?= date('F d, Y', strtotime($invoice['due_date'])) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge status-<?= $invoice['status'] ?>">
                            <?= ucfirst($invoice['status']) ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Student Information -->
            <div class="info-section">
                <h5><i class="bi bi-person"></i> Student Information</h5>
                <div class="info-row">
                    <div class="info-label">Name:</div>
                    <div class="info-value"><?= esc($invoice['student']['full_name'] ?? 'N/A') ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Registration Number:</div>
                    <div class="info-value"><?= esc($invoice['registration_number']) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value"><?= esc($invoice['student']['email'] ?? 'N/A') ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phone:</div>
                    <div class="info-value"><?= esc($invoice['student']['phone'] ?? 'N/A') ?></div>
                </div>
            </div>

            <!-- Description -->
            <?php if (!empty($invoice['description'])): ?>
            <div class="info-section">
                <h5><i class="bi bi-card-text"></i> Description</h5>
                <p class="mb-0"><?= nl2br(esc($invoice['description'])) ?></p>
            </div>
            <?php endif ?>

            <!-- Amount -->
            <div class="amount-box">
                <div class="amount-label">Total Amount</div>
                <div class="amount-value">Rp <?= number_format($invoice['amount'], 0, ',', '.') ?></div>
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
                                <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                                <td>Rp <?= number_format($payment['amount'], 0, ',', '.') ?></td>
                                <td><?= ucwords(str_replace('_', ' ', $payment['payment_method'])) ?></td>
                                <td>
                                    <span class="status-badge status-<?= $payment['status'] ?>">
                                        <?= ucfirst($payment['status']) ?>
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
                    <img src="<?= base_url('invoice/qr/' . $invoice['id']) ?>" 
                         alt="Invoice QR Code" 
                         style="max-width: 200px; border: 2px solid #ddd; padding: 10px; border-radius: 8px;">
                    <p class="small text-muted mt-2">Invoice #<?= esc($invoice['invoice_number']) ?></p>
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
</body>
</html>
