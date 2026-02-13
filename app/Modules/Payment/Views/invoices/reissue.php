<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Re-issue Invoice' ?></title>
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
            background-color: #f5f5f5;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            padding: 20px;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 20px;
        }

        .card-header h1 {
            font-size: 1.25rem;
            margin: 0;
        }

        .card-body {
            padding: 25px;
        }

        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-info {
            background: #bee3f8;
            border-left: 4px solid #3182ce;
            color: #2c5282;
        }

        .alert-warning {
            background: #feebc8;
            border-left: 4px solid #dd6b20;
            color: #c05621;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-item {
            background: #f7fafc;
            padding: 12px;
            border-radius: 6px;
        }

        .info-item label {
            display: block;
            font-size: 0.75rem;
            color: #718096;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .info-item p {
            font-weight: 600;
            color: #2d3748;
        }

        .amount-box {
            background: linear-gradient(135deg, #f6f8fb 0%, #e8ecf1 100%);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }

        .amount-box .label {
            font-size: 0.85rem;
            color: #718096;
            margin-bottom: 5px;
        }

        .amount-box .amount {
            font-size: 2rem;
            font-weight: 700;
            color: #ed8936;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.95rem;
            color: #2d3748;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: #667eea;
            color: #fff;
        }

        .btn-primary:hover {
            background: #5a67d8;
        }

        .btn-secondary {
            background: #718096;
            color: #fff;
        }

        .btn-secondary:hover {
            background: #4a5568;
        }

        .actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Re-issue Invoice</h1>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <strong>Warning:</strong> The original invoice will be marked as "Extended" and a new invoice will be created for the remaining balance.
                </div>

                <div class="alert alert-info">
                    <strong>Original Invoice:</strong> <?= $invoice['invoice_number'] ?><br>
                    <strong>Status:</strong> <?= ucfirst($invoice['status']) ?>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <label>Registration Number</label>
                        <p><?= $invoice['registration_number'] ?></p>
                    </div>
                    <div class="info-item">
                        <label>Invoice Type</label>
                        <p><?= ucfirst(str_replace('_', ' ', $invoice['invoice_type'])) ?></p>
                    </div>
                    <div class="info-item">
                        <label>Original Amount</label>
                        <p><?= number_format($invoice['amount'], 2) ?></p>
                    </div>
                    <div class="info-item">
                        <label>Due Date</label>
                        <p><?= date('d M Y', strtotime($invoice['due_date'])) ?></p>
                    </div>
                </div>

                <?php if ($installment): ?>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Total Contract Amount</label>
                            <p><?= number_format($installment['total_contract_amount'], 2) ?></p>
                        </div>
                        <div class="info-item">
                            <label>Total Paid</label>
                            <p><?= number_format($installment['total_paid'], 2) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="amount-box">
                    <div class="label">Remaining Balance to Re-issue</div>
                    <div class="amount"><?= number_format($remainingBalance, 2) ?></div>
                </div>

                <form action="<?= base_url('invoice/process-reissue') ?>" method="post">
                    <?= csrf_field() ?>

                    <input type="hidden" name="invoice_id" value="<?= $invoice['id'] ?>">

                    <div class="form-group">
                        <label for="due_date">New Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control"
                            value="<?= date('Y-m-d', strtotime('+2 weeks')) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes (Optional)</label>
                        <textarea name="notes" id="notes" class="form-control"
                            placeholder="Reason for re-issuing invoice..."></textarea>
                    </div>

                    <div class="actions">
                        <a href="<?= base_url('invoice/view/' . $invoice['id']) ?>" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Re-issue Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>