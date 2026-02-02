# Payment Module Seeders - Usage Instructions

## Overview
Two seeders have been created to populate test data for the Payment Management module:
- **InvoiceSeeder**: 25 invoice records
- **PaymentSeeder**: 25 payment records

## Running the Seeders

### Option 1: Run Individual Seeders

Run the invoice seeder:
```bash
php spark db:seed InvoiceSeeder
```

Run the payment seeder:
```bash
php spark db:seed PaymentSeeder
```

### Option 2: Run All Seeders at Once

If you want to run all seeders including admissions, create a main seeder file or run them sequentially:
```bash
php spark db:seed AdmissionSeeder
php spark db:seed InvoiceSeeder
php spark db:seed PaymentSeeder
```

## Test Data Summary

### Invoice Records (25 total)
- **Registration Fees**: 10 records (mix of paid, unpaid, cancelled)
- **Tuition Fees**: 7 records (various programs, mix of paid/unpaid)
- **Miscellaneous Fees**: 8 records (lab fees, insurance, library, etc.)

**Status Distribution**:
- Unpaid: 14 invoices (including some overdue for testing)
- Paid: 10 invoices
- Cancelled: 1 invoice

**Invoice Numbers**: INV-2026-0001 through INV-2026-0025

### Payment Records (25 total)
- **Paid Payments**: 12 records (linked to invoices)
- **Pending Payments**: 10 records (awaiting confirmation)
- **Failed Payments**: 2 records (with failure reasons)
- **Refunded Payments**: 2 records (with refund dates and reasons)

**Payment Methods**:
- Bank Transfer: 19 payments
- Cash: 6 payments

**Document Numbers**: TRF-2026-0001 through TRF-2026-0019, CASH-2026-0001 through CASH-2026-0006

## Data Relationships

The seeders create realistic relationships:
- Payments are linked to invoices via `invoice_id` (where applicable)
- All records reference existing student registration numbers (REG-2026-0001 through REG-2026-0020)
- Some payments are not linked to invoices (direct payments for textbooks, uniforms, etc.)
- Invoice statuses match payment statuses (paid invoices have corresponding paid payments)

## Testing Scenarios

The seeded data supports testing of:
1. **Overdue Invoices**: Several invoices with due dates in the past
2. **Payment Status Transitions**: Pending, paid, failed, and refunded statuses
3. **Partial Payments**: Some payments are partial amounts for larger invoices
4. **Revenue Reports**: Mix of payment methods and invoice types
5. **Search Functionality**: Various student names, registration numbers, document numbers
6. **Filtering**: Different statuses, types, and date ranges

## Notes

- All timestamps are set to the current date/time when the seeder runs
- Invoice numbers follow the format: INV-YYYY-NNNN
- Payment document numbers follow formats: TRF-YYYY-NNNN (transfers) or CASH-YYYY-NNNN (cash)
- The data is designed to work with the existing 20 admission records from AdmissionSeeder
