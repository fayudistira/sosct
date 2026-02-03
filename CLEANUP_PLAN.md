# Project Cleanup Plan

## 📋 Files to Keep vs Delete

### ✅ KEEP - Essential Documentation

#### Core Documentation
- `README.md` - Main project readme
- `COOKBOOK.md` - Complete project documentation
- `TASK_LIST.md` - Project task tracking
- `APPLICATION_WORKFLOW.md` - Application flow documentation
- `DATA_MODEL_REFACTORING_COMPLETE.md` - Database refactoring summary
- `LANGUAGE_PROGRAMS_SEEDED.md` - Seeded programs reference

#### API & Configuration
- `API_DOCUMENTATION.md` - API reference
- `TEST_ACCOUNTS.md` - Test account credentials
- `ROLES_AND_PERMISSIONS.md` - Permission system

---

### ❌ DELETE - Temporary/Obsolete Documentation

#### Troubleshooting Docs (Issues Resolved)
- `DEPLOYMENT_TROUBLESHOOTING.md` - Deployment issues (resolved)
- `FINAL_FIX_STEPS.md` - Temporary fix steps
- `FRONTEND_PERFORMANCE_FIX.md` - Performance fix (done)
- `INVOICE_QR_TROUBLESHOOTING.md` - QR code issues (resolved)
- `PERFORMANCE_ISSUE_RESOLVED.md` - Performance fix (done)
- `PERFORMANCE_OPTIMIZATION_COMPLETE.md` - Optimization (done)
- `SHIELD_FILTER_FIX.md` - Shield fix (done)
- `THUMBNAIL_ISSUE_DIAGNOSIS.md` - Thumbnail issues (resolved)
- `THUMBNAIL_FIX_INSTRUCTIONS.md` - Fix instructions (done)
- `THUMBNAIL_FIX_COMPLETE.md` - Fix complete (done)

#### Implementation Docs (Features Complete)
- `ACCOUNT_MODULE_PLAN.md` - Account module (implemented)
- `BULK_UPLOAD_IMPLEMENTATION_SUMMARY.md` - Bulk upload (done)
- `BULK_UPLOAD_QUICK_REFERENCE.md` - Quick ref (done)
- `FRONTEND_PROGRAMS_IMPLEMENTATION.md` - Frontend (done)
- `FRONTEND_PROGRAMS_PLAN.md` - Frontend plan (done)
- `INVOICE_QR_RUPIAH_UPDATE.md` - Invoice update (done)
- `PROFILE_MODULE_SETUP.md` - Profile setup (done)
- `PROGRAMS_CARDS_FIXED.md` - Cards fix (done)
- `PROGRAMS_CATEGORY_TABS_IMPLEMENTED.md` - Tabs (done)
- `PROGRAMS_MODE_CURRICULUM_ADDED.md` - Mode/curriculum (done)
- `PROGRAMS_MODE_CURRICULUM_COMPLETE.md` - Complete (done)
- `PROGRAMS_SEARCH_PAGINATION_ADDED.md` - Search (done)
- `PROGRAMS_VIEWS_UPDATED.md` - Views updated (done)
- `PROGRAM_BULK_UPLOAD_GUIDE.md` - Guide (done)
- `PROGRAM_BULK_UPLOAD_PLAN.md` - Plan (done)
- `PROGRAM_DETAIL_VIEW_FIXED.md` - Detail view (done)
- `QR_CODE_IMPLEMENTATION_SUMMARY.md` - QR code (done)
- `QR_CODE_JAVASCRIPT_IMPLEMENTATION.md` - QR JS (done)
- `README_THUMBNAIL_FIX.md` - Thumbnail fix (done)
- `SEEDER_INSTRUCTIONS.md` - Seeder info (done)
- `SYMLINK_SOLUTION.md` - Symlink (done)
- `UPLOADS_MOVED_TO_PUBLIC.md` - Uploads moved (done)
- `USERS_MODULE_SETUP.md` - Users setup (done)

**Total to Delete**: 31 markdown files

---

### ❌ DELETE - Shell Scripts (Windows Environment)

All `.sh` files are for Linux/Mac and not needed on Windows:
- `cleanup_debug_files.sh`
- `create_symlink.sh`
- `fix_permissions.sh`
- `quick_fix.sh`

**Total to Delete**: 4 shell scripts

---

### ❌ DELETE - Test/Debug PHP Files

- `debug_thumbnails.php` - Debug script
- `test_invoice_qr.php` - Test script
- `test_new_columns.php` - Test script
- `test_page_load.php` - Test script
- `test_performance.php` - Test script
- `test_route.php` - Test script
- `update_existing_programs.php` - One-time migration script

**Total to Delete**: 7 PHP test files

---

### ✅ KEEP - Essential PHP Files

- `preload.php` - CodeIgniter preload configuration

---

## 📊 Cleanup Summary

| Category | Keep | Delete |
|----------|------|--------|
| Markdown Files | 9 | 31 |
| Shell Scripts | 0 | 4 |
| PHP Files | 1 | 7 |
| **TOTAL** | **10** | **42** |

---

## 🗂️ Recommended File Organization

After cleanup, create organized documentation structure:

```
/
├── README.md                              # Main readme
├── COOKBOOK.md                            # Complete documentation
├── TASK_LIST.md                           # Task tracking
├── APPLICATION_WORKFLOW.md                # Workflow guide
├── API_DOCUMENTATION.md                   # API reference
├── TEST_ACCOUNTS.md                       # Test credentials
├── ROLES_AND_PERMISSIONS.md               # Permissions
├── DATA_MODEL_REFACTORING_COMPLETE.md     # DB refactoring
├── LANGUAGE_PROGRAMS_SEEDED.md            # Seeded data
├── preload.php                            # CodeIgniter config
└── docs/                                  # Optional: Move docs here
    ├── cookbook.md
    ├── api.md
    └── workflow.md
```

---

## 🚀 Cleanup Script

Run the cleanup script to delete all unnecessary files:

```powershell
# Windows PowerShell
.\cleanup_project.ps1
```

Or manually delete files listed above.

---

**Created**: 2026-02-03  
**Status**: Ready for cleanup
