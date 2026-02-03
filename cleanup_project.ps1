# Project Cleanup Script
# Removes temporary documentation and test files

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  FEECS Project Cleanup Script" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Files to delete
$markdownFilesToDelete = @(
    "ACCOUNT_MODULE_PLAN.md",
    "BULK_UPLOAD_IMPLEMENTATION_SUMMARY.md",
    "BULK_UPLOAD_QUICK_REFERENCE.md",
    "DEPLOYMENT_TROUBLESHOOTING.md",
    "FINAL_FIX_STEPS.md",
    "FRONTEND_PERFORMANCE_FIX.md",
    "FRONTEND_PROGRAMS_IMPLEMENTATION.md",
    "FRONTEND_PROGRAMS_PLAN.md",
    "INVOICE_QR_RUPIAH_UPDATE.md",
    "INVOICE_QR_TROUBLESHOOTING.md",
    "PERFORMANCE_ISSUE_RESOLVED.md",
    "PERFORMANCE_OPTIMIZATION_COMPLETE.md",
    "PROFILE_MODULE_SETUP.md",
    "PROGRAMS_CARDS_FIXED.md",
    "PROGRAMS_CATEGORY_TABS_IMPLEMENTED.md",
    "PROGRAMS_MODE_CURRICULUM_ADDED.md",
    "PROGRAMS_MODE_CURRICULUM_COMPLETE.md",
    "PROGRAMS_SEARCH_PAGINATION_ADDED.md",
    "PROGRAMS_VIEWS_UPDATED.md",
    "PROGRAM_BULK_UPLOAD_GUIDE.md",
    "PROGRAM_BULK_UPLOAD_PLAN.md",
    "PROGRAM_DETAIL_VIEW_FIXED.md",
    "QR_CODE_IMPLEMENTATION_SUMMARY.md",
    "QR_CODE_JAVASCRIPT_IMPLEMENTATION.md",
    "README_THUMBNAIL_FIX.md",
    "SEEDER_INSTRUCTIONS.md",
    "SHIELD_FILTER_FIX.md",
    "SYMLINK_SOLUTION.md",
    "THUMBNAIL_FIX_COMPLETE.md",
    "THUMBNAIL_FIX_INSTRUCTIONS.md",
    "THUMBNAIL_ISSUE_DIAGNOSIS.md",
    "UPLOADS_MOVED_TO_PUBLIC.md",
    "USERS_MODULE_SETUP.md"
)

$shellScriptsToDelete = @(
    "cleanup_debug_files.sh",
    "create_symlink.sh",
    "fix_permissions.sh",
    "quick_fix.sh"
)

$phpFilesToDelete = @(
    "debug_thumbnails.php",
    "test_invoice_qr.php",
    "test_new_columns.php",
    "test_page_load.php",
    "test_performance.php",
    "test_route.php",
    "update_existing_programs.php"
)

# Counters
$deletedCount = 0
$notFoundCount = 0
$errorCount = 0

# Function to delete file
function Remove-FileIfExists {
    param($fileName)
    
    if (Test-Path $fileName) {
        try {
            Remove-Item $fileName -Force
            Write-Host "[DELETED] $fileName" -ForegroundColor Green
            return $true
        } catch {
            Write-Host "[ERROR] Failed to delete $fileName" -ForegroundColor Red
            return $false
        }
    } else {
        Write-Host "[NOT FOUND] $fileName" -ForegroundColor Yellow
        return $null
    }
}

# Delete markdown files
Write-Host "`nDeleting temporary markdown files..." -ForegroundColor Cyan
foreach ($file in $markdownFilesToDelete) {
    $result = Remove-FileIfExists $file
    if ($result -eq $true) { $deletedCount++ }
    elseif ($result -eq $null) { $notFoundCount++ }
    else { $errorCount++ }
}

# Delete shell scripts
Write-Host "`nDeleting shell scripts..." -ForegroundColor Cyan
foreach ($file in $shellScriptsToDelete) {
    $result = Remove-FileIfExists $file
    if ($result -eq $true) { $deletedCount++ }
    elseif ($result -eq $null) { $notFoundCount++ }
    else { $errorCount++ }
}

# Delete test PHP files
Write-Host "`nDeleting test PHP files..." -ForegroundColor Cyan
foreach ($file in $phpFilesToDelete) {
    $result = Remove-FileIfExists $file
    if ($result -eq $true) { $deletedCount++ }
    elseif ($result -eq $null) { $notFoundCount++ }
    else { $errorCount++ }
}

# Summary
Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "  Cleanup Summary" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Deleted:   $deletedCount files" -ForegroundColor Green
Write-Host "Not Found: $notFoundCount files" -ForegroundColor Yellow
Write-Host "Errors:    $errorCount files" -ForegroundColor Red
Write-Host ""

if ($deletedCount -gt 0) {
    Write-Host "Cleanup completed successfully!" -ForegroundColor Green
} else {
    Write-Host "No files were deleted." -ForegroundColor Yellow
}

Write-Host ""
