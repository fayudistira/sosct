# Invoice QR Code Troubleshooting Guide

## Issue
Controller or method not found error when accessing QR code or public invoice view.

## Solution Applied

### 1. Routes Order Fixed
**Problem**: Public routes were placed after grouped routes, causing route matching issues.

**Solution**: Moved public routes to the TOP of the routes file, before any grouped routes.

**File**: `app/Modules/Payment/Config/Routes.php`

```php
// Public Invoice Routes (MUST be before grouped routes)
$routes->get('invoice/public/(:segment)', '\Modules\Payment\Controllers\InvoiceController::publicView/$1');
$routes->get('invoice/qr/(:segment)', '\Modules\Payment\Controllers\InvoiceController::generateQr/$1');

// Then grouped routes follow...
$routes->group('invoice', [...], function($routes) {
    // ...
});
```

### 2. QR Code Library API Updated
**Problem**: Using old QR code API that doesn't match version 6.0.9.

**Solution**: Updated to use Builder pattern.

**File**: `app/Modules/Payment/Controllers/InvoiceController.php`

```php
// Old (doesn't work with v6.0.9)
$qrCode = new QrCode($publicUrl);
$qrCode->setSize(300);
$writer = new PngWriter();
$result = $writer->write($qrCode);

// New (works with v6.0.9)
$result = \Endroid\QrCode\Builder\Builder::create()
    ->data($publicUrl)
    ->size(300)
    ->margin(10)
    ->build();
```

### 3. Removed Unnecessary Use Statements
Removed old QR code use statements since we're using Builder pattern with full namespace.

## Testing

### Test URLs
Replace `{id}` with an actual invoice ID (e.g., 1, 2, 3):

1. **Public Invoice View**: `http://localhost/feecs/invoice/public/{id}`
2. **QR Code Image**: `http://localhost/feecs/invoice/qr/{id}`

### Quick Test File
Created `test_invoice_qr.php` in root directory for easy testing:
```
http://localhost/feecs/test_invoice_qr.php
```

### Verify Routes
Check if routes are registered:
```bash
php spark routes | Select-String "invoice"
```

Should show:
```
GET | invoice/public/([^/]+) | \Modules\Payment\Controllers\InvoiceController::publicView/$1
GET | invoice/qr/([^/]+)     | \Modules\Payment\Controllers\InvoiceController::generateQr/$1
```

## Common Issues

### Issue 1: 404 Not Found
**Cause**: Routes not loaded or in wrong order
**Solution**: 
- Check routes are at TOP of Routes.php
- Run `composer dump-autoload`
- Clear cache if using production mode

### Issue 2: QR Code Shows Error
**Cause**: QR code library API mismatch
**Solution**: 
- Verify using Builder pattern (see code above)
- Check library version: `composer show endroid/qr-code`
- Should be version 6.0.9

### Issue 3: Controller Not Found
**Cause**: Namespace issues
**Solution**:
- Use full namespace with leading backslash: `\Modules\Payment\Controllers\InvoiceController`
- Don't use namespace in route group for public routes

### Issue 4: Public View Requires Login
**Cause**: Routes inside session filter group
**Solution**:
- Public routes MUST be outside any filter groups
- Place them before grouped routes

## Verification Checklist

- [ ] Routes are at TOP of Routes.php file
- [ ] Routes use full namespace with leading backslash
- [ ] Routes are NOT inside any filter group
- [ ] QR code uses Builder pattern
- [ ] Composer autoload is up to date
- [ ] Test URLs work without login
- [ ] QR code image displays
- [ ] Scanning QR code opens public view

## Files Modified

1. `app/Modules/Payment/Config/Routes.php` - Routes order and format
2. `app/Modules/Payment/Controllers/InvoiceController.php` - QR code API
3. `test_invoice_qr.php` - Test file (can be deleted after testing)

## Expected Behavior

### QR Code Generation (`/invoice/qr/{id}`)
- Returns PNG image
- 300x300 pixels
- 10px margin
- No authentication required
- Contains URL to public invoice view

### Public Invoice View (`/invoice/public/{id}`)
- Shows invoice details
- Shows student information
- Shows payment history
- Professional layout
- Print button
- No authentication required
- Responsive design

## Next Steps

1. Access an invoice in admin panel: `/invoice/view/{id}`
2. Look for QR code in right sidebar
3. Click "Public View" link to test
4. Scan QR code with mobile device
5. Verify it opens public invoice page

## Support

If issues persist:
1. Check PHP error logs
2. Check CodeIgniter logs in `writable/logs/`
3. Verify database has invoices: `php spark db:table invoices --limit 1`
4. Test with different invoice IDs
5. Clear browser cache
