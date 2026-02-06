<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= esc($invoice['invoice_number']) ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #8B0000;
        }

        .company-info h1 {
            margin: 0;
            color: #8B0000;
            font-size: 28px;
        }

        .company-info p {
            margin: 5px 0;
            color: #666;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-meta h2 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }

        .invoice-meta p {
            margin: 5px 0;
            color: #666;
        }

        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .detail-section h3 {
            margin: 0 0 10px 0;
            color: #8B0000;
            font-size: 14px;
            text-transform: uppercase;
        }

        .detail-section p {
            margin: 5px 0;
            color: #333;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .invoice-table th {
            background: #8B0000;
            color: white;
            padding: 12px;
            text-align: left;
        }

        .invoice-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .invoice-table tr:last-child td {
            border-bottom: none;
        }

        .invoice-totals {
            margin-left: auto;
            width: 300px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }

        .total-row.grand-total {
            border-top: 2px solid #8B0000;
            margin-top: 10px;
            padding-top: 10px;
            font-weight: bold;
            font-size: 18px;
            color: #8B0000;
        }

        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .qr-code {
            text-align: center;
        }

        .qr-code p {
            margin: 10px 0 0 0;
            font-size: 12px;
            color: #666;
        }

        .payment-info {
            flex: 1;
            padding-right: 20px;
        }

        .payment-info h4 {
            margin: 0 0 10px 0;
            color: #8B0000;
        }

        .payment-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-unpaid {
            background: #fff3cd;
            color: #856404;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        .status-overdue {
            background: #f8d7da;
            color: #721c24;
        }

        .invoice-actions {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .invoice-actions {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }

        .payment-instructions {
            background: #f9f3e6;
            border-left: 4px solid #8B0000;
            padding: 20px;
            border-radius: 5px;
            font-size: 14px;
        }

        .payment-instructions h5 {
            color: #8B0000;
            margin-top: 0;
            margin-bottom: 15px;
        }

        .payment-instructions ol {
            padding-left: 20px;
            margin: 0;
        }

        .payment-instructions li {
            margin-bottom: 10px;
            line-height: 1.6;
            color: #333;
        }

        .whatsapp-section {
            background: linear-gradient(135deg, #25D366 0%, #20BA5A 100%);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .whatsapp-section h5 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .whatsapp-section p {
            margin: 0 0 15px 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .btn-whatsapp {
            background: white;
            color: #25D366;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-whatsapp:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .btn-whatsapp i {
            margin-right: 8px;
            font-size: 18px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .btn-action {
            flex: 1;
            min-width: 150px;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-print {
            background: #8B0000;
            color: white;
        }

        .btn-print:hover {
            background: #6b0000;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #8B0000;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .print-button:hover {
            background: #6B0000;
        }
    </style>
</head>

<body>
    <button class="print-button no-print" onclick="window.print()">
        🖨️ Print / Save as PDF
    </button>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>SOSCT</h1>
                <p>SOS Course & Training</p>
                <p>Perum GPR 1 Blok C No.4, Jl. Veteran Tulungrejo, Pare, Kediri 64212</p>
                <p>Email: admin@kursusbahasa.org</p>
                <p>Telp: +62 858 1031 0950</p>
            </div>
            <div class="invoice-meta">
                <h2>INVOICE</h2>
                <p><strong>#<?= esc($invoice['invoice_number']) ?></strong></p>
                <p>Tgl: <?= date('d M Y', strtotime($invoice['created_at'])) ?></p>
                <p>Jt.Tempo: <?= date('d M Y', strtotime($invoice['due_date'])) ?></p>
                <p>
                    <span class="status-badge status-<?= esc($invoice['status']) ?>">
                        <?= ucfirst($invoice['status']) ?>
                    </span>
                </p>
            </div>
        </div>

        <!-- Details -->
        <div class="invoice-details">
            <div class="detail-section">
                <h3>Ditagihkan Kepada:</h3>
                <p><strong><?= esc($student['full_name'] ?? 'N/A') ?></strong></p>
                <p><?= esc($student['email'] ?? '') ?></p>
                <p><?= esc($student['phone'] ?? '') ?></p>
                <p>Reg. No: <?= esc($invoice['registration_number']) ?></p>
            </div>
            <div class="detail-section">
                <h3>Program Terdaftar:</h3>
                <p><strong><?= esc($student['program_title'] ?? 'N/A') ?></strong></p>
                <?php if (!empty($student['category'])): ?>
                    <p>Category: <?= esc($student['category']) ?></p>
                <?php endif ?>
            </div>
        </div>

        <!-- Items Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="width: 150px; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $items = [];
                if (!empty($invoice['items'])) {
                    $items = is_string($invoice['items']) ? json_decode($invoice['items'], true) : $invoice['items'];
                    $items = is_array($items) ? $items : [];
                }
                ?>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= esc($item['description'] ?? '') ?></td>
                            <td style="text-align: right;">Rp <?= number_format($item['amount'] ?? 0, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td>
                            <strong><?= esc($invoice['description']) ?></strong>
                            <?php if (!empty($invoice['notes'])): ?>
                                <br><small style="color: #666;"><?= nl2br(esc($invoice['notes'])) ?></small>
                            <?php endif ?>
                        </td>
                        <td style="text-align: right;">Rp <?= number_format($invoice['amount'], 0, ',', '.') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Totals -->
        <div class="invoice-totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Rp <?= number_format($invoice['amount'], 0, ',', '.') ?></span>
            </div>
            <?php if (!empty($invoice['total_paid']) && $invoice['total_paid'] > 0): ?>
                <div class="total-row">
                    <span>Paid:</span>
                    <span style="color: green;">- Rp <?= number_format($invoice['total_paid'], 0, ',', '.') ?></span>
                </div>
            <?php endif ?>
            <div class="total-row grand-total">
                <span>Balance Due:</span>
                <span>Rp <?= number_format($invoice['amount'] - ($invoice['total_paid'] ?? 0), 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Payment Instructions & Actions Section (No Print) -->
        <div class="invoice-actions no-print">
            <!-- Payment Instructions -->
            <div class="payment-instructions">
                <h5><i class="bi bi-building"></i> Payment Instructions</h5>
                <ol>
                    <li><strong>Bank Transfer</strong> to the account details below</li>
                    <li><strong>Include Invoice #</strong> in transfer description: <code><?= esc($invoice['invoice_number']) ?></code></li>
                    <li><strong>Due Date:</strong> <?= date('F d, Y', strtotime($invoice['due_date'])) ?></li>
                    <li><strong>Confirm Payment</strong> via WhatsApp with proof of transfer</li>
                    <li><strong>Status Update</strong> will be sent via email after verification</li>
                </ol>
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 12px;">
                    <strong>Bank Account:</strong><br>
                    Bank: BNI | Account: 2205502277<br>
                    Name: SOS Course and Training
                </div>
            </div>

            <!-- WhatsApp Confirmation -->
            <div class="whatsapp-section">
                <h5><i class="bi bi-whatsapp"></i> Confirm Payment</h5>
                <p>Click to notify admin via WhatsApp after making payment</p>
                <?php
                $waNumber = '6289509778659';
                $message = "Halo Admin, saya sudah melakukan pembayaran untuk Invoice berikut:\n\n";
                $message .= "Invoice #: " . $invoice['invoice_number'] . "\n";

                if (isset($student)) {
                    $message .= "Nama: " . $student['full_name'] . "\n";
                    $message .= "Reg No: " . $invoice['registration_number'] . "\n";
                    if (!empty($student['program_title'])) {
                        $message .= "Program: " . $student['program_title'] . "\n";
                    }
                }

                $message .= "Jumlah: Rp " . number_format($invoice['amount'], 0, ',', '.') . "\n";
                $message .= "Tanggal jatuh tempo: " . date('d F Y', strtotime($invoice['due_date'])) . "\n\n";
                $message .= "Mohon verifikasi pembayaran saya. Terima kasih!";

                $waUrl = "https://wa.me/" . $waNumber . "?text=" . urlencode($message);
                ?>
                <a href="<?= $waUrl ?>" target="_blank" class="btn-whatsapp">
                    <i class="bi bi-whatsapp"></i> Send Confirmation
                </a>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons no-print" style="margin-top: 30px;">
            <button onclick="window.print()" class="btn-action btn-print">
                <i class="bi bi-printer"></i> Print / Download
            </button>
        </div>

        <!-- Footer (Print View) -->
        <div class="invoice-footer">
            <div class="payment-info">
                <h4>Informasi Pembayaran</h4>
                <p>Bank: BNI</p>
                <p>Account: 2205502277</p>
                <p>Name: SOS Course and Training</p>
                <p style="margin-top: 15px; font-size: 12px;">
                    Harap cantumkan nomor Invoice <strong>#<?= esc($invoice['invoice_number']) ?></strong>pada keterangan Transfer.
                </p>
            </div>
            <div class="qr-code">
                <div id="qrcode"></div>
                <p>Scan to view online</p>
            </div>
        </div>
    </div>

    <script>
        // Generate QR Code
        const publicUrl = '<?= base_url('invoice/public/' . $invoice['id']) ?>';
        new QRCode(document.getElementById('qrcode'), {
            text: publicUrl,
            width: 120,
            height: 120,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    </script>
</body>

</html>