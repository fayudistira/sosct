<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= esc($invoice['invoice_number']) ?></title>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 0; }
            @page { size: A4; margin: 10mm; }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
            font-size: 12px;
            color: #333;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }

        .company-info h1 {
            margin: 0 0 5px 0;
            color: #8B0000;
            font-size: 24px;
        }

        .company-info p {
            margin: 2px 0;
            color: #666;
            font-size: 11px;
        }

        .invoice-meta h2 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 20px;
        }

        .invoice-meta p {
            margin: 3px 0;
            color: #666;
            font-size: 12px;
        }

        .invoice-number {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-unpaid { background: #fff3cd; color: #856404; }
        .status-paid { background: #d4edda; color: #155724; }
        .status-overdue { background: #f8d7da; color: #721c24; }
        .status-partially_paid { background: #cce5ff; color: #004085; }

        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .detail-section h3 {
            margin: 0 0 8px 0;
            color: #8B0000;
            font-size: 11px;
            text-transform: uppercase;
        }

        .detail-section p {
            margin: 4px 0;
            color: #333;
            font-size: 12px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th {
            background: #8B0000;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }

        .data-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }

        .data-table td.amount {
            text-align: right;
            font-weight: bold;
        }

        .totals {
            margin-left: auto;
            width: 300px;
            margin-bottom: 30px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }

        .total-row.grand-total {
            border-top: 2px solid #8B0000;
            margin-top: 8px;
            padding-top: 8px;
            font-weight: bold;
            font-size: 14px;
            color: #8B0000;
        }

        .payment-info {
            margin-top: 30px;
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .bank-info h4 {
            margin: 0 0 8px 0;
            color: #8B0000;
            font-size: 12px;
        }

        .bank-info p {
            margin: 3px 0;
            font-size: 11px;
            color: #666;
        }

        .invoice-url {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .invoice-url p {
            margin: 0;
            font-size: 11px;
            color: #666;
        }

        .invoice-url a {
            color: #8B0000;
            font-size: 11px;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #8B0000;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .print-button:hover {
            background: #6b0000;
        }
    </style>
</head>

<body>
    <button class="print-button no-print" onclick="window.print()">
        Print / Save as PDF
    </button>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>SOSCT</h1>
                <p>SOS Course & Training</p>
                <p>Perum GPR 1 Blok C No.4, Jl. Veteran Tulungrejo, Pare, Kediri 64212</p>
                <p>Email: admin@kursusbahasa.org | Telp: +62 858 1031 0950</p>
            </div>
            <div class="invoice-meta">
                <h2>INVOICE</h2>
                <p class="invoice-number">#<?= esc($invoice['invoice_number']) ?></p>
                <p>Date: <?= date('d M Y', strtotime($invoice['created_at'])) ?></p>
                <p>Due Date: <?= date('d M Y', strtotime($invoice['due_date'])) ?></p>
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
                <h3>Student</h3>
                <p><strong><?= esc($student['full_name'] ?? 'N/A') ?></strong></p>
                <p><?= esc($student['email'] ?? '') ?></p>
                <p><?= esc($student['phone'] ?? '') ?></p>
                <p>Reg. Number: <?= esc($invoice['registration_number']) ?></p>
            </div>
            <div class="detail-section">
                <h3>Program</h3>
                <p><strong><?= esc($student['program_title'] ?? 'N/A') ?></strong></p>
                <?php if (!empty($student['category'])): ?>
                    <p>Language: <?= esc(ucfirst($student['category'])) ?></p>
                <?php endif ?>
            </div>
        </div>

        <!-- Items Table -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Amount</th>
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
                            <td class="amount">Rp <?= number_format($item['amount'] ?? 0, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td><?= esc($invoice['description'] ?? 'Course Fee') ?></td>
                        <td class="amount">Rp <?= number_format($invoice['amount'], 0, ',', '.') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <?php if ($invoice['invoice_type'] === 'tuition_fee' && !empty($installment)): ?>
                <div class="total-row">
                    <span>Program Fee</span>
                    <span>Rp <?= number_format($student['tuition_fee'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="total-row">
                    <span>Registration Fee</span>
                    <span>Rp <?= number_format($student['program_registration_fee'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="total-row">
                    <span>Total Contract</span>
                    <span>Rp <?= number_format(($student['tuition_fee'] ?? 0) + ($student['program_registration_fee'] ?? 0), 0, ',', '.') ?></span>
                </div>
                <?php if ($totalPaid > 0): ?>
                    <div class="total-row">
                        <span>Paid</span>
                        <span style="color: green;">- Rp <?= number_format($totalPaid, 0, ',', '.') ?></span>
                    </div>
                <?php endif; ?>
                <div class="total-row grand-total">
                    <span>Remaining Balance</span>
                    <span>Rp <?= number_format((($student['tuition_fee'] ?? 0) + ($student['program_registration_fee'] ?? 0)) - $totalPaid, 0, ',', '.') ?></span>
                </div>
            <?php else: ?>
                <div class="total-row">
                    <span>Total Amount</span>
                    <span>Rp <?= number_format($invoice['amount'], 0, ',', '.') ?></span>
                </div>
                <?php if (!empty($invoice['total_paid']) && $invoice['total_paid'] > 0): ?>
                    <div class="total-row">
                        <span>Paid</span>
                        <span style="color: green;">- Rp <?= number_format($invoice['total_paid'], 0, ',', '.') ?></span>
                    </div>
                <?php endif; ?>
                <div class="total-row grand-total">
                    <span>Remaining Balance</span>
                    <span>Rp <?= number_format($invoice['amount'] - ($invoice['total_paid'] ?? 0), 0, ',', '.') ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="bank-info">
                <h4>Payment Information</h4>
                <p><strong>Bank:</strong> BNI</p>
                <p><strong>Account:</strong> 2205502277</p>
                <p><strong>Name:</strong> SOS Course and Training</p>
                <p style="margin-top: 10px; font-size: 10px; color: #999;">
                    Please include invoice number #<?= esc($invoice['invoice_number']) ?> in the transfer description.
                </p>
            </div>
        </div>

        <!-- Invoice URL -->
        <div class="invoice-url">
            <p><strong>Invoice URL:</strong> <a href="<?= base_url('invoice/view/' . $invoice['id']) ?>"><?= base_url('invoice/view/' . $invoice['id']) ?></a></p>
        </div>

        <!-- WhatsApp Confirmation -->
        <div class="no-print" style="margin-top: 30px; text-align: center; padding: 20px; background: #f8f9fa; border-radius: 10px;">
            <h5 style="color: #25D366; margin-bottom: 15px;"><i class="bi bi-whatsapp"></i> Confirm Payment via WhatsApp</h5>
            <p style="font-size: 12px; color: #666; margin-bottom: 15px;">Click the button below to notify our admission team about your payment.</p>
            
            <a href="<?= $waUrl ?? '#' ?>" target="_blank" id="wa-confirm-btn" class="btn" style="background-color: #25D366; color: white; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; display: inline-block;">
                <i class="bi bi-whatsapp me-2"></i>Confirm via WhatsApp
            </a>
            
            <div id="wa-countdown" style="margin-top: 15px; font-size: 12px; color: #666;">
                <span id="countdown-text">Auto-confirming in 3 seconds...</span>
            </div>
        </div>
    </div>
</body>

<script>
// Auto-redirect to WhatsApp after 3 seconds
document.addEventListener('DOMContentLoaded', function() {
    const waBtn = document.getElementById('wa-confirm-btn');
    const countdownText = document.getElementById('countdown-text');
    
    if (waBtn && countdownText && waBtn.href && waBtn.href !== '#') {
        let secondsLeft = 3;
        
        // Update countdown every second
        const countdownInterval = setInterval(function() {
            secondsLeft--;
            if (secondsLeft > 0) {
                countdownText.textContent = 'Auto-confirming in ' + secondsLeft + ' seconds...';
            } else {
                clearInterval(countdownInterval);
                countdownText.innerHTML = '<span style="color: #25D366;"><i class="bi bi-check-circle"></i> Redirecting to WhatsApp...</span>';
                
                // Open WhatsApp in new tab
                waBtn.click();
            }
        }, 1000);
    }
});
</script>

</html>
