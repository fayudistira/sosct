<?php

namespace App\Services;

use CodeIgniter\Email\Email;
use CodeIgniter\Encryption\Encryption;

class EmailService
{
    protected $email;
    protected $emailConfig;
    protected $encryption;

    public function __construct()
    {
        // Use emailer helper like Shield does for better configuration
        helper('email');
        $this->email = emailer(['mailType' => 'html']);

        // Load email configuration
        $this->emailConfig = config('Email');

        // Load encryption for secure tokens (with fallback)
        try {
            $this->encryption = service('encrypter');
        } catch (\Exception $e) {
            log_message('error', 'EmailService: Encryption not available - ' . $e->getMessage());
            $this->encryption = null;
        }
    }

    /**
     * Generate secure token for invoice link
     *
     * @param int $invoiceId Invoice ID
     * @param string $email Recipient email
     * @return string Encrypted token
     */
    public function generateInvoiceToken(int $invoiceId, string $email): string
    {
        // If encryption is not available, return a simple fallback token
        if ($this->encryption === null) {
            // Create a simple base64-encoded token without encryption
            $data = [
                'invoice_id' => $invoiceId,
                'email' => $email,
                'timestamp' => time()
            ];
            return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
        }

        $data = [
            'invoice_id' => $invoiceId,
            'email' => $email,
            'timestamp' => time(),
            'hash' => hash('sha256', $invoiceId . $email . time() . 'invoice_salt')
        ];

        // Encrypt and then encode as URL-safe base64 to avoid disallowed URI characters
        $encrypted = $this->encryption->encrypt(json_encode($data));
        return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
    }

