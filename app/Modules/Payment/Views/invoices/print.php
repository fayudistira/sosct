<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur #<?= esc($invoice['invoice_number']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
                padding: 0;
            }

            @page {
                size: A4;
                margin: 10mm;
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background: #f5f5f5;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #8B0000;
        }

        .company-info h1 {
            margin: 0;
            color: #8B0000;
            font-size: 20px;
        }

        .company-info h1 {
            margin: 0;
            color: #8B0000;
            font-size: 20px;
        }

        .company-info p {
            margin: 3px 0;
            color: #666;
            font-size: 12px;
        }

        .company-logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }

        .company-logo img {
            max-width: 50px;
            height: auto;
            display: block;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-meta h2 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }

        .invoice-meta p {
            margin: 3px 0;
            color: #666;
            font-size: 12px;
        }

        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .detail-section h3 {
            margin: 0 0 5px 0;
            color: #8B0000;
            font-size: 12px;
            text-transform: uppercase;
        }

        .detail-section p {
            margin: 3px 0;
            color: #333;
            font-size: 12px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .invoice-table th {
            background: #8B0000;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        .invoice-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 12px;
        }

        .invoice-table tr:last-child td {
            border-bottom: none;
        }

        .invoice-totals {
            margin-left: auto;
            width: 250px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .total-row.grand-total {
            border-top: 2px solid #8B0000;
            margin-top: 5px;
            padding-top: 5px;
            font-weight: bold;
            font-size: 14px;
            color: #8B0000;
        }

        .invoice-footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .qr-code {
            text-align: center;
        }

        .qr-code p {
            margin: 5px 0 0 0;
            font-size: 10px;
            color: #666;
        }

        .payment-info {
            flex: 1;
            padding-right: 10px;
        }

        .payment-info h4 {
            margin: 0 0 5px 0;
            color: #8B0000;
            font-size: 12px;
        }

        .payment-info p {
            margin: 3px 0;
            font-size: 11px;
            color: #666;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 10px;
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

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .btn-action {
            flex: 1;
            min-width: 150px;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.3s ease;
        }

        .btn-print {
            background: #8B0000;
            color: white;
        }

        .btn-print:hover {
            background: #6b0000;
        }

        .btn-whatsapp {
            background-color: #25D366;
            color: white;
            border: none;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .btn-whatsapp:hover {
            background-color: #128C7E;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3);
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #8B0000;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .print-button:hover {
            background: #6B0000;
        }

        .copy-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 2px 8px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 10px;
            margin-left: 5px;
            transition: all 0.3s ease;
        }

        .copy-btn:hover {
            background: #5a6268;
        }

        .copy-btn.copied {
            background: #28a745;
        }

        .copy-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 2px 8px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 10px;
            margin-left: 5px;
            transition: all 0.3s ease;
        }

        .copy-btn:hover {
            background: #5a6268;
        }

        .copy-btn.copied {
            background: #28a745;
        }
    </style>

    <script>
        function copyToClipboard(text, element) {
            navigator.clipboard.writeText(text).then(() => {
                element.classList.add('copied');
                setTimeout(() => {
                    element.classList.remove('copied');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        }

        // Add click listeners to all copy buttons
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const textToCopy = this.getAttribute('data-copy');
                copyToClipboard(textToCopy, this);
            });
        });
    </script>
</head>

<body>
    <button class="print-button no-print" onclick="window.print()">
        🖨️ Cetak / Simpan sebagai PDF
    </button>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-logo">
                    <img src="/assets/images/sos-logo.png" alt="SOS Logo" onerror="this.style.display='none'">
                </div>
                <h1>SOSCT</h1>
                <p>SOS Course & Training</p>
                <p>Perum GPR 1 Blok C No.4, Jl. Veteran Tulungrejo, Pare, Kediri 64212</p>
                <p>Email: admin@kursusbahasa.org</p>
                <p>Telp: +62 858 1031 0950</p>
            </div>
            <div class="invoice-meta">
                <h2>FAKTUR</h2>
                <p><strong>#<?= esc($invoice['invoice_number']) ?></strong> <button class="copy-btn no-print" data-copy="#<?= esc($invoice['invoice_number']) ?>" title="Salin Nomor Faktur"><i class="bi bi-clipboard"></i></button></p>
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
                <p>No. Reg: <?= esc($invoice['registration_number']) ?></p>
            </div>
            <div class="detail-section">
                <h3>Program Terdaftar:</h3>
                <p><strong><?= esc($student['program_title'] ?? 'N/A') ?></strong></p>
                <?php if (!empty($student['category'])): ?>
                    <p>Kategori Kursus : Bahasa <?= esc($student['category']) ?></p>
                <?php endif ?>
            </div>
        </div>

        <!-- Items Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th style="width: 150px; text-align: right;">Jumlah</th>
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
                            <strong><?= esc((string)($invoice['description'] ?? '')) ?></strong>
                            <?php if (!empty($invoice['notes'])): ?>
                                <br><small style="color: #666;"><?= nl2br(esc((string)($invoice['notes'] ?? ''))) ?></small>
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
                    <span>Dibayar:</span>
                    <span style="color: green;">- Rp <?= number_format($invoice['total_paid'], 0, ',', '.') ?></span>
                </div>
            <?php endif ?>
            <div class="total-row grand-total">
                <span>Sisa Tagihan:</span>
                <span>Rp <?= number_format($invoice['amount'] - ($invoice['total_paid'] ?? 0), 0, ',', '.') ?></span>
            </div>
        </div>
        <p style="margin-top: 10px; font-size: 11px;">
            Konfirmasi Pendaftaran Anda via Whatsap di bawah untuk fast response Admin
        </p>
        <!-- Action Buttons -->
        <div class="action-buttons no-print">
            <!-- <button onclick="window.print()" class="btn-action btn-print">
                <i class="bi bi-printer"></i> Cetak / Unduh
            </button> -->
            <?php
            $waNumber = '6285810310950';
            $message = "Halo Admin, saya butuh bantuan dengan faktur saya #" . $invoice['invoice_number'] . ".";
            $waUrl = "https://wa.me/" . $waNumber . "?text=" . urlencode($message);
            ?>
            <a href="<?= $waUrl ?>" target="_blank" class="btn-action btn-whatsapp">
                <i class="bi bi-whatsapp"></i> Konfirmasi : 0858 1031 0950
            </a>
        </div>

        <!-- Footer (Print View) -->
        <div class="invoice-footer">
            <div class="payment-info">
                <h4>Informasi Pembayaran</h4>
                <p>Bank: BNI</p>
                <p>Rekening: 2205502277</p>
                <p>Nama: SOS Course and Training</p>
                <p style="margin-top: 10px; font-size: 11px;">
                    Harap cantumkan nomor Faktur <strong>#<?= esc($invoice['invoice_number']) ?></strong> pada keterangan Transfer.
                </p>
            </div>
            <div class="qr-code">
                <img src="<?= base_url('invoice/qr/' . $invoice['id']) ?>" alt="QR Code" width="100" height="100">
                <p>Scan untuk mencocokkan</p>
            </div>
        </div>
        <div class="qr-code">
            <p>Dokumen ini diterbitkan secara elektronik dan tidak memerlukan stempel basah atau tanda tangan.</p>
        </div>
</body>

</html>