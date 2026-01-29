# Requirements: Modular ERP Backend

## 1. Overview
Build a modular HMVC (Hierarchical Model-View-Controller) ERP application backend using CodeIgniter 4 with Shield authentication library. The system should support multiple independent modules with role-based access control.

## 2. User Stories

### 2.1 As a developer
I want to organize the application in a modular HMVC structure so that each module is self-contained and maintainable independently.

**Acceptance Criteria:**
- All modules are placed in `app/Modules/` directory
- Each module contains its own Controllers, Models, Views, Config, and Routes
- Modules can be enabled/disabled independently
- Module autoloading is configured properly

### 2.2 As a visitor
I want to view static website pages (home, about, contact us) so that I can learn about the organization.

**Acceptance Criteria:**
- Frontend module exists with routes for home, about, and contact pages
- Pages are publicly accessible without authentication
- Each page has a dedicated view file
- Navigation between pages works correctly

### 2.2.1 As a prospective student
I want to submit an admission application online so that I can apply to the institution without visiting in person.

**Acceptance Criteria:**
- Public admission application form is accessible at `/apply`
- System automatically generates unique registration number upon submission (format: REG-YYYY-NNNN)
- Form includes personal information fields:
  - Full name (required)
  - Nickname (optional)
  - Gender (required - Male/Female)
  - Place of birth (required)
  - Date of birth (required)
  - Religion (required)
  - Citizen ID number (optional - only if applicant has ID card)
  - Phone (required)
  - Email (required, unique)
- Form includes address fields:
  - Street address (required)
  - District/Sub-district (required)
  - Regency/City (required)
  - Province (required)
  - Postal code (optional)
- Form includes emergency contact:
  - Contact name (required)
  - Contact phone (required)
  - Relationship to applicant (required)
- Form includes family information:
  - Father's name (required)
  - Mother's name (required)
- Form includes course selection (required)
- Form allows file uploads: profile photo (required) and supporting documents (optional, multiple files)
- Profile photo accepts: jpg, jpeg, png (max 2MB)
- Documents accept: pdf, doc, docx (max 5MB per file, max 3 files)
- Additional notes field (optional)
- Form has proper validation (required fields, email format, phone format, date format, file types, file sizes)
- Successful submission shows confirmation page with registration number
- Application is saved with "pending" status
- Uploaded files are stored securely with unique filenames
- Email uniqueness is enforced (no duplicate applications)
- CSRF protection is enabled on the form

### 2.3 As an authenticated user
I want to access a dashboard that shows only the features I'm authorized to see based on my role/permissions.

**Acceptance Criteria:**
- Dashboard module requires authentication
- Dashboard displays menu items filtered by user's auth_group or permissions
- Unauthorized users cannot access restricted dashboard features
- Dashboard shows user information and available modules

### 2.5 As a mobile/external application developer
I want to access admission data through RESTful API endpoints so that I can integrate with mobile apps or external systems.

**Acceptance Criteria:**
- RESTful API endpoints are available for admission module
- API follows REST conventions (GET, POST, PUT, DELETE)
- API uses JSON for request and response format
- API requires authentication (token-based)
- API endpoints include:
  - GET /api/admissions - List all admissions (with pagination)
  - GET /api/admissions/{id} - Get single admission details
  - POST /api/admissions - Create new admission
  - PUT /api/admissions/{id} - Update admission
  - DELETE /api/admissions/{id} - Delete admission (soft delete)
  - GET /api/admissions/search?q={keyword} - Search admissions
  - GET /api/admissions/filter?status={status} - Filter by status
- API returns proper HTTP status codes (200, 201, 400, 401, 404, 500)
- API includes error messages in JSON format
- API supports file uploads (multipart/form-data)
- API rate limiting is implemented
- API documentation is available
I want to manage student admission data so that I can review, track, and process student applications submitted through the public form.

**Acceptance Criteria:**
- Admission module exists with CRUD operations for student data
- Only authorized users (with admission permissions) can access the module
- Student admission data includes all form fields: personal info, address, emergency contact, family info, course, status, files, notes
- List view shows all admissions with search and filter capabilities
- Staff can view individual admission details including all information and uploaded files
- Staff can download uploaded documents
- Staff can view/preview profile photos
- Staff can edit admission records and update status (pending, approved, rejected)
- Staff can manually create admission records with file uploads
- Staff can delete admission records (soft delete) - files are retained
- Staff can restore soft-deleted records
- Form validation is implemented for all required fields and file uploads
- Dashboard shows admission statistics (pending, approved, rejected counts)

## 3. Technical Requirements

### 3.1 Module Structure
Each module must follow this structure:
```
app/Modules/{ModuleName}/
├── Config/
│   └── Routes.php
├── Controllers/
├── Models/
└── Views/
```

**Note:** Migrations are centralized in `app/Database/Migrations/`, not within individual modules.

