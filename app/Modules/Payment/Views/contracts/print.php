<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Contract Print' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
            padding: 40px;
        }

        .contract-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #667eea;
        }

        .contract-header h1 {
            font-size: 1.8rem;
            color: #667eea;
            margin-bottom: 5px;
        }

        .contract-header .subtitle {
            color: #718096;
            font-size: 1rem;
        }

        .contract-number {
            background: #f7fafc;
            padding: 10px 20px;
            border-radius: 6px;
            display: inline-block;
            margin-top: 10px;
            font-weight: 600;
            color: #2d3748;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 1rem;
            color: #667eea;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e2e8f0;
            text-transform: uppercase;
            font-weight: 600;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-item {
            padding: 10px;
            background: #f7fafc;
            border-radius: 4px;
        }

        .info-item label {
            display: block;
            font-size: 0.75rem;
            color: #718096;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .info-item p {
            font-weight: 500;
            color: #2d3748;
        }

        .summary-box {
            background: linear-gradient(135deg, #f6f8fb 0%, #e8ecf1 100%);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .summary-item {
            text-align: center;
            padding: 15px;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .summary-item .label {
            font-size: 0.7rem;
            color: #718096;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .summary-item .value {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d3748;
        }

        .summary-item.total .value {
            color: #667eea;
        }

        .summary-item.paid .value {
            color: #48bb78;
        }

        .summary-item.balance .value {
            color: #ed8936;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 0.9rem;
        }

        th,
        td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background: #f7fafc;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .amount-positive {
            color: #48bb78;
            font-weight: 600;
        }

        .total-row {
            background: #f7fafc;
            font-weight: 700;
        }

        .total-row td {
            border-top: 2px solid #667eea;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-badge.unpaid {
            background: #fed7d7;
            color: #c53030;
        }

        .status-badge.partial {
            background: #feebc8;
            color: #c05621;
        }

        .status-badge.paid {
            background: #c6f6d5;
            color: #2f855a;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #718096;
        }

        .signature-box {
            width: 200px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #2d3748;
            margin-top: 50px;
            padding-top: 5px;
        }

        .print-date {
            text-align: right;
            font-size: 0.8rem;
            color: #718096;
            margin-bottom: 20px;
        }

        @media print {
            body {
                padding: 20px;
            }

            .no-print {
                display: none;
            }

            @page {
                margin: 1cm;
            }
        }
    </style>
</head>

<body>
    <div class="print-date">Print Date: <?= date('d M Y H:i', strtotime($printDate)) ?></div>

    <div class="contract-header">
        <h1>Contract Agreement</h1>
        <div class="subtitle">Foreign Language Education Center System</div>
        <div class="contract-number">Contract #: <?= $installment['registration_number'] ?></div>
    </div>

    <div class="section">
        <div class="section-title">Student Information</div>
        <div class="info-grid">
            <div class="info-item">
                <label>Full Name</label>
                <p><?= $admission['full_name'] ?? $installment['full_name'] ?></p>
            </div>
            <div class="info-item">
                <label>Email</label>
                <p><?= $admission['email'] ?? $installment['email'] ?></p>
            </div>
            <div class="info-item">
                <label>Phone</label>
                <p><?= $admission['phone'] ?? 'N/A' ?></p>
            </div>
            <div class="info-item">
                <label>Program</label>
                <p><?= $admission['program_title'] ?? $installment['program_title'] ?></p>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Contract Details</div>
        <div class="info-grid">
            <div class="info-item">
                <label>Contract Status</label>
                <p><span class="status-badge <?= $installment['status'] ?>"><?= ucfirst($installment['status']) ?></span></p>
            </div>
            <div class="info-item">
                <label>Payment Due Date</label>
                <p><?= date('d M Y', strtotime($installment['due_date'])) ?></p>
            </div>
            <div class="info-item">
                <label>Category</label>
                <p><?= $installment['category'] ?? 'N/A' ?></p>
            </div>
            <div class="info-item">
                <label>Contract Date</label>
                <p><?= date('d M Y', strtotime($installment['created_at'])) ?></p>
            </div>
        </div>
    </div>

    <div class="summary-box">
        <div class="summary-grid">
            <div class="summary-item total">
                <div class="label">Total Contract Amount</div>
                <div class="value">Rp <?= number_format($installment['total_contract_amount'], 0, ',', '.') ?></div>
            </div>
            <div class="summary-item paid">
                <div class="label">Total Paid</div>
                <div class="value">Rp <?= number_format($totalPaid, 0, ',', '.') ?></div>
            </div>
            <div class="summary-item balance">
                <div class="label">Remaining Balance</div>
                <div class="value">Rp <?= number_format($remainingBalance, 0, ',', '.') ?></div>
            </div>
        </div>
    </div>

    <?php if (!empty($payments)): ?>
        <div class="section">
            <div class="section-title">Payment History</div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Document Number</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($payment['payment_date'])) ?></td>
                            <td><?= $payment['document_number'] ?? 'N/A' ?></td>
                            <td><?= ucfirst(str_replace('_', ' ', $payment['payment_method'])) ?></td>
                            <td class="amount-positive">Rp <?= number_format($payment['amount'], 0, ',', '.') ?></td>
                            <td><span class="status-badge <?= $payment['status'] ?>"><?= ucfirst($payment['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">Total Paid:</td>
                        <td class="amount-positive">Rp <?= number_format($totalPaid, 0, ',', '.') ?></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="footer">
        <div class="signature-box">
            <p>Student Signature</p>
            <div class="signature-line"><?= $admission['full_name'] ?? $installment['full_name'] ?></div>
        </div>
        <div class="signature-box">
            <p>Authorized Signature</p>
            <div class="signature-line">FEECS Admin</div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>