# Implementation Plan: Payment Management Module

## Overview

This implementation plan breaks down the Payment Management module into discrete, incremental tasks. Each task builds on previous work, with testing integrated throughout to catch errors early. The module follows CodeIgniter 4 HMVC patterns and integrates with the existing ERP system architecture.

## Tasks

- [ ] 1. Create database migrations and models
  - [x] 1.1 Create invoices table migration
    - Create migration file: `app/Database/Migrations/2026_02_01_000001_create_invoices_table.php`
    - Define schema: id, invoice_number (unique), registration_number (FK), description, amount, due_date, invoice_type, status, timestamps, soft delete
    - Add indexes on registration_number, invoice_number, status, due_date
    - _Requirements: 2.1, 2.2, 11.1, 11.2_
  
  - [x] 1.2 Create payments table migration
    - Create migration file: `app/Database/Migrations/2026_02_01_000002_create_payments_table.php`
    - Define schema: id, registration_number (FK), invoice_id (FK nullable), amount, payment_method, document_number, payment_date, receipt_file, status, failure_reason, refund_date, refund_reason, notes, timestamps, soft delete
    - Add indexes on registration_number, payment_date, status, payment_method
    - Add foreign key to invoices table with ON DELETE SET NULL
    - _Requirements: 1.1, 1.3, 1.4, 3.1, 11.1, 11.2_
  
  - [x] 1.3 Create InvoiceModel
    - Create `app/Modules/Payment/Models/InvoiceModel.php`
    - Extend CodeIgniter Model with table='invoices'
    - Define validation rules for all fields
    - Implement generateInvoiceNumber() method (format: INV-YYYY-NNNN)
    - Implement createInvoice() method with auto-generated invoice number
    - _Requirements: 2.1, 2.2, 2.3_
  
  - [x] 1.4 Create PaymentModel
    - Create `app/Modules/Payment/Models/PaymentModel.php`
    - Extend CodeIgniter Model with table='payments'
    - Define validation rules for all fields
    - Implement createPayment() method
    - _Requirements: 1.1, 1.2, 1.3_

- [ ] 2. Implement core invoice functionality
  - [x] 2.1 Add invoice query methods to InvoiceModel
    - Implement getInvoicesByStudent($registrationNumber)
    - Implement getOverdueInvoices() - filter by status='unpaid' and due_date < today
    - Implement searchInvoices($keyword) - search by invoice_number or student name
    - Implement filterInvoices($filters) - support status, type, date range filters
    - _Requirements: 2.6, 6.1, 6.2, 9.7_
  
  - [x] 2.2 Add invoice status management to InvoiceModel
    - Implement updateInvoiceStatus($id, $status)
    - Implement linkPaymentToInvoice($invoiceId, $paymentId)
    - Implement getInvoiceWithPayments($id) - join with payments table
    - _Requirements: 2.4, 2.6_
  
  - [ ]* 2.3 Write property test for invoice number uniqueness
    - **Property 6: Invoice Number Uniqueness**
    - Generate multiple invoices, verify all invoice_numbers are unique
    - **Validates: Requirements 2.1**
  
  - [ ]* 2.4 Write property test for invoice initial status
    - **Property 8: Invoice Initial Status**
    - For any newly created invoice, verify status is "unpaid"
    - **Validates: Requirements 2.3**
  
  - [ ]* 2.5 Write property test for invoice status update on payment
    - **Property 9: Invoice Status Update on Payment**
    - Create invoice, link payment, verify status changes to "paid"
    - **Validates: Requirements 2.4**