### 3.2 Authentication & Authorization
- Use CodeIgniter Shield for authentication
- Implement permission-based access control
- Protect dashboard and admission routes with auth filters
- Support multiple auth groups (admin, staff, student, etc.)

### 3.3 Database
- Create migrations for admission module tables
- Use proper foreign key relationships where applicable
- Implement soft deletes for data retention

### 3.4 Routing
- Module routes should be prefixed (e.g., `/dashboard`, `/admission`)
- Frontend routes should be at root level (`/`, `/about`, `/contact`)
- API routes should follow RESTful conventions with `/api` prefix
- API routes use resource routing (GET, POST, PUT, DELETE)
- API authentication uses token-based auth (Shield tokens)

## 4. Modules Specification

### 4.1 Frontend Module
**Purpose:** Public-facing static pages and admission application form

**Routes:**
- `GET /` - Home page
- `GET /about` - About page
- `GET /contact` - Contact page
- `GET /apply` - Admission application form
- `POST /apply/submit` - Submit admission application
- `GET /apply/success` - Application success confirmation

**Access:** Public (no authentication required)

**Features:**
- Static informational pages
- Public admission application form with validation
- Success confirmation after submission

### 4.2 Dashboard Module
**Purpose:** Central hub for authenticated users
**Routes:**
- `GET /dashboard` - Main dashboard view

**Access:** Authenticated users only
**Features:**
- Display user information
- Show available modules based on permissions
- Quick stats and notifications

### 4.3 Admission Module
**Purpose:** Manage student admission applications (staff interface)

**Routes:**
- `GET /admission` - List all admissions with statistics
- `GET /admission/view/{id}` - View admission details
- `GET /admission/create` - Show create form (manual entry)
- `POST /admission/store` - Store new admission
- `GET /admission/edit/{id}` - Show edit form
- `POST /admission/update/{id}` - Update admission
- `DELETE /admission/delete/{id}` - Delete admission (soft delete)
- `GET /admission/search` - Search admissions

**Access:** Users with `admission.manage` permission

**Data Fields:**
- Registration number (auto-generated, unique, format: REG-YYYY-NNNN)
- Full name (required)
- Nickname (optional)
- Gender (required - Male/Female)
- Place of birth (required)
- Date of birth (required)
- Religion (required)
- Citizen ID number (optional)
- Phone (required)
- Email (required, unique)
- Address:
  - Street address (required)
  - District (required)
  - Regency/City (required)
  - Province (required)
  - Postal code (optional)
- Emergency Contact:
  - Name (required)
  - Phone (required)
  - Relationship (required)
- Father's name (required)
- Mother's name (required)
- Course/Program (required)
- Profile photo (required - jpg, jpeg, png, max 2MB)
- Supporting documents (optional - pdf, doc, docx, max 5MB each, max 3 files)
- Application status (pending, approved, rejected)
- Application date (auto-generated or manual)
- Additional notes (optional)

**Features:**
- View all applications submitted via public form
- Search and filter by registration number, name, email, course, or status
- Update application status (approve/reject)
- Manual admission entry by staff with file uploads
- View and download uploaded files
- Soft delete with data retention (including files)
- Restore soft-deleted records
- Statistics dashboard (pending, approved, rejected counts)

### 4.4 API Module (Admission API)
**Purpose:** RESTful API for admission data access

**Routes:**
- `GET /api/admissions` - List all admissions (paginated)
- `GET /api/admissions/{id}` - Get single admission
- `POST /api/admissions` - Create new admission
- `PUT /api/admissions/{id}` - Update admission
- `DELETE /api/admissions/{id}` - Delete admission
- `GET /api/admissions/search` - Search admissions
- `GET /api/admissions/filter` - Filter by status

**Access:** Token-based authentication (Shield tokens)

**Response Format:** JSON

**Features:**
- RESTful resource routing
- JSON request/response
- Proper HTTP status codes
- Error handling with JSON messages
- Pagination support
- File upload support (multipart/form-data)
- Rate limiting

## 5. Non-Functional Requirements
- All forms must have CSRF protection
- Input validation and sanitization
- SQL injection prevention through query builder
- XSS protection enabled
- File upload validation (type, size, extension)
- Secure file storage with unique filenames
- Prevent directory traversal attacks
- Validate file MIME types

### 5.2 Performance
- Efficient database queries with proper indexing
- Pagination for large data sets
- Caching for static content

### 5.3 Maintainability
- Follow PSR-12 coding standards
- Proper code documentation
- Separation of concerns
- DRY principle

## 6. Dependencies
- CodeIgniter 4 (appstarter)
- CodeIgniter Shield (authentication library)
- PHP 8.1+
- MySQL/MariaDB database

## 7. Out of Scope (for initial version)
- Advanced reporting features
- Email notifications
- Multi-language support
- Bulk file operations
- Image cropping/resizing in browser
- Webhook notifications
- GraphQL API
