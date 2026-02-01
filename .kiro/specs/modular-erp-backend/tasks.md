# Tasks: Modular ERP Backend

## 1. Database Setup
- [x] 1.1 Create admissions table migration
- [x] 1.2 Run migrations
- [x] 1.3 Create upload directories (writable/uploads/admissions/photos and documents)
- [x] 1.4 Update MakeModule command to generate Menu.php config file

## 2. Frontend Module
- [x] 2.1 Create Frontend module structure using make:module command
- [x] 2.2 Create PageController
  - [x] 2.2.1 Implement home() method
  - [x] 2.2.2 Implement about() method
  - [x] 2.2.3 Implement contact() method
  - [x] 2.2.4 Implement apply() method
  - [x] 2.2.5 Implement submitApplication() method with file upload handling
  - [x] 2.2.6 Implement applySuccess() method
- [x] 2.3 Create Frontend views
  - [x] 2.3.1 Create layout.php (shared layout)
  - [x] 2.3.2 Create home.php
  - [x] 2.3.3 Create about.php
  - [x] 2.3.4 Create contact.php
  - [x] 2.3.5 Create apply.php (admission form with all fields and file uploads)
  - [x] 2.3.6 Create apply_success.php (show registration number)
- [x] 2.4 Configure Frontend routes
- [x] 2.5 Test Frontend module functionality

## 3. Dashboard Module
- [x] 3.1 Create Dashboard module structure using make:module command
- [x] 3.2 Create menu_helper.php
  - [x] 3.2.1 Implement render_menu() function
  - [x] 3.2.2 Implement is_active_menu() function
- [x] 3.3 Create DashboardController
  - [x] 3.3.1 Implement index() method
  - [x] 3.3.2 Implement loadModuleMenus() method
  - [x] 3.3.3 Implement hasMenuPermission() method
- [x] 3.4 Create Dashboard Menu.php config
- [x] 3.5 Create Dashboard views
  - [x] 3.5.1 Create layout.php (authenticated layout with dynamic sidebar menu)
  - [x] 3.5.2 Create index.php (dashboard home)
- [x] 3.6 Configure Dashboard routes with session filter
- [x] 3.7 Test Dashboard module functionality
- [x] 3.8 Test menu filtering based on permissions

## 4. Admission Module - Model
- [x] 4.1 Create Admission module structure using make:module command
- [x] 4.2 Create AdmissionModel
  - [x] 4.2.1 Define table properties and allowed fields
  - [x] 4.2.2 Define validation rules
  - [x] 4.2.3 Implement generateRegistrationNumber() method
  - [x] 4.2.4 Implement getWithPagination() method
  - [x] 4.2.5 Implement searchAdmissions() method
  - [x] 4.2.6 Implement filterByStatus() method
  - [x] 4.2.7 Implement getStatusCounts() method

## 5. Admission Module - Web Controller
- [x] 5.1 Create AdmissionController
  - [x] 5.1.1 Implement index() method (list with pagination and stats)
  - [x] 5.1.2 Implement view() method (show details with file links)
  - [x] 5.1.3 Implement create() method (show form)
  - [x] 5.1.4 Implement store() method (save with file uploads)
  - [x] 5.1.5 Implement edit() method (show edit form)
  - [x] 5.1.6 Implement update() method (update with file handling)
  - [x] 5.1.7 Implement delete() method (soft delete)
  - [x] 5.1.8 Implement search() method
  - [x] 5.1.9 Implement downloadDocument() method

## 6. Admission Module - Views
- [x] 6.1 Create Admission views
  - [x] 6.1.1 Create index.php (list with search, filter, pagination)
  - [x] 6.1.2 Create view.php (detail view with photo and document downloads)
  - [x] 6.1.3 Create create.php (form for manual entry)
  - [x] 6.1.4 Create edit.php (edit form with current data)

