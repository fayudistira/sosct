# FEECS - Educational Management System Cookbook

## 📖 Table of Contents

1. [Overview](#overview)
2. [Technology Stack](#technology-stack)
3. [System Architecture](#system-architecture)
4. [File Structure](#file-structure)
5. [Database Design](#database-design)
6. [Module Documentation](#module-documentation)
7. [Implementation Timeline](#implementation-timeline)
8. [Setup & Installation](#setup--installation)
9. [Development Guidelines](#development-guidelines)
10. [Future Roadmap](#future-roadmap)

---

## Overview

**FEECS** (Foundation for Educational and Entrepreneurial Community Services) is a comprehensive educational management system built with CodeIgniter 4. The system manages the complete lifecycle of an educational institution from student admissions to academic management, payments, and reporting.

### Key Features

- 🎓 **Student Admission Management** - Online application, approval workflow
- 👥 **Profile Management** - Unified profile system for all users
- 📚 **Program Management** - Course catalog with categories and details
- 💰 **Payment & Invoicing** - Invoice generation, payment tracking, QR codes
- 🏫 **Academic Management** - Class management, student enrollment (in progress)
- 📊 **Dashboard & Reporting** - Statistics and analytics
- 🌐 **Public Frontend** - Program showcase, online applications

### Design Philosophy

- **Modular Architecture** - Each feature is a self-contained module
- **Single Source of Truth** - Unified profile system for all entities
- **Role-Based Access** - Flexible role assignment (student, staff, instructor)
- **Soft Deletes** - Data retention for audit trails
- **Responsive Design** - Mobile-first approach

---

## Technology Stack

### Backend
- **Framework**: CodeIgniter 4.7+
- **PHP**: 8.1+
- **Database**: MySQL 8.0+
- **Authentication**: CodeIgniter Shield

### Frontend
- **CSS Framework**: Bootstrap 5.3
- **Icons**: Bootstrap Icons
- **JavaScript**: Vanilla JS + jQuery
- **Charts**: Chart.js (for dashboard)

### Libraries & Packages
- **dompdf/dompdf** - PDF generation
- **endroid/qr-code** - QR code generation
- **phpoffice/phpspreadsheet** - Excel import/export

---

## System Architecture

### Modular Structure

```
FEECS/
├── Frontend Module      → Public-facing website
├── Dashboard Module     → Admin dashboard
├── Admission Module     → Application management
├── Account Module       → Profile management
├── Program Module       → Course catalog
├── Payment Module       → Invoicing & payments
├── Users Module         → User management
└── Academic Module      → Class & enrollment (planned)
```

### Data Flow

```
┌─────────────┐
│   Public    │  → Apply for admission
│  Frontend   │
└──────┬──────┘
       ↓
┌─────────────┐
│  Admission  │  → Create profile + application
│   Module    │
└──────┬──────┘
       ↓ (Approved)
┌─────────────┐
│   Student   │  → Assign to classes
│   Record    │
└──────┬──────┘
       ↓
┌─────────────┐
│  Academic   │  → Enrollment & tracking
│   Module    │
└──────┬──────┘
       ↓
┌─────────────┐
│   Payment   │  → Generate invoices
│   Module    │
└─────────────┘
```

---

## File Structure

### Root Directory

```
feecs/
├── app/
│   ├── Commands/              # CLI commands
│   ├── Config/                # Configuration files
│   ├── Controllers/           # Base controllers
│   │   └── Auth/             # Authentication controllers
│   ├── Database/
│   │   ├── Migrations/       # Database migrations
│   │   └── Seeds/            # Database seeders
│   ├── Modules/              # Application modules
│   │   ├── Account/          # Profile management
│   │   ├── Admission/        # Admission system
│   │   ├── Dashboard/        # Admin dashboard
│   │   ├── Frontend/         # Public website
│   │   ├── Payment/          # Payment & invoicing
│   │   ├── Program/          # Program catalog
│   │   └── Users/            # User management
│   └── Views/
│       ├── Auth/             # Login/register views
│       └── errors/           # Error pages
├── public/
│   ├── uploads/              # Junction to writable/uploads
│   └── templates/            # File templates (Excel, etc.)
├── writable/
│   ├── uploads/              # File storage
│   │   ├── admissions/
│   │   ├── profiles/
│   │   ├── programs/
│   │   └── receipts/
│   ├── logs/                 # Application logs
│   └── cache/                # Cache files
├── .kiro/
│   └── specs/                # Feature specifications
│       ├── academic-module/
│       ├── payment-management/
│       └── modular-erp-backend/
└── vendor/                   # Composer dependencies
```

### Module Structure (Standard)

Each module follows this structure:

```
ModuleName/
├── Config/
│   ├── Routes.php           # Module routes
│   └── Menu.php             # Menu configuration
├── Controllers/
│   ├── ModuleController.php # Main controller
│   └── Api/                 # API controllers (optional)
├── Models/
│   └── ModuleModel.php      # Data models
├── Views/
│   ├── index.php            # List view
│   ├── create.php           # Create form
│   ├── edit.php             # Edit form
│   └── view.php             # Detail view
├── Database/                # Module-specific (optional)
│   ├── Migrations/
│   └── Seeds/
└── Libraries/               # Module-specific libraries
```

---

## Database Design

### Current Schema Overview

```
users (Shield)
  ↓ 1:1
profiles ← SINGLE SOURCE OF TRUTH
  ↓
  ├─ 1:N → admissions (application history)
  ├─ 1:1 → students (role assignment)
  ├─ 1:1 → staff (role assignment)
  └─ 1:1 → instructors (role assignment)

programs (course catalog)
  ↓ 1:N
admissions (applications reference programs)

invoices
  ↓ 1:1
admissions (invoices for students)
  ↓ 1:N
payments (payment records)

classes
  ↓ N:M
students (via class_members)
```

### Core Tables

#### 1. profiles (Master Identity)
```sql
- id (PK)
- profile_number (UNIQUE)
- user_id (FK, nullable)
- full_name, gender, date_of_birth
- contact info (phone, email, address)
- emergency contact
- parents info
- photo, documents (JSON)
- timestamps, soft delete
```

#### 2. admissions (Applications)
```sql
- id (PK)
- registration_number (UNIQUE)
- profile_id (FK) ← Links to profile
- course, status, application_date
- notes
- timestamps, soft delete
```

#### 3. students (Student Role)
```sql
- id (PK)
- student_number (UNIQUE)
- profile_id (FK, UNIQUE)
- admission_id (FK)
- enrollment_date, status
- program, batch
- academic data (GPA, credits)
- timestamps, soft delete
```

#### 4. programs
```sql
- id (PK, UUID)
- program_name, category, level
- mode, curriculum
- duration, fee
- description, thumbnail
- timestamps, soft delete
```

#### 5. invoices
```sql
- id (PK)
- invoice_number (UNIQUE)
- registration_number (FK)
- amount, due_date, status
- qr_code
- timestamps, soft delete
```

#### 6. payments
```sql
- id (PK)
- payment_number (UNIQUE)
- invoice_id (FK)
- amount, payment_date, method
- receipt_file
- timestamps, soft delete
```

#### 7. classes
```sql
- id (PK, UUID)
- class_name, category, level, batch
- status, start_date, end_date
- max_students, instructor_id
- timestamps, soft delete
```

#### 8. class_members (Enrollment)
```sql
- id (PK)
- class_id (FK)
- student_id (FK)
- enrollment_date, status
- notes
- timestamps, soft delete
- UNIQUE(class_id, student_id)
```

---

## Module Documentation

### 1. Frontend Module

**Purpose**: Public-facing website for program showcase and applications

**Routes**:
- `/` - Home page
- `/programs` - Program catalog
- `/programs/detail/{id}` - Program details
- `/apply` - Application form
- `/about` - About page
- `/contact` - Contact page

**Key Features**:
- Program browsing with filters
- Online application submission
- Responsive design
- SEO-friendly

**Files**:
- Controllers: `PageController.php`
- Views: `home.php`, `programs/index.php`, `programs/detail.php`, `apply.php`
- Layout: `layout.php`

---

### 2. Dashboard Module

**Purpose**: Admin dashboard with statistics and navigation

**Routes**:
- `/dashboard` - Main dashboard

**Key Features**:
- Statistics cards (admissions, programs, payments)
- Recent activities
- Quick actions
- Sidebar navigation with module menu

**Files**:
- Controllers: `DashboardController.php`
- Views: `index.php`, `layout.php`
- Helpers: `menu_helper.php`

---

### 3. Admission Module

**Purpose**: Manage student applications and approvals

**Routes**:
- `/admission/admissions` - List all applications
- `/admission/admissions/create` - Create application
- `/admission/admissions/view/{id}` - View details
- `/admission/admissions/edit/{id}` - Edit application
- `/admission/admissions/approve/{id}` - Approve application
- `/admission/admissions/reject/{id}` - Reject application

**Key Features**:
- Application CRUD operations
- Status workflow (pending → approved/rejected)
- File uploads (photo, documents)
- Search and filter
- Bulk operations
- API endpoints

**Files**:
- Controllers: `AdmissionController.php`, `Api/AdmissionApiController.php`
- Models: `AdmissionModel.php`
- Views: `index.php`, `create.php`, `edit.php`, `view.php`
- Migration: `2026-01-30-005313_CreateAdmissionsTable.php`
- Seeder: `AdmissionSeeder.php`

**Business Rules**:
- Unique registration number (REG-YYYY-NNNN)
- Unique email per application
- Status transitions: pending → approved/rejected
- Soft delete for data retention

---

### 4. Account Module

**Purpose**: Profile management for staff and administrators

**Routes**:
- `/account/profiles` - List profiles
- `/account/profiles/create` - Create profile
- `/account/profiles/edit/{id}` - Edit profile

**Key Features**:
- Profile CRUD operations
- File uploads (photo, documents)
- Position assignment

**Files**:
- Controllers: `ProfileController.php`
- Models: `ProfileModel.php`
- Views: `index.php`, `create.php`, `edit.php`
- Migration: `2026-02-02-042200_CreateProfilesTable.php`

**Note**: This module will be refactored to become the master profile system for all entities.

---

### 5. Program Module

**Purpose**: Manage educational programs/courses

**Routes**:
- `/program/programs` - List programs
- `/program/programs/create` - Create program
- `/program/programs/view/{id}` - View details
- `/program/programs/edit/{id}` - Edit program
- `/program/programs/bulk-upload` - Bulk upload via Excel

**Key Features**:
- Program CRUD operations
- Category management (Undergraduate, Graduate, etc.)
- Level management (Beginner, Intermediate, Advanced)
- Mode & Curriculum tracking
- Thumbnail uploads
- Bulk upload via Excel template
- Search and filter
- API endpoints

**Files**:
- Controllers: `ProgramController.php`, `Api/ProgramApiController.php`
- Models: `ProgramModel.php`
- Views: `index.php`, `create.php`, `edit.php`, `view.php`
- Migrations: 
  - `2026-02-01-104702_CreateProgramsTable.php`
  - `2026-02-01-105617_AddThumbnailToPrograms.php`
  - `2026-02-02-103246_AddModeCurriculumToPrograms.php`
- Seeder: `ProgramSeeder.php`
- Template: `public/templates/program_bulk_upload_template.xlsx`

**Business Rules**:
- UUID as primary key
- Unique program names
- Thumbnail stored in `writable/uploads/programs/thumbs/`
- Soft delete

---

### 6. Payment Module

**Purpose**: Invoice generation and payment tracking

**Routes**:

**Invoices**:
- `/payment/invoices` - List invoices
- `/payment/invoices/create` - Create invoice
- `/payment/invoices/view/{id}` - View invoice
- `/payment/invoices/edit/{id}` - Edit invoice
- `/payment/invoices/public/{invoice_number}` - Public invoice view
- `/payment/invoices/pdf/{id}` - Download PDF
- `/payment/invoices/send/{id}` - Send via email

**Payments**:
- `/payment/payments` - List payments
- `/payment/payments/create` - Record payment
- `/payment/payments/view/{id}` - View payment
- `/payment/payments/edit/{id}` - Edit payment

**Reports**:
- `/payment/reports/revenue` - Revenue report
- `/payment/reports/overdue` - Overdue invoices

**Key Features**:
- Invoice generation with QR codes
- Payment recording with receipts
- PDF generation (download/print)
- Public invoice view (via QR scan)
- Payment status tracking
- Revenue reporting
- Overdue tracking
- Currency: Indonesian Rupiah (Rp)
- API endpoints

**Files**:
- Controllers: 
  - `InvoiceController.php`
  - `PaymentController.php`
  - `Api/InvoiceApiController.php`
  - `Api/PaymentApiController.php`
- Models: `InvoiceModel.php`, `PaymentModel.php`
- Libraries: `PdfGenerator.php`
- Views:
  - `invoices/` - index, create, edit, view, public_view
  - `payments/` - index, create, edit, view
  - `reports/` - revenue, overdue
- Migrations:
  - `2026-02-01-000001_CreateInvoicesTable.php`
  - `2026-02-01-000002_CreatePaymentsTable.php`
- Seeders: `InvoiceSeeder.php`, `PaymentSeeder.php`

**Business Rules**:
- Invoice number format: INV-YYYY-NNNN
- Payment number format: PAY-YYYY-NNNN
- QR code contains public invoice URL
- Invoice status: unpaid, partial, paid, overdue, cancelled
- Payment status: pending, completed, failed, refunded
- Soft delete

**Technical Details**:
- QR Code: Generated using endroid/qr-code library
- PDF: Generated using dompdf library
- Public view: No authentication required
- Currency formatting: Indonesian Rupiah (Rp)

---

### 7. Users Module

**Purpose**: User account management (leverages CodeIgniter Shield)

**Routes**:
- `/users/users` - List users
- `/users/users/edit/{id}` - Edit user

**Key Features**:
- User listing
- Role assignment
- Status management
- Integration with Shield authentication

**Files**:
- Controllers: `UserController.php`
- Views: `index.php`, `edit.php`
- Seeder: `AdminUsersSeeder.php`

**Roles**:
- superadmin - Full system access
- admin - Administrative access
- user - Basic access

---

### 8. Academic Module (In Progress)

**Purpose**: Class management and student enrollment

**Status**: Specification phase complete, implementation pending

**Planned Routes**:
- `/academic/classes` - List classes
- `/academic/classes/create` - Create class
- `/academic/classes/view/{id}` - View class details
- `/academic/classes/assign/{id}` - Assign students
- `/academic/members` - List all enrollments

**Planned Features**:
- Class CRUD operations
- Student assignment to classes
- Enrollment tracking
- Capacity management
- Status management (active, dropped, completed)
- Class roster export

**Specification Files**:
- `.kiro/specs/academic-module/PLAN.md`
- `.kiro/specs/academic-module/requirements.md`
- `.kiro/specs/academic-module/design.md`
- `.kiro/specs/academic-module/tasks.md`

**Planned Tables**:
- `classes` - Class information
- `class_members` - Student enrollments

---

## Implementation Timeline

### Phase 1: Foundation (Completed)
**Duration**: Week 1-2

- [x] CodeIgniter 4 setup
- [x] CodeIgniter Shield authentication
- [x] Base module structure
- [x] Dashboard layout
- [x] Menu system
- [x] File upload handling

**Deliverables**:
- Working authentication system
- Admin dashboard with navigation
- Module auto-loading system

---

### Phase 2: Core Modules (Completed)
**Duration**: Week 3-6

#### 2.1 Frontend Module
- [x] Public website layout
- [x] Home page
- [x] Program catalog
- [x] Program detail pages
- [x] Application form
- [x] About/Contact pages

#### 2.2 Admission Module
- [x] Database migration
- [x] Admission CRUD operations
- [x] Status workflow
- [x] File uploads
- [x] Search and filter
- [x] API endpoints
- [x] Bulk operations

#### 2.3 Program Module
- [x] Database migration
- [x] Program CRUD operations
- [x] Category and level management
- [x] Thumbnail uploads
- [x] Bulk upload via Excel
- [x] Search and filter
- [x] API endpoints

**Deliverables**:
- Functional admission system
- Program catalog management
- Public application form

---

### Phase 3: Financial Management (Completed)
**Duration**: Week 7-9

#### 3.1 Payment Module
- [x] Invoice database migration
- [x] Payment database migration
- [x] Invoice CRUD operations
- [x] Payment CRUD operations
- [x] PDF generation
- [x] QR code generation
- [x] Public invoice view
- [x] Revenue reporting
- [x] Overdue tracking
- [x] Currency formatting (Rupiah)
- [x] API endpoints

**Deliverables**:
- Complete invoicing system
- Payment tracking
- Financial reports
- QR code integration

---

### Phase 4: Profile System (Completed)
**Duration**: Week 10

#### 4.1 Account Module
- [x] Profile database migration
- [x] Profile CRUD operations
- [x] File uploads
- [x] Position management

**Deliverables**:
- Profile management for staff

---

### Phase 5: Academic Management (In Progress)
**Duration**: Week 11-13 (Current)

#### 5.1 Academic Module Specification
- [x] Requirements documentation
- [x] Database design
- [x] Technical design
- [x] Task breakdown
- [ ] Implementation (pending)

#### 5.2 Data Model Refactoring (Planned)
- [ ] Refactor profiles as master identity table
- [ ] Create students table
- [ ] Create staff table
- [ ] Create instructors table
- [ ] Migrate existing data
- [ ] Update relationships

#### 5.3 Academic Module Implementation (Planned)
- [ ] Class management
- [ ] Student enrollment
- [ ] Capacity tracking
- [ ] Status management
- [ ] Roster export

**Deliverables** (Planned):
- Unified profile system
- Class management system
- Student enrollment tracking

---

### Phase 6: Enhancement & Polish (Planned)
**Duration**: Week 14-16

- [ ] Email notifications
- [ ] Advanced reporting
- [ ] Dashboard widgets
- [ ] Performance optimization
- [ ] Security audit
- [ ] User documentation
- [ ] Deployment preparation

---

## Setup & Installation

### Prerequisites

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Composer
- Web server (Apache/Nginx)

### Installation Steps

1. **Clone Repository**
```bash
git clone <repository-url>
cd feecs
```

2. **Install Dependencies**
```bash
composer install
```

3. **Environment Configuration**
```bash
cp env .env
```

Edit `.env`:
```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = feecs_db
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
```

4. **Database Setup**
```bash
# Create database
mysql -u root -p
CREATE DATABASE feecs_db;
exit;

# Run migrations
php spark migrate --all

# Run seeders
php spark db:seed AdminUsersSeeder
php spark db:seed ProgramSeeder
php spark db:seed AdmissionSeeder
php spark db:seed InvoiceSeeder
php spark db:seed PaymentSeeder
```

5. **File Permissions**
```bash
# Windows (PowerShell as Administrator)
icacls writable /grant Users:F /t

# Linux/Mac
chmod -R 777 writable
```

6. **Create Upload Symlink**

**Windows (CMD as Administrator)**:
```cmd
cd public
mklink /J uploads ..\writable\uploads
```

**Linux/Mac**:
```bash
cd public
ln -s ../writable/uploads uploads
```

7. **Start Development Server**
```bash
php spark serve
```

8. **Access Application**
- Frontend: http://localhost:8080
- Admin: http://localhost:8080/login
  - Email: admin@example.com
  - Password: admin123

---

## Development Guidelines

### Coding Standards

1. **Follow CodeIgniter 4 Style Guide**
   - PSR-4 autoloading
   - Namespaces for all classes
   - Type hints where possible

2. **Naming Conventions**
   - Controllers: PascalCase + "Controller" suffix
   - Models: PascalCase + "Model" suffix
   - Views: snake_case.php
   - Database tables: snake_case, plural
   - Routes: kebab-case

3. **Module Structure**
   - Each module is self-contained
   - Routes defined in module's Config/Routes.php
   - Menu defined in module's Config/Menu.php
   - Follow standard folder structure

4. **Database**
   - Use migrations for schema changes
   - Use seeders for sample data
   - Always use soft deletes
   - Add indexes for foreign keys
   - Use UUIDs for distributed systems

5. **Security**
   - Validate all inputs
   - Escape all outputs
   - Use CSRF protection
   - Sanitize file uploads
   - Use prepared statements (query builder)

### Git Workflow

1. **Branch Naming**
   - feature/module-name
   - bugfix/issue-description
   - hotfix/critical-issue

2. **Commit Messages**
   - Use present tense: "Add feature" not "Added feature"
   - Be descriptive but concise
   - Reference issues: "Fix #123: Description"

3. **Pull Requests**
   - One feature per PR
   - Include description and testing steps
   - Update documentation if needed

### Testing

1. **Manual Testing Checklist**
   - [ ] CRUD operations work
   - [ ] Validation works
   - [ ] File uploads work
   - [ ] Search/filter works
   - [ ] Responsive design works
   - [ ] Error handling works

2. **Database Testing**
   - Test migrations up/down
   - Test seeders
   - Test foreign key constraints
   - Test soft deletes

---

## Future Roadmap

### Short Term (Next 3 Months)

1. **Complete Academic Module**
   - Implement class management
   - Implement student enrollment
   - Add attendance tracking

2. **Refactor Profile System**
   - Unified profile table
   - Role-based tables (students, staff, instructors)
   - Data migration

3. **Enhanced Reporting**
   - Academic reports
   - Financial reports
   - Custom report builder

### Medium Term (3-6 Months)

1. **Student Portal**
   - Student login
   - View classes
   - View grades
   - View invoices
   - Make payments

2. **Instructor Portal**
   - View assigned classes
   - Mark attendance
   - Enter grades
   - View class roster

3. **Communication System**
   - Email notifications
   - SMS integration
   - In-app messaging
   - Announcement system

4. **Advanced Features**
   - Grade management
   - Transcript generation
   - Certificate generation
   - Document management

### Long Term (6-12 Months)

1. **Mobile Application**
   - Student mobile app
   - Instructor mobile app
   - Push notifications

2. **Integration**
   - Payment gateway integration
   - Email service integration
   - SMS gateway integration
   - Cloud storage integration

3. **Analytics & BI**
   - Advanced analytics dashboard
   - Predictive analytics
   - Data visualization
   - Export to BI tools

4. **Multi-tenancy**
   - Support multiple institutions
   - Tenant isolation
   - Shared resources
   - Custom branding

---

## Appendix

### A. Test Accounts

**Admin Account**:
- Email: admin@example.com
- Password: admin123
- Role: superadmin

### B. Sample Data

The system includes seeders for:
- Admin users
- Programs (10 sample programs)
- Admissions (20 sample applications)
- Invoices (10 sample invoices)
- Payments (5 sample payments)

### C. File Upload Limits

- Photos: Max 2MB, JPEG/PNG
- Documents: Max 5MB, PDF/JPEG/PNG
- Receipts: Max 5MB, JPEG/PNG/PDF
- Thumbnails: Max 2MB, JPEG/PNG

### D. Number Formats

- Registration: REG-YYYY-NNNN (e.g., REG-2026-0001)
- Invoice: INV-YYYY-NNNN (e.g., INV-2026-0001)
- Payment: PAY-YYYY-NNNN (e.g., PAY-2026-0001)
- Student: STU-YYYY-NNNN (planned)
- Staff: STF-YYYY-NNNN (planned)
- Instructor: INS-YYYY-NNNN (planned)
- Profile: PROF-YYYY-NNNN (planned)

### E. Status Enums

**Admission Status**:
- pending - Awaiting review
- approved - Application approved
- rejected - Application rejected

**Invoice Status**:
- unpaid - Not paid
- partial - Partially paid
- paid - Fully paid
- overdue - Past due date
- cancelled - Cancelled

**Payment Status**:
- pending - Processing
- completed - Successful
- failed - Failed
- refunded - Refunded

**Student Status** (planned):
- active - Currently enrolled
- inactive - Temporarily inactive
- graduated - Completed program
- dropped - Left program

**Class Status** (planned):
- draft - Being prepared
- active - Open for enrollment
- ongoing - In progress
- completed - Finished
- cancelled - Cancelled

### F. Useful Commands

```bash
# Database
php spark migrate                    # Run migrations
php spark migrate:rollback          # Rollback last migration
php spark migrate:refresh           # Rollback all and re-run
php spark db:seed SeederName        # Run specific seeder

# Development
php spark serve                     # Start dev server
php spark routes                    # List all routes
php spark namespaces                # List namespaces

# Cache
php spark cache:clear               # Clear cache
php spark optimize                  # Optimize autoloader

# Custom
php spark make:module ModuleName    # Create new module (if command exists)
```

### G. Directory Permissions

**Windows**:
```cmd
icacls writable /grant Users:F /t
icacls public\uploads /grant Users:F /t
```

**Linux/Mac**:
```bash
chmod -R 777 writable
chmod -R 777 public/uploads
```

### H. Troubleshooting

**Issue**: 404 on all routes
- Check .htaccess in public folder
- Verify mod_rewrite is enabled
- Check baseURL in .env

**Issue**: Database connection failed
- Verify database credentials in .env
- Check database exists
- Check MySQL service is running

**Issue**: File upload fails
- Check writable permissions
- Check upload_max_filesize in php.ini
- Check post_max_size in php.ini

**Issue**: Images not displaying
- Check uploads symlink exists
- Check file permissions
- Check file path in database

---

## Document Information

**Version**: 1.0  
**Last Updated**: 2026-02-03  
**Author**: FEECS Development Team  
**Status**: Living Document

---

**End of Cookbook**