    /**
     * Verify invoice token
     *
     * @param string $token Encrypted token
     * @return array|false Decoded data or false if invalid
     */
    public function verifyInvoiceToken(string $token)
    {
        try {
            // Decode URL-safe base64 back to raw encrypted data
            $encrypted = base64_decode(strtr($token, '-_', '+/'));

            // If encryption is not available, try to decode as simple base64 JSON
            if ($this->encryption === null) {
                $decoded = json_decode($encrypted, true);
                if (!$decoded || !isset($decoded['invoice_id'], $decoded['email'], $decoded['timestamp'])) {
                    return false;
                }
                // Check if token is expired (24 hours)
                if (time() - $decoded['timestamp'] > 86400) {
                    return false;
                }
                return $decoded;
            }

            $decoded = json_decode($this->encryption->decrypt($encrypted), true);

            if (!$decoded || !isset($decoded['invoice_id'], $decoded['email'], $decoded['timestamp'], $decoded['hash'])) {
                return false;
            }

            // Check if token is expired (24 hours)
            if (time() - $decoded['timestamp'] > 86400) {
                return false;
            }

            // Verify hash
            $expectedHash = hash('sha256', $decoded['invoice_id'] . $decoded['email'] . $decoded['timestamp'] . 'invoice_salt');
            if (!hash_equals($decoded['hash'], $expectedHash)) {
                return false;
            }

            return $decoded;
        } catch (\Exception $e) {
            log_message('error', 'Invoice token verification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate secure token for payment receipt link
     *
     * @param int $paymentId Payment ID
     * @param string $email Recipient email
     * @return string Encrypted token
     */
    public function generatePaymentToken(int $paymentId, string $email): string
    {
        // If encryption is not available, return a simple fallback token
        if ($this->encryption === null) {
            // Create a simple base64-encoded token without encryption
            $data = [
                'payment_id' => $paymentId,
                'email' => $email,
                'timestamp' => time()
            ];
            return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
        }

        $data = [
            'payment_id' => $paymentId,
            'email' => $email,
            'timestamp' => time(),
            'hash' => hash('sha256', $paymentId . $email . time() . 'payment_salt')
        ];

        // Encrypt and then encode as URL-safe base64 to avoid disallowed URI characters
        $encrypted = $this->encryption->encrypt(json_encode($data));
        return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
    }

    /**
     * Verify payment token
     *
     * @param string $token Encrypted token
     * @return array|false Decoded data or false if invalid
     */
    public function verifyPaymentToken(string $token)
    {
        try {
            // Decode URL-safe base64 back to raw encrypted data
            $encrypted = base64_decode(strtr($token, '-_', '+/'));

            // If encryption is not available, try to decode as simple base64 JSON
            if ($this->encryption === null) {
                $decoded = json_decode($encrypted, true);
                if (!$decoded || !isset($decoded['payment_id'], $decoded['email'], $decoded['timestamp'])) {
                    return false;
                }
                // Check if token is expired (24 hours)
                if (time() - $decoded['timestamp'] > 86400) {
                    return false;
                }
                return $decoded;
            }

            $decoded = json_decode($this->encryption->decrypt($encrypted), true);

            if (!$decoded || !isset($decoded['payment_id'], $decoded['email'], $decoded['timestamp'], $decoded['hash'])) {
                return false;
            }

            // Check if token is expired (24 hours)
            if (time() - $decoded['timestamp'] > 86400) {
                return false;
            }

            // Verify hash
            $expectedHash = hash('sha256', $decoded['payment_id'] . $decoded['email'] . $decoded['timestamp'] . 'payment_salt');
            if (!hash_equals($decoded['hash'], $expectedHash)) {
                return false;
            }

            return $decoded;
        } catch (\Exception $e) {
            log_message('error', 'Payment token verification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send invoice to guest/applicant
     * 
     * @param array $invoice Invoice data with 'amount', 'due_date', 'description'
     * @param string $recipientEmail Guest's email
     * @param string $recipientName Guest's full name
     * @param array $admissionData Admission data for context
     * @param int|null $invoiceId Invoice ID for generating direct link
     * @return bool
     */
    public function sendInvoiceNotification($invoice, $recipientEmail, $recipientName, $admissionData = [], $invoiceId = null)
    {
        try {
            // Generate secure token for invoice link, fallback to simple link if encryption fails
            try {
                if ($this->encryption !== null) {
                    $token = $this->generateInvoiceToken($invoiceId, $recipientEmail);
                    $invoiceLink = base_url('invoice/secure/' . $token);
                } else {
                    // Encryption not available, use simple invoice link
                    $invoiceLink = base_url('invoice/public/' . $invoiceId);
                }
            } catch (\Exception $e) {
                log_message('error', 'Token generation failed, using simple invoice link: ' . $e->getMessage());
                $invoiceLink = base_url('invoice/public/' . $invoiceId);
            }

            // Check if email is properly configured
            if (empty($this->emailConfig->fromEmail) || empty($this->emailConfig->SMTPHost)) {
                log_message('error', 'Email not configured properly');
                return false;
            }

            // Set email properties
            $this->email->setFrom($this->emailConfig->fromEmail, $this->emailConfig->fromName);
            $this->email->setTo($recipientEmail);
            $this->email->setSubject('Your Registration Invoice - ' . ($admissionData['registration_number'] ?? 'SOSCT'));

            // Build HTML email
            $html = $this->buildInvoiceEmailTemplate($invoice, $recipientName, $admissionData, $invoiceId, $invoiceLink);

            $this->email->setMessage($html);

            // Send email and check result
            if ($this->email->send()) {
                log_message('info', "Invoice email sent to {$recipientEmail}");

                // Clear the email like Shield does
                $this->email->clear();

                return true;
            } else {
                // Log detailed error information like Shield does
                $error = $this->email->printDebugger(['headers']);
                log_message('error', "Failed to send invoice email to {$recipientEmail}: " . $error);
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Email service exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send payment received notification
     */
    public function sendPaymentReceivedNotification($recipientEmail, $recipientName, $paymentData = [])
    {
        try {
            $this->email->setFrom($this->emailConfig->fromEmail, $this->emailConfig->fromName);
            $this->email->setTo($recipientEmail);
            $this->email->setSubject('Payment Received - Admissions Approved');

            $html = $this->buildPaymentReceivedEmailTemplate($recipientName, $paymentData);

            $this->email->setMessage($html);

            if ($this->email->send()) {
                log_message('info', "Payment confirmation email sent to {$recipientEmail}");

                // Clear the email like Shield does
                $this->email->clear();

                return true;
            } else {
                log_message('error', "Failed to send payment confirmation email: " . $this->email->printDebugger(['headers']));
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
    private function buildInvoiceEmailTemplate($invoice, $recipientName, $admissionData, $invoiceId = null, $invoiceLink = null)
    {
        $logo = base_url('assets/images/logo.png'); // Adjust path as needed
        $appName = 'SOSCT';
        $dueDate = date('F j, Y', strtotime($invoice['due_date'] ?? date('Y-m-d')));
        $amount = number_format($invoice['amount'] ?? 0, 2);
        $regNumber = $admissionData['registration_number'] ?? 'N/A';
        $programTitle = $admissionData['program_title'] ?? 'Program';

        // Use provided secure invoice link or generate fallback
        $invoiceLink = $invoiceLink ?: ($invoiceId ? base_url('invoice/public/' . $invoiceId) : base_url('payment/invoice'));

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
                        <a href='{$invoiceLink}' class='btn btn-primary'>View Full Invoice</a>
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
                    <strong>SOSCT Admissions Team</strong></p>
                </div>

                <div class='footer'>
                    <p>This is an automated email. Please do not reply to this message.</p>
                    <p>&copy; " . date('Y') . " SOSCT. All rights reserved.</p>
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
        $appName = 'SOSCT';
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
                    <strong>SOSCT Admissions Team</strong></p>
                </div>

                <div class='footer'>
                    <p>This is an automated email. Please do not reply to this message.</p>
                    <p>&copy; " . date('Y') . " SOSCT. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
