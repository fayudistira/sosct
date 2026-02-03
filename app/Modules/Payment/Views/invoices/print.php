<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= esc($invoice['invoice_number']) ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
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
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
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
                <h1>FEECS</h1>
                <p>Foreign Education & English Course Services</p>
                <p>Email: info@feecs.edu</p>
                <p>Phone: +62 xxx xxxx xxxx</p>
            </div>
            <div class="invoice-meta">
                <h2>INVOICE</h2>
                <p><strong>#<?= esc($invoice['invoice_number']) ?></strong></p>
                <p>Date: <?= date('d M Y', strtotime($invoice['created_at'])) ?></p>
                <p>Due: <?= date('d M Y', strtotime($invoice['due_date'])) ?></p>
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
                <h3>Bill To:</h3>
                <p><strong><?= esc($student['full_name'] ?? 'N/A') ?></strong></p>
                <p><?= esc($student['email'] ?? '') ?></p>
                <p><?= esc($student['phone'] ?? '') ?></p>
                <p>Reg. No: <?= esc($invoice['registration_number']) ?></p>
            </div>
            <div class="detail-section">
                <h3>Program:</h3>
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
                <tr>
                    <td>
                        <strong><?= esc($invoice['description']) ?></strong>
                        <?php if (!empty($invoice['notes'])): ?>
                        <br><small style="color: #666;"><?= nl2br(esc($invoice['notes'])) ?></small>
                        <?php endif ?>
                    </td>
                    <td style="text-align: right;">Rp <?= number_format($invoice['amount'], 0, ',', '.') ?></td>
                </tr>
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
        
        <!-- Footer -->
        <div class="invoice-footer">
            <div class="payment-info">
                <h4>Payment Information</h4>
                <p>Bank: BCA</p>
                <p>Account: 1234567890</p>
                <p>Name: FEECS Education</p>
                <p style="margin-top: 15px; font-size: 12px;">
                    Please include invoice number in payment description.
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
