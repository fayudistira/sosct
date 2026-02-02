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
            color