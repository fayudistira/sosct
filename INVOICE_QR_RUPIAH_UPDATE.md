# Invoice QR Code and Rupiah Currency Update

## Summary
Updated the invoice system to display currency in Indonesian Rupiah (Rp) format and added QR code functionality for public invoice viewing. QR codes are now displayed on public invoice views, printed documents, and PDF downloads.

## Changes Made

### 1. QR Code Library Installation
- Installed `endroid/qr-code` package via Composer (version 6.0.9)
- Dependencies: bacon/bacon-qr-code, dasprid/enum

### 2. InvoiceController Updates
**File**: `app/Modules/Payment/Controllers/InvoiceController.php`

Added three new methods:

#### `generateQr($id)`
- Generates QR code image for invoice
- QR code contains public URL to invoice
- Returns PNG image (300x300px with 10px margin)
- No authentication required

#### `publicView($id)`
- Public invoice view accessible without authentication
- Shows complete invoice details including:
  - Invoice information
  - Student information
  - Payment history
  - **QR code for easy sharing**
  - Professional layout with print functionality

### 3. New Public Invoice View
**File**: `app/Modules/Payment/Views/invoices/public_view.php`

Features:
- Standalone HTML page (no dashboard layout)
- Modern, professional design with dark red gradient theme
- Responsive layout
- Print-friendly styling with QR code included
- Shows all invoice details publicly
- Payment history table
- Status badges (unpaid, paid, cancelled)
- Currency displayed in Rupiah format
- **QR code section for easy access and sharing**
- QR code prints correctly with invoice

### 4. PDF Generator Updates
**File**: `app/Modules/Payment/Libraries/PdfGenerator.php`

New features:
- QR code embedded in PDF invoices
- QR code displayed as base64 image in PDF
- New method `generateQrCodeBase64()` for PDF embedding
- QR code shows at bottom of invoice with "Scan to View Online" text
- 150x150px QR code in PDF documents

### 4. Routes Configuration
**File**: `app/Modules/Payment/Config/Routes.php`

Added two new public routes (no authentication):
```php
$routes->get('invoice/public/(:segment)', 'InvoiceController::publicView/$1');
$routes->get('invoice/qr/(:segment)', 'InvoiceController::generateQr/$1');
```

### 5. Invoice View Updates
**File**: `app/Modules/Payment/Views/invoices/view.php`

Changes:
- Changed currency from USD ($) to Rupiah (Rp)
- Format: `Rp X.XXX` (no decimals, dot as thousand separator)
- Added QR code card in sidebar showing:
  - QR code image
  - Link to public view
  - Scan instruction text

### 6. Currency Format Updates

Updated all payment and invoice views to use Rupiah:

#### Invoice Views:
- `app/Modules/Payment/Views/invoices/view.php` - Admin view
- `app/Modules/Payment/Views/invoices/index.php` - Invoice list
- `app/Modules/Payment/Views/invoices/public_view.php` - Public view

#### Payment Views:
- `app/Modules/Payment/Views/payments/index.php` - Payment list
- `app/Modules/Payment/Views/payments/create.php` - Create form dropdown

#### PDF Generator:
- `app/Modules/Payment/Libraries/PdfGenerator.php`
  - Invoice PDF: Rupiah format
  - Receipt PDF: Rupiah format

### 7. Currency Format Specification

**Old Format**: `$X,XXX.XX` (USD with 2 decimals)
**New Format**: `Rp X.XXX` (Rupiah with no decimals)

PHP Implementation:
```php
// Old
number_format($amount, 2)  // Output: 1,500.00

// New
number_format($amount, 0, ',', '.')  // Output: 1.500
```

## QR Code Display Locations

The QR code now appears in **three places**:

### 1. Admin Invoice View (`/invoice/view/{id}`)
- QR code in right sidebar
- Link to public view
- For staff to share with students

### 2. Public Invoice View (`/invoice/public/{id}`)
- QR code displayed at bottom of page
- Prints with the invoice
- For easy sharing and verification
- No login required

### 3. PDF Invoice Download (`/invoice/pdf/{id}`)
- QR code embedded in PDF
- 150x150px at bottom of document
- "Scan to View Online" label
- Permanent part of PDF file

## Usage

### Viewing Invoice with QR Code
1. Navigate to invoice detail page: `/invoice/view/{id}`
2. QR code is displayed in the right sidebar
3. Scan QR code with mobile device
4. Opens public invoice view automatically

### Public Invoice Access
- Direct URL: `http://yourdomain.com/invoice/public/{id}`
- No authentication required
- Shows complete invoice information
- Print-friendly layout

### QR Code Image
- Direct URL: `http://yourdomain.com/invoice/qr/{id}`
- Returns PNG image
- Can be embedded in emails or documents

## Security Considerations

1. **Public Access**: Invoice public view is intentionally accessible without authentication
2. **Data Exposure**: Only invoice and related student data is shown (no sensitive admin data)
3. **No Modification**: Public view is read-only
4. **QR Code**: Contains only the public URL, no sensitive data embedded

## Testing Checklist

- [x] QR code library installed successfully
- [x] QR code generates correctly for invoices
- [x] Public invoice view displays all information
- [x] Currency displays as Rupiah in all views
- [x] PDF invoices show Rupiah currency
- [x] PDF receipts show Rupiah currency
- [x] Payment list shows Rupiah
- [x] Invoice list shows Rupiah
- [x] Public view is accessible without login
- [x] QR code scans and opens public view
- [x] Print functionality works on public view

## Files Modified

1. `composer.json` - Added endroid/qr-code dependency
2. `app/Modules/Payment/Controllers/InvoiceController.php` - Added QR and public view methods
3. `app/Modules/Payment/Config/Routes.php` - Added public routes
4. `app/Modules/Payment/Views/invoices/view.php` - Added QR code card, changed currency
5. `app/Modules/Payment/Views/invoices/index.php` - Changed currency
6. `app/Modules/Payment/Views/payments/index.php` - Changed currency
7. `app/Modules/Payment/Views/payments/create.php` - Changed currency
8. `app/Modules/Payment/Libraries/PdfGenerator.php` - Changed currency in PDFs

## Files Created

1. `app/Modules/Payment/Views/invoices/public_view.php` - New public invoice view

## Dependencies

- PHP 8.1+
- endroid/qr-code ^6.0
- bacon/bacon-qr-code ^3.0
- dasprid/enum ^1.0

## Future Enhancements

Potential improvements:
- Email invoice with QR code
- SMS notification with public link
- WhatsApp share button
- Download invoice as image
- Multi-language support for public view
- Custom QR code styling (colors, logo)
