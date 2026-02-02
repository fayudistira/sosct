<?php

namespace Modules\Payment\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGenerator
{
    protected $dompdf;
    protected $options;

    /**
     * Constructor - Initialize PDF library
     */
    public function __construct()
    {
        $this->options = new Options();
        $this->options->set('isHtml5ParserEnabled', true);
        $this->options->set('isRemoteEnabled', true);
        
        $this->dompdf = new Dompdf($this->options);
    }

    /**
     * Apply dark red gradient theme to PDF
     * Colors: #8B0000 to #6B0000
     * 
     * @return string CSS styles for theme
     */
    protected function applyTheme(): string
    {
        return '
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                }
                .header {
                    background: linear-gradient(to right, #8B0000, #6B0000);
                    color: white;
                    padding: 20px;
                    margin-bottom: 20px;
                    border-radius: 5px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 24px;
                }
                .content {
                    padding: 20px;
                    border: 2px solid #8B0000;
                    border-radius: 5px;
                }
                .info-row {
                    margin-bottom: 10px;
                    padding: 5px 0;
                }
                .label {
                    font-weight: bold;
                    color: #8B0000;
                }
                .total {
                    font-size: 18px;
                    font-weight: bold;
                    color: #8B0000;
                    margin-top: 20px;
                    padding-top: 10px;
                    border-top: 2px solid #8B0000;
                }
            </style>
        ';
    }

    /**
     * Generate invoice PDF
     * 
     * @param array $invoiceData Invoice data with student details
     * @return string|false File path or false on failure
     */
    public function generateInvoicePdf(array $invoiceData)
    {
        // Generate QR code as base64 for embedding in PDF
        $qrCodeBase64 = $this->generateQrCodeBase64($invoiceData['id']);
        
        // Create PDF template
        $html = $this->applyTheme();
        $html .= '
            <div class="header">
                <h1>ERP System - INVOICE</h1>
            </div>
            <div class="content">
                <div class="info-row">
                    <span class="label">Invoice Number:</span> ' . htmlspecialchars($invoiceData['invoice_number']) . '
                </div>
                <div class="info-row">
                    <span class="label">Date:</span> ' . htmlspecialchars($invoiceData['created_at'] ?? date('Y-m-d')) . '
                </div>
                <div class="info-row">
                    <span class="label">Due Date:</span> ' . htmlspecialchars($invoiceData['due_date']) . '
                </div>
                
                <h3 style="color: #8B0000; margin-top: 20px;">Student Information</h3>
                <div class="info-row">
                    <span class="label">Name:</span> ' . htmlspecialchars($invoiceData['student_name'] ?? 'N/A') . '
                </div>
                <div class="info-row">
                    <span class="label">Registration Number:</span> ' . htmlspecialchars($invoiceData['registration_number']) . '
                </div>
                
                <h3 style="color: #8B0000; margin-top: 20px;">Invoice Details</h3>
                <div class="info-row">
                    <span class="label">Description:</span> ' . htmlspecialchars($invoiceData['description']) . '
                </div>
                <div class="info-row">
                    <span class="label">Type:</span> ' . htmlspecialchars(ucwords(str_replace('_', ' ', $invoiceData['invoice_type']))) . '
                </div>
                <div class="info-row">
                    <span class="label">Amount:</span> Rp ' . number_format($invoiceData['amount'], 0, ',', '.') . '
                </div>
                
                <div class="total">
                    Total Amount: Rp ' . number_format($invoiceData['amount'], 0, ',', '.') . '
                </div>
                
                <div style="text-align: center; margin-top: 30px; padding: 20px; border: 2px solid #8B0000; border-radius: 5px;">
                    <p style="margin: 0 0 10px 0; font-weight: bold; color: #8B0000;">Scan to View Online</p>
                    <img src="' . $qrCodeBase64 . '" alt="QR Code" style="width: 150px; height: 150px;">
                    <p style="margin: 10px 0 0 0; font-size: 12px; color: #666;">Invoice #' . htmlspecialchars($invoiceData['invoice_number']) . '</p>
                </div>
            </div>
        ';
        
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
        
        // Save PDF to writable/uploads/invoices/ directory
        $uploadPath = WRITEPATH . 'uploads/invoices/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Generate unique filename
        $filename = 'invoice_' . $invoiceData['invoice_number'] . '_' . time() . '.pdf';
        $filePath = $uploadPath . $filename;
        
        // Save PDF
        file_put_contents($filePath, $this->dompdf->output());
        
        // Return relative path
        return 'invoices/' . $filename;
    }