- [ ] 3. Implement core payment functionality
  - [x] 3.1 Add payment query methods to PaymentModel
    - Implement getPaymentsByStudent($registrationNumber)
    - Implement getPaymentsByDateRange($startDate, $endDate)
    - Implement searchPayments($keyword) - search by student name, registration_number, document_number
    - Implement filterPayments($filters) - support status, method, date range filters
    - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5, 9.6_
  
  - [x] 3.2 Add payment status management to PaymentModel
    - Implement updatePaymentStatus($id, $status, $additionalData)
    - Handle failure_reason for status='failed'
    - Handle refund_date and refund_reason for status='refunded'
    - Validate status transitions (prevent refunded → pending)
    - _Requirements: 3.2, 3.3, 3.4, 3.5_
  
  - [x] 3.3 Add file upload handling to PaymentModel
    - Implement uploadReceiptFile($file) method
    - Validate file format (PDF, JPG, JPEG, PNG)
    - Validate file size (max 2MB)
    - Store files in writable/uploads/receipts/ directory
    - Return file path for database storage
    - _Requirements: 1.4, 4.4, 4.5_
  
  - [ ]* 3.4 Write property test for payment data persistence
    - **Property 1: Payment Data Persistence**
    - Create payment with random valid data, retrieve it, verify all fields match
    - **Validates: Requirements 1.1, 1.5**
  
  - [ ]* 3.5 Write property test for student registration validation
    - **Property 2: Student Registration Validation**
    - Test with valid and invalid registration numbers, verify only valid ones accepted
    - **Validates: Requirements 1.2**
  
  - [ ]* 3.6 Write property test for payment status transitions
    - **Property 15: Invalid Status Transitions**
    - Attempt to change status from "refunded" to "pending", verify rejection
    - **Validates: Requirements 3.5**

- [ ] 4. Implement statistics and reporting methods
  - [x] 4.1 Add dashboard statistics methods to PaymentModel
    - Implement getDashboardStatistics($startDate, $endDate)
    - Calculate total revenue (sum of paid payments in date range)
    - Count pending payments
    - Count completed payments in date range
    - Count overdue invoices
    - _Requirements: 7.1, 7.2, 7.3, 7.4_
  
  - [x] 4.2 Add revenue breakdown methods to PaymentModel
    - Implement getRevenueByMethod() - group by payment_method, sum amounts
    - Implement getRevenueByType() - join with invoices, group by invoice_type, sum amounts
    - Implement getMonthlyRevenueTrend($year) - group by month, sum amounts
    - _Requirements: 7.5, 7.6, 10.5_
  
  - [x] 4.3 Add report generation methods to PaymentModel
    - Implement getOverduePayments() - invoices with status='unpaid' and due_date < today
    - Implement getRefundedPayments() - payments with status='refunded'
    - Implement exportToCSV($data) - convert array to CSV format
    - _Requirements: 6.1, 10.4, 10.6_
  
  - [ ]* 4.4 Write property test for revenue calculation
    - **Property 26: Revenue Calculation**
    - Create payments in date range, verify total revenue equals sum of paid amounts
    - **Validates: Requirements 7.1**
  
  - [ ]* 4.5 Write property test for overdue invoice identification
    - **Property 22: Overdue Invoice Identification**
    - Create invoices with various due dates, verify correct ones identified as overdue
    - **Validates: Requirements 6.1**

