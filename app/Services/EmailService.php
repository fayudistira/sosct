<?php

namespace App\Services;

use CodeIgniter\Email\Email;

class EmailService
{
    protected $email;
    protected $config = [];

    public function __construct()
    {
        $this->email = service('email');

        // Load email configuration
        $this->config = (new \Config\Email())->getTempConfiguration();
    }

    /**
     * Send invoice to guest/applicant
     * 
     * @param array $invoice Invoice data with 'amount', 'due_date', 'description'
     * @param string $recipientEmail Guest's email
     * @param string $recipientName Guest's full name
     * @param array $admissionData Admission data for context
     * @return bool
     */
    public function sendInvoiceNotification($invoice, $recipientEmail, $recipientName, $admissionData = [])
    {
        try {
            $this->email->setFrom($this->config['SMTPUser'] ?? 'noreply@example.com', 'FEECS Admissions');
            $this->email->setTo($recipientEmail);
            $this->email->setSubject('Your Registration Invoice - ' . ($admissionData['registration_number'] ?? 'FEECS'));

            // Build HTML email
            $html = $this->buildInvoiceEmailTemplate($invoice, $recipientName, $admissionData);

            $this->email->setMessage($html);
            $this->email->setMailType('html');

            if ($this->email->send(false)) {
                log_message('info', "Invoice email sent to {$recipientEmail}");
                return true;
            } else {
                log_message('error', "Failed to send invoice email to {$recipientEmail}: " . $this->email->printDebugger());
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', "Email service error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send payment received notification
     */
    public function sendPaymentReceivedNotification($recipientEmail, $recipientName, $paymentData = [])
    {
        try {
            $this->email->setFrom($this->config['SMTPUser'] ?? 'noreply@example.com', 'FEECS Admissions');
            $this->email->setTo($recipientEmail);
            $this->email->setSubject('Payment Received - Admissions Approved');

            $html = $this->buildPaymentReceivedEmailTemplate($recipientName, $paymentData);

            $this->email->setMessage($html);
            $this->email->setMailType('html');

            if ($this->email->send(false)) {
                log_message('info', "Payment confirmation email sent to {$recipientEmail}");
                return true;
            } else {
                log_message('error', "Failed to send payment confirmation email: " . $this->email->printDebugger());
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', "Email service error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Build HTML template for invoice email
     */
    private function buildInvoiceEmailTemplate($invoice, $recipientName, $admissionData)
    {
        $logo = base_url('assets/images/logo.png'); // Adjust path as needed
        $appName = 'FEECS';
        $dueDate = date('F j, Y', strtotime($invoice['due_date'] ?? date('Y-m-d')));
        $amount = number_format($invoice['amount'] ?? 0, 2);
        $regNumber = $admissionData['registration_number'] ?? 'N/A';
        $programTitle = $admissionData['program_title'] ?? 'Program';

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                body { font-family: Arial, sans-serif; color: #333; }
                .container { max-width: 600px; margin: 0 auto; border: 1px solid #ddd; }
                .header { background-color: #004b9e; color: white; padding: 20px; text-align: center; }
                .header h1 { margin: 0; }
                .content { padding: 30px; }
                .invoice-details { background-color: #f9f9f9; padding: 20px; margin: 20px 0; border-left: 4px solid #004b9e; }
                .invoice-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
                .invoice-row.total { font-weight: bold; font-size: 18px; background-color: #e8f4f8; padding: 15px; border: none; }
                .action-buttons { text-align: center; margin: 30px 0; }
                .btn { display: inline-block; padding: 12px 30px; margin: 0 10px; text-decoration: none; border-radius: 4px; font-weight: bold; }
                .btn-primary { background-color: #004b9e; color: white; }
                .btn-whatsapp { background-color: #25d366; color: white; }
                .footer { background-color: #f5f5f5; padding: 20px; text-align: center; font-size: 12px; color: #666; border-top: 1px solid #ddd; }
                .alert { background-color: #fff3cd; border: 1px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>{$appName} - Admission Registration</h1>
                </div>

                <div class='content'>
                    <p>Dear <strong>{$recipientName}</strong>,</p>

                    <p>Thank you for your admission application to our institution. We're excited to have you join us!</p>

                    <p>Your registration invoice has been generated. Please review the details below and complete the payment before the due date.</p>

                    <div class='invoice-details'>
                        <div class='invoice-row'>
                            <span><strong>Registration Number:</strong></span>
                            <span>{$regNumber}</span>
                        </div>
                        <div class='invoice-row'>
                            <span><strong>Program:</strong></span>
                            <span>{$programTitle}</span>
                        </div>
                        <div class='invoice-row'>
                            <span><strong>Description:</strong></span>
                            <span>" . esc($invoice['description'] ?? 'Registration Fee') . "</span>
                        </div>
                        <div class='invoice-row'>
                            <span><strong>Amount:</strong></span>
                            <span>Rp {$amount}</span>
                        </div>
                        <div class='invoice-row'>
                            <span><strong>Due Date:</strong></span>
                            <span>{$dueDate}</span>
                        </div>
                        <div class='invoice-row total'>
                            <span>Total Payment:</span>
                            <span>Rp {$amount}</span>
                        </div>
                    </div>

                    <div class='alert'>
                        <strong>⚠️ Important:</strong> Please complete your payment before the due date to secure your admission spot.
                    </div>

                    <div class='action-buttons'>
                        <a href='" . base_url('payment/invoice') . "' class='btn btn-primary'>View Full Invoice</a>
                        <a href='https://wa.me/?text=I%20would%20like%20to%20inquire%20about%20my%20registration%20({$regNumber})' class='btn btn-whatsapp'>Contact via WhatsApp</a>
                    </div>

                    <p><strong>Payment Instructions:</strong></p>
                    <ul>
                        <li>Bank Transfer: [Bank Details]</li>
                        <li>Reference: {$regNumber}</li>
                        <li>Once payment is received, your admission will be automatically approved</li>
                    </ul>

                    <p>If you have any questions, please don't hesitate to reach out.</p>

                    <p>Best regards,<br>
                    <strong>FEECS Admissions Team</strong></p>
                </div>

                <div class='footer'>
                    <p>This is an automated email. Please do not reply to this message.</p>
                    <p>&copy; " . date('Y') . " FEECS. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Build HTML template for payment received email
     */
    private function buildPaymentReceivedEmailTemplate($recipientName, $paymentData)
    {
        $appName = 'FEECS';
        $amount = number_format($paymentData['amount'] ?? 0, 2);
        $paymentDate = date('F j, Y', strtotime($paymentData['created_at'] ?? 'now'));

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                body { font-family: Arial, sans-serif; color: #333; }
                .container { max-width: 600px; margin: 0 auto; border: 1px solid #ddd; }
                .header { background-color: #28a745; color: white; padding: 20px; text-align: center; }
                .header h1 { margin: 0; }
                .content { padding: 30px; }
                .success-badge { text-align: center; margin: 20px 0; }
                .success-badge .icon { font-size: 48px; }
                .details { background-color: #f0f8f4; padding: 20px; margin: 20px 0; border-radius: 4px; }
                .detail-row { padding: 10px 0; border-bottom: 1px solid #ddd; }
                .detail-row:last-child { border-bottom: none; }
                .next-steps { background-color: #e3f2fd; padding: 20px; margin: 20px 0; border-left: 4px solid #2196f3; }
                .footer { background-color: #f5f5f5; padding: 20px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>✓ Payment Received</h1>
                </div>

                <div class='content'>
                    <p>Dear <strong>{$recipientName}</strong>,</p>

                    <div class='success-badge'>
                        <div class='icon'>✓</div>
                        <p style='font-size: 18px; color: #28a745;'><strong>Payment Verified Successfully!</strong></p>
                    </div>

                    <p>We have successfully received your payment. Your admission is now <strong>APPROVED</strong>.</p>

                    <div class='details'>
                        <div class='detail-row'>
                            <strong>Payment Amount:</strong> Rp {$amount}
                        </div>
                        <div class='detail-row'>
                            <strong>Payment Date:</strong> {$paymentDate}
                        </div>
                        <div class='detail-row'>
                            <strong>Status:</strong> <span style='color: #28a745; font-weight: bold;'>APPROVED</span>
                        </div>
                    </div>

                    <div class='next-steps'>
                        <strong>🎓 What's Next?</strong>
                        <p>Your admission has been approved! You can now:</p>
                        <ul>
                            <li>Log in to your student portal using your registration number</li>
                            <li>Complete your student profile setup</li>
                            <li>View your course enrollment details</li>
                            <li>Download your admission letter</li>
                        </ul>
                    </div>

                    <p>Welcome to {$appName}! We're looking forward to seeing you soon.</p>

                    <p>Best regards,<br>
                    <strong>FEECS Admissions Team</strong></p>
                </div>

                <div class='footer'>
                    <p>This is an automated email. Please do not reply to this message.</p>
                    <p>&copy; " . date('Y') . " FEECS. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