    /**
     * Generate QR code as base64 data URI for embedding in PDF
     * 
     * @param int $invoiceId Invoice ID
     * @return string Base64 data URI
     */
    protected function generateQrCodeBase64($invoiceId): string
    {
        try {
            // Generate public URL for invoice
            $publicUrl = base_url('invoice/public/' . $invoiceId);
            
            // Create QR code using Builder
            $result = \Endroid\QrCode\Builder\Builder::create()
                ->data($publicUrl)
                ->size(300)
                ->margin(10)
                ->build();
            
            // Convert to base64 data URI
            return 'data:image/png;base64,' . base64_encode($result->getString());
        } catch (\Exception $e) {
            // Return empty string if QR generation fails
            return '';
        }
    }

    /**
     * Generate receipt PDF
     * 
     * @param array $paymentData Payment data with student and invoice details
     * @return string|false File path or false on failure
     */
    public function generateReceiptPdf(array $paymentData)
    {
        // Create PDF template
        $html = $this->applyTheme();
        $html .= '
            <div class="header">
                <h1>ERP System - RECEIPT</h1>
            </div>
            <div class="content">
                <div class="info-row">
                    <span class="label">Receipt Number:</span> RCP-' . str_pad($paymentData['id'], 6, '0', STR_PAD_LEFT) . '
                </div>
                <div class="info-row">
                    <span class="label">Payment Date:</span> ' . htmlspecialchars($paymentData['payment_date']) . '
                </div>
                
                <h3 style="color: #8B0000; margin-top: 20px;">Student Information</h3>
                <div class="info-row">
                    <span class="label">Name:</span> ' . htmlspecialchars($paymentData['student_name'] ?? 'N/A') . '
                </div>
                <div class="info-row">
                    <span class="label">Registration Number:</span> ' . htmlspecialchars($paymentData['registration_number']) . '
                </div>
                
                <h3 style="color: #8B0000; margin-top: 20px;">Payment Details</h3>
                <div class="info-row">
                    <span class="label">Amount:</span> Rp ' . number_format($paymentData['amount'], 0, ',', '.') . '
                </div>
                <div class="info-row">
                    <span class="label">Payment Method:</span> ' . htmlspecialchars(ucwords(str_replace('_', ' ', $paymentData['payment_method']))) . '
                </div>
                <div class="info-row">
                    <span class="label">Document Number:</span> ' . htmlspecialchars($paymentData['document_number']) . '
                </div>
                ' . (isset($paymentData['invoice_number']) ? '
                <div class="info-row">
                    <span class="label">Invoice Number:</span> ' . htmlspecialchars($paymentData['invoice_number']) . '
                </div>
                ' : '') . '
                
                <div class="total">
                    Total Paid: Rp ' . number_format($paymentData['amount'], 0, ',', '.') . '
                </div>
            </div>
        ';
        
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
        
        // Save PDF to public/uploads/receipts/ directory
        $uploadPath = FCPATH . 'uploads/receipts/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Generate unique filename
        $filename = 'receipt_' . $paymentData['id'] . '_' . time() . '.pdf';
        $filePath = $uploadPath . $filename;
        
        // Save PDF
        file_put_contents($filePath, $this->dompdf->output());
        
        // Return relative path
        return 'receipts/' . $filename;
    }
}