## 7. Admission Module - API Controller
- [x] 7.1 Create Api subdirectory in Controllers
- [x] 7.2 Create AdmissionApiController
  - [x] 7.2.1 Implement index() method (list with pagination)
  - [x] 7.2.2 Implement show() method (get single record)
  - [x] 7.2.3 Implement create() method (create new)
  - [x] 7.2.4 Implement update() method (update existing)
  - [x] 7.2.5 Implement delete() method (soft delete)
  - [x] 7.2.6 Implement search() method
  - [x] 7.2.7 Implement filter() method

## 8. Admission Module - Routes
- [x] 8.1 Configure web routes with permission filter
- [x] 8.2 Configure API routes with tokens filter
- [x] 8.3 Create Menu.php config file
- [x] 8.4 Test route accessibility
- [x] 8.5 Test menu item appears in dashboard sidebar

## 9. Authentication & Authorization
- [x] 9.1 Add admission permissions to AuthGroups.php
  - [x] 9.1.1 Add 'dashboard.access' permission
  - [x] 9.1.2 Add 'admission.manage' permission
  - [x] 9.1.3 Add 'admission.view' permission
  - [x] 9.1.4 Assign permissions to appropriate groups in matrix
- [x] 9.2 Test permission-based access control
- [x] 9.3 Test token-based API authentication

## 10. Testing & Validation
- [ ] 10.1 Test public admission form submission
  - [ ] 10.1.1 Test with valid data and files
  - [ ] 10.1.2 Test validation errors
  - [ ] 10.1.3 Test file upload validation
  - [ ] 10.1.4 Test registration number generation
  - [ ] 10.1.5 Test email uniqueness
- [ ] 10.2 Test staff admission management
  - [ ] 10.2.1 Test list view with pagination
  - [ ] 10.2.2 Test search functionality
  - [ ] 10.2.3 Test filter by status
  - [ ] 10.2.4 Test view details
  - [ ] 10.2.5 Test file downloads
  - [ ] 10.2.6 Test create/update/delete operations
  - [ ] 10.2.7 Test soft delete and restore
- [ ] 10.3 Test API endpoints
  - [ ] 10.3.1 Test GET /api/admissions (list)
  - [ ] 10.3.2 Test GET /api/admissions/{id} (show)
  - [ ] 10.3.3 Test POST /api/admissions (create)
  - [ ] 10.3.4 Test PUT /api/admissions/{id} (update)
  - [ ] 10.3.5 Test DELETE /api/admissions/{id} (delete)
  - [ ] 10.3.6 Test search and filter endpoints
  - [ ] 10.3.7 Test authentication and authorization
  - [ ] 10.3.8 Test error responses
- [ ] 10.4 Test Dashboard module
  - [ ] 10.4.1 Test authentication requirement
  - [ ] 10.4.2 Test permission-based module display
  - [ ] 10.4.3 Test navigation to modules
- [ ] 10.5 Test Frontend module
  - [ ] 10.5.1 Test all static pages
  - [ ] 10.5.2 Test navigation between pages

## 11. Security & Performance
- [ ] 11.1 Verify CSRF protection on all forms
- [ ] 11.2 Verify file upload security (type, size, MIME validation)
- [ ] 11.3 Verify SQL injection prevention (Query Builder usage)
- [ ] 11.4 Verify XSS protection
- [ ] 11.5 Test database indexes performance
- [ ] 11.6 Verify file storage security (writable directory)

## 12. Documentation
- [ ] 12.1 Document API endpoints (request/response examples)
- [ ] 12.2 Document authentication flow
- [ ] 12.3 Document file upload requirements
- [ ] 12.4 Document module structure
- [ ] 12.5 Create deployment guide

## 13. Optional Enhancements
- [ ]* 13.1 Add email notifications for status changes
- [ ]* 13.2 Add image preview/thumbnail generation
- [ ]* 13.3 Add bulk operations (approve/reject multiple)
- [ ]* 13.4 Add export functionality (CSV/PDF)
- [ ]* 13.5 Add API rate limiting
- [ ]* 13.6 Add audit logging
