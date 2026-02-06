# Invoice Creation Fix - Status Validation Issue Resolved

**Date**: February 6, 2026  
**Issue**: Invoice records were not created during admission submission
**Root Cause**: Invalid invoice status value `'unpaid'` (not in database enum)
**Fix**: Changed to valid status `'outstanding'`

---

## Problem Analysis

### Symptoms

- Invoices were not being created when admissions were submitted
- An admission was created successfully, but no invoice
- No error messages in the UI (silently failing)

### Root Cause

The invoice creation code was using an **invalid status value**:

```php
// WRONG - Status 'unpaid' does not exist in database
$invoiceData = [
    'status' => 'unpaid'  // ✗ INVALID
];
```

The database migration defined valid invoice statuses as:

- `'outstanding'` - Invoice awaiting payment
- `'paid'` - Invoice paid in full
- `'cancelled'` - Invoice cancelled
- `'expired'` - Invoice past due date (expired)
- `'partially_paid'` - Invoice partially paid

The specification originally used `'unpaid'`, but the actual database implementation uses `'outstanding'` instead.

---

## Solution

### Changed: Invalid Status Value

**File**: [app/Modules/Frontend/Controllers/PageController.php](app/Modules/Frontend/Controllers/PageController.php#L368)

**Before** (Line 368):

```php
$invoiceData = [
    // ... other fields ...
    'status' => 'unpaid'  // ✗ INVALID - does not exist in enum
];
```

**After**:

```php
$invoiceData = [
    // ... other fields ...
    'status' => 'outstanding'  // ✓ CORRECT - valid enum value
];
```

This is the **primary fix** that enables invoice creation.

---

## Previous Changes (Still Applied)

### 1. Enhanced PageController::submitApplication Logging

**File**: [app/Modules/Frontend/Controllers/PageController.php](app/Modules/Frontend/Controllers/PageController.php)

Added comprehensive debug logging to track invoice creation:

- Logs program fees (registration_fee, tuition_fee, discount)
- Logs total invoice amount calculation
- Logs invoice creation data before attempting insert
- Logs success/failure with detailed error messages

**Lines 347-387**: Invoice creation with enhanced logging

```php
$invoiceModel->createInvoice($invoiceData);

if (!$invoiceId) {
    log_message('error', '[Frontend Apply] Invoice creation FAILED. Errors: ...');
} else {
    log_message('error', '[Frontend Apply] Invoice created successfully with ID: ...');
}
```

### 2. Enhanced Success Page Invoice Display

**File**: [app/Modules/Frontend/Views/apply_success.php](app/Modules/Frontend/Views/apply_success.php)

Added "View Invoice" links next to each invoice:

**Lines 165-173**: Added link to public invoice viewer

```php
<a href="<?= base_url('invoice/public/' . $inv['id']) ?>" target="_blank">
    <i class="bi bi-eye me-1"></i><small>View Invoice</small>
</a>
```

### 3. Improved applySuccess Invoice Retrieval

**File**: [app/Modules/Frontend/Controllers/PageController.php](app/Modules/Frontend/Controllers/PageController.php)

Changed invoice filtering to show all invoices (not just unpaid):

**Lines 413-437**: Updated query to retrieve all invoices

```php
$invoices = $invoiceModel->where('registration_number', $registrationNumber)
    ->where('deleted_at IS NULL', null, false)
    ->orderBy('created_at', 'DESC')
    ->findAll();
```

**Why**: This ensures all invoices are visible on the success page, regardless of payment status

---

## How Invoice Creation Works

### Step 1: Form Submission

- **URL**: `/apply` (GET) - Display form
- **URL**: `/apply/submit` (POST) - Submit form

### Step 2: PageController::submitApplication Processing

1. Validates all form fields
2. Creates Profile record
3. Creates Admission record
4. **Calculates total fees**: registration_fee + tuition_fee \* (1 - discount/100)
5. **Creates Invoice** if totalAmount > 0:
   - Uses all program fees
   - Sets status = `'outstanding'` (valid enum value)
   - Logs creation with debug information

### Step 3: Redirect Flow

- **If invoice created**: Redirects to `/invoice/public/{id}` (new invoice)
- **If no invoice**: Redirects to `/apply/success` with registration number in session

### Step 4: Success Page Display

- **URL**: `/apply/success` (automatically loaded after submit)
- **Shows**: Application confirmation with fee breakdown
- **Shows**: List of invoices with "View Invoice" links
- **Shows**: "Download/Print Invoice" button
- **Shows**: WhatsApp confirmation option

---

## Public Invoice Viewing

### Route Configuration

**File**: [app/Modules/Payment/Config/Routes.php](app/Modules/Payment/Config/Routes.php)

```php
// Public Invoice Routes (no authentication required)
$routes->get('invoice/public/(:segment)', '\Modules\Payment\Controllers\InvoiceController::publicView/$1');
```

### InvoiceController::publicView Method

**File**: [app/Modules/Payment/Controllers/InvoiceController.php](app/Modules/Payment/Controllers/InvoiceController.php)

The publicView method:

1. Retrieves invoice by ID
2. Gets associated student/admission details
3. Displays invoice with student information
4. Printable layout (includes CSS for print styling)

---

## Invoice Status Values

| Status           | Meaning                    | When Used                                  |
| ---------------- | -------------------------- | ------------------------------------------ |
| `outstanding`    | Awaiting payment           | When invoice is first created              |
| `paid`           | Invoice paid in full       | After full payment recorded by admin       |
| `partially_paid` | Invoice partially paid     | When partial payment recorded (if enabled) |
| `cancelled`      | Invoice cancelled          | If invoice is manually cancelled           |
| `expired`        | Invoice expired (past due) | Auto-set when due_date is passed           |

### When to Use Each Status

**On Creation**: Use `'outstanding'` - invoice awaiting payment

```php
$invoiceModel->createInvoice([
    'registration_number' => $regNumber,
    'description' => 'Registration Fee',
    'amount' => 500000,
    'due_date' => date('Y-m-d', strtotime('+3 days')),
    'invoice_type' => 'registration_fee',
    'status' => 'outstanding'  // ✓ ALWAYS use this
]);
```

**On Payment**: Update to `'paid'` after payment is recorded

```php
// When payment is recorded and matches invoice amount
$invoiceModel->update($invoiceId, ['status' => 'paid']);
```

**On Expiry**: Set to `'expired'` when due_date has passed

```php
// Automatic or manual expiration
$invoiceModel->update($invoiceId, ['status' => 'expired']);
```

---

## When Invoices CAN Be Created

✓ **Admissions with ANY status**: pending, approved, rejected, withdrawn  
✓ **When totalAmount > 0**: Registration fee or tuition fee must be greater than zero  
✓ **When registration_number exists**: The admission must be saved first  
✓ **Before or after approval**: Invoices can be created regardless of admission status

---

## Invoice Creation Flow

```
User fills apply form
        ↓
Profile is created (orphaned, no user yet)
        ↓
Admission is created with status='pending'
        ↓
Calculate total fees: registration_fee + (tuition_fee * (1 - discount/100))
        ↓
If totalAmount > 0:
  Create Invoice with status='outstanding'
        ↓
Redirect to /invoice/public/{id} or /apply/success
        ↓
Guest sees invoice on success page
        ↓
Guest can pay (admin records payment)
        ↓
Admission auto-updates to status='approved'
        ↓
Guest account is created with user_id
        ↓
Admin can promote to Student
```

---

## Testing the Fix

### Quick Test

Run: `http://localhost:8080/test_complete_flow.php`

This will:

1. Create a profile
2. Create an admission with status='**pending**'
3. Create an invoice with status='**outstanding**'
4. Display success confirmation

### Expected Result

```
✓ Profile created: ID 123
✓ Admission created: ID 456 (Status: pending)
✓ Invoice created: ID 789 (Status: outstanding)
✓ COMPLETE SUCCESS!
```

### Full Application Test

1. Navigate to: `http://localhost:8080/apply`
2. Fill in the form
3. Submit
4. Should:
   - Show success page OR redirect to invoice
   - Display invoice with "View Invoice" link
   - Invoice should have status `'outstanding'`

---

### Method 1: Direct Form Submission

1. Navigate to: `http://localhost:8080/apply`
2. Select a program (all have registration_fee = 500,000 + tuition fees)
3. Fill in all required fields
4. Click "Submit Application"
5. Should redirect to invoice page or success page with invoices

### Method 2: Check Database Directly

```sql
-- View recent admissions
SELECT id, registration_number, status FROM admissions ORDER BY created_at DESC LIMIT 5;

-- View recent invoices
SELECT id, registration_number, description, amount, status FROM invoices ORDER BY created_at DESC LIMIT 5;

-- Link an admission to its invoices
SELECT
    a.registration_number,
    i.invoice_number,
    i.amount,
    i.status,
    i.created_at
FROM admissions a
LEFT JOIN invoices i ON i.registration_number = a.registration_number
ORDER BY a.created_at DESC LIMIT 1;
```

### Method 3: Check Logs

```
Location: writable/logs/log-YYYY-MM-DD.log
Search for: "[Frontend Apply]" to find invoice creation logs
```

---

## Troubleshooting

### Issue: Invoice not created

**Check**:

1. Program has registration_fee > 0 (verify in database or admin)
2. Total fees calculation is correct: registration_fee + (tuition_fee \* (1 - discount/100))
3. Check logs: `writable/logs/log-YYYY-MM-DD.log` for "[Frontend Apply] Invoice creation FAILED"

### Issue: Invoices not showing on success page

**Check**:

1. registration_number is properly set in session
2. `getByRegistrationNumber()` returns admission data
3. Invoices exist in database for that registration_number
4. Your browser cookies/session is enabled

### Issue: Public invoice link returns 404

**Check**:

1. Invoice ID exists in database
2. URL is correct: `http://localhost:8080/invoice/public/{id}`
3. Invoice hasn't been soft-deleted

---

## Program Setup

All programs have fees configured:

- **registration_fee**: 500,000 (default for all programs)
- **tuition_fee**: Varies by program (2.5M - 7.5M)
- **discount**: Varies by program (0% - 15%)

Example:

- German Package: RegFee 500K + Tuition 7.5M \* 85% = 500K + 6.375M = 6.875M total

---

## Files Modified

1. **[app/Modules/Frontend/Controllers/PageController.php](app/Modules/Frontend/Controllers/PageController.php)**
   - Line 368: Changed invoice status from `'unpaid'` to `'outstanding'`
   - Lines 347-387: Added comprehensive debug logging (previous session)
   - Lines 415-437: Updated invoice retrieval on success page (previous session)

2. **[app/Modules/Frontend/Views/apply_success.php](app/Modules/Frontend/Views/apply_success.php)**
   - Lines 165-173: Added "View Invoice" links (previous session)

3. **[app/Modules/Payment/Routes/Routes.php](app/Modules/Payment/Config/Routes.php)**
   - Already has public invoice route (no changes needed)

4. **Test/Debug Files** (Created for validation):
   - `public/test_direct_invoice.php` - Direct invoice creation test (updated status)
   - `public/test_invoice_creation.php` - Full admission flow test (updated status)
   - `public/test_complete_flow.php` - **NEW** - Complete end-to-end flow test
   - `public/test_status_fix.php` - **NEW** - Status validation verification

---

## Why This Was Failing

### Database Validation

When `InvoiceModel->createInvoice()` is called, the model performs validation:

```php
// From InvoiceModel
protected $validationRules = [
    'status' => 'permit_empty|in_list[outstanding,paid,cancelled,expired,partially_paid]'
];
```

Since `'unpaid'` is not in the list, validation **silently fails**, and `createInvoice()` returns `false` or `null`.

The exception was **never thrown** because:

1. The model's `insert()` method doesn't throw exceptions for validation failures
2. It just returns false
3. The code checked `if (!$invoiceId)` but continued anyway without strict error handling

### Admission Status is NOT the Issue

The manual invoice creation UI filters to show only "approved" admissions:

```php
// This is just a UI filter, not a database constraint
$students = array_filter($students, function ($student) {
    return $student['status'] === 'approved';
});
```

But **there is no database constraint** preventing invoices from being created for pending admissions.

---

## Testing Verification Checklist

- [ ] Navigate to `/apply` form
- [ ] Fill form and submit (ensure all fields are valid)
- [ ] Check that invoice is created in database
- [ ] Verify redirect to `/invoice/public/{id}` OR `/apply/success`
- [ ] Success page displays invoice with "View Invoice" link
- [ ] Click "View Invoice" and see public invoice page
- [ ] "Download/Print Invoice" button works
- [ ] Invoice displays applicant name, program, amount, due date
- [ ] Page is printable (no print UI elements visible when printing)

---

## Next Steps

1. **Test the complete flow** by submitting a test application
2. **Check logs** at `writable/logs/` for any errors
3. **Verify database** that admission and invoice records are created
4. **Test public invoice access** from the success page
5. Report any issues with specific error messages from logs

---

**Status**: ✅ IMPLEMENTED - Invoice creation with logging and public viewing routes configured