- [x] 5. Checkpoint - Ensure models and business logic work correctly
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 6. Create PDF generation library
  - [x] 6.1 Set up PDF library and create PdfGenerator class
    - Install TCPDF or Dompdf via Composer
    - Create `app/Modules/Payment/Libraries/PdfGenerator.php`
    - Implement constructor with PDF library initialization
    - Implement applyTheme($pdf) method - apply dark red gradient (#8B0000 to #6B0000)
    - _Requirements: 5.4_
  
  - [x] 6.2 Implement invoice PDF generation
    - Implement generateInvoicePdf($invoiceData) method
    - Create PDF template with: invoice number, student info, amount, due date, description
    - Apply system theme to header and borders
    - Save PDF to writable/uploads/invoices/ directory
    - Return file path
    - _Requirements: 5.2, 5.3, 5.5_
  
  - [x] 6.3 Implement receipt PDF generation
    - Implement generateReceiptPdf($paymentData) method
    - Create PDF template with: receipt number, payment date, amount, method, student info
    - Apply system theme to header and borders
    - Save PDF to writable/uploads/receipts/ directory
    - Return file path
    - _Requirements: 5.1, 5.3, 5.5_
  
  - [ ]* 6.4 Write property test for PDF content completeness
    - **Property 20: PDF Content Completeness**
    - Generate invoice/receipt PDF, parse content, verify all required fields present
    - **Validates: Requirements 5.3**

- [ ] 7. Create Payment API endpoints
  - [x] 7.1 Create PaymentApiController with basic CRUD
    - Create `app/Modules/Payment/Controllers/Api/PaymentApiController.php`
    - Extend ResourceController with format='json'
    - Implement index() - list payments with pagination
    - Implement show($id) - get single payment with student and invoice details
    - Implement create() - create payment with validation
    - Implement update($id) - update payment with validation
    - _Requirements: 8.1, 8.2, 8.3, 8.4_
  
  - [x] 7.2 Add payment status update endpoint
    - Implement updateStatus($id) method in PaymentApiController
    - Accept status, failure_reason, refund_date, refund_reason in request body
    - Validate status transitions
    - Return updated payment
    - _Requirements: 8.5_
  
  - [x] 7.3 Add payment search and filter endpoints
    - Implement search() method - query param 'q'
    - Implement filterByStatus() method - query param 'status'
    - Implement filterByMethod() method - query param 'method'
    - Implement filterByDateRange() method - query params 'start_date', 'end_date'
    - Implement getByStudent($registrationNumber) method
    - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5, 9.6_
  
  - [x] 7.4 Add payment statistics endpoint
    - Implement statistics() method in PaymentApiController
    - Accept start_date and end_date query params
    - Call PaymentModel->getDashboardStatistics()
    - Return revenue totals, counts, breakdowns
    - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5, 7.6_
  
  - [x] 7.5 Add receipt file upload endpoint
    - Implement uploadReceipt($id) method in PaymentApiController
    - Accept multipart/form-data with 'receipt_file' field
    - Validate file format and size
    - Call PaymentModel->uploadReceiptFile()
    - Update payment record with file path
    - Return updated payment
    - _Requirements: 1.4, 4.4, 4.5_
  
  - [ ]* 7.6 Write property test for API JSON response format
    - **Property 32: JSON Response Format**
    - Call various endpoints, verify all return valid JSON with "status" field
    - **Validates: Requirements 8.1**
  
  - [ ]* 7.7 Write property test for validation error responses
    - **Property 39: Validation Error Responses**
    - Send invalid data to endpoints, verify HTTP 422 with error details
    - **Validates: Requirements 8.10**

- [ ] 8. Create Invoice API endpoints
  - [x] 8.1 Create InvoiceApiController with basic CRUD
    - Create `app/Modules/Payment/Controllers/Api/InvoiceApiController.php`
    - Extend ResourceController with format='json'
    - Implement index() - list invoices with pagination
    - Implement show($id) - get single invoice with student and payment details
    - Implement create() - create invoice with auto-generated invoice_number
    - Implement update($id) - update invoice with validation
    - Implement delete($id) - soft delete invoice
    - _Requirements: 8.1, 8.6, 8.7_
  
  - [x] 8.2 Add invoice search and filter endpoints
    - Implement search() method - query param 'q'
    - Implement filterByStatus() method - query param 'status'
    - Implement filterByType() method - query param 'type'
    - Implement getByStudent($registrationNumber) method
    - Implement getOverdue() method - return overdue invoices with days_overdue
    - _Requirements: 6.1, 6.2, 6.3, 9.7_
  
  - [x] 8.3 Add invoice PDF generation endpoint
    - Implement generatePdf($id) method in InvoiceApiController
    - Get invoice data with student details
    - Call PdfGenerator->generateInvoicePdf()
    - Return PDF file with appropriate headers (Content-Type: application/pdf)
    - _Requirements: 5.2, 8.8_
  
  - [x] 8.4 Add invoice cancellation endpoint
    - Implement cancel($id) method in InvoiceApiController
    - Update invoice status to 'cancelled'
    - Return updated invoice
    - _Requirements: 2.4_
  
  - [ ]* 8.5 Write property test for invoice retrieval round trip
    - **Property 37: Invoice Retrieval Round Trip**
    - Create invoice via API, retrieve by ID, verify data matches
    - **Validates: Requirements 8.7**

- [ ] 9. Configure routes for Payment module
  - [x] 9.1 Create module routes configuration
    - Create `app/Modules/Payment/Config/Routes.php`
    - Define API routes for PaymentApiController (all CRUD + custom endpoints)
    - Define API routes for InvoiceApiController (all CRUD + custom endpoints)
    - Use resource routes where applicable
    - Add custom routes for search, filter, statistics, PDF generation
    - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5, 8.6, 8.7, 8.8, 8.9_

- [ ] 10. Implement authentication and authorization
  - [x] 10.1 Add authentication middleware to routes
    - Apply CodeIgniter Shield auth filter to all Payment module routes
    - Configure in Routes.php using filter('auth')
    - _Requirements: 12.1, 12.2_
  
  - [ ]* 10.2 Write property test for authentication requirement
    - **Property 51: Authentication Requirement**
    - Make requests without auth credentials, verify HTTP 401 responses
    - **Validates: Requirements 12.2**

- [ ] 11. Create web UI controllers and views
  - [x] 11.1 Create PaymentController for web UI
    - Create `app/Modules/Payment/Controllers/PaymentController.php`
    - Implement index() - display payment list with filters
    - Implement create() - show payment creation form
    - Implement store() - handle form submission
    - Implement edit($id) - show payment edit form
    - Implement update($id) - handle edit form submission
    - Implement view($id) - show payment details
    - _Requirements: 1.1, 1.5_
  
  - [x] 11.2 Create InvoiceController for web UI
    - Create `app/Modules/Payment/Controllers/InvoiceController.php`
    - Implement index() - display invoice list with filters
    - Implement create() - show invoice creation form
    - Implement store() - handle form submission
    - Implement edit($id) - show invoice edit form
    - Implement update($id) - handle edit form submission
    - Implement view($id) - show invoice details
    - _Requirements: 2.1, 2.2, 2.6_
  
  - [x] 11.3 Create payment views
    - Create `app/Modules/Payment/Views/payments/index.php` - list with search/filter
    - Create `app/Modules/Payment/Views/payments/create.php` - form with file upload
    - Create `app/Modules/Payment/Views/payments/edit.php` - edit form
    - Create `app/Modules/Payment/Views/payments/view.php` - details view
    - Apply dark red gradient theme (#8B0000 to #6B0000)
    - _Requirements: 1.1, 1.4, 9.1, 9.2, 9.3, 9.4, 9.5, 9.6_
  
  - [x] 11.4 Create invoice views
    - Create `app/Modules/Payment/Views/invoices/index.php` - list with search/filter
    - Create `app/Modules/Payment/Views/invoices/create.php` - creation form
    - Create `app/Modules/Payment/Views/invoices/edit.php` - edit form
    - Create `app/Modules/Payment/Views/invoices/view.php` - details view with PDF download
    - Apply dark red gradient theme
    - _Requirements: 2.1, 2.2, 2.6, 9.7_

- [ ] 12. Create reports and dashboard integration
  - [x] 12.1 Create report views
    - Create `app/Modules/Payment/Views/reports/revenue.php` - revenue report with filters
    - Create `app/Modules/Payment/Views/reports/overdue.php` - overdue invoices report
    - Add CSV export buttons
    - Apply dark red gradient theme
    - _Requirements: 10.1, 10.2, 10.3, 10.4, 10.5, 10.6_
  
  - [x] 12.2 Add dashboard widget for payment statistics
    - Create method in PaymentModel to provide dashboard data
    - Integrate with Dashboard module to display payment statistics
    - Show: total revenue, pending count, completed count, overdue count
    - Show revenue breakdown charts (by method, by type)
    - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5, 7.6_

- [ ] 13. Final checkpoint - Integration testing and documentation
  - [ ] 13.1 Run all property-based tests
    - Execute all 52 property tests with 100+ iterations each
    - Verify all tests pass
    - _Requirements: All_
  
  - [ ]* 13.2 Write integration tests
    - Test complete payment workflow: create invoice → record payment → generate receipt
    - Test search and filter combinations
    - Test dashboard statistics accuracy
    - Test PDF generation end-to-end
  
  - [x] 13.3 Update API documentation
    - Add Payment API endpoints to API_DOCUMENTATION.md
    - Include request/response examples for all endpoints
    - Document error responses
    - Add code examples in JavaScript, PHP, Python
    - _Requirements: 8.1 through 8.10_
  
  - [x] 13.4 Final verification
    - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation
- Property tests validate universal correctness properties (minimum 100 iterations each)
- Unit tests validate specific examples and edge cases
- File uploads stored in writable/uploads/receipts/ and writable/uploads/invoices/
- All API endpoints return JSON with consistent format
- Authentication required for all endpoints via CodeIgniter Shield
- Database migrations in app/Database/Migrations/ (not in module directory)
