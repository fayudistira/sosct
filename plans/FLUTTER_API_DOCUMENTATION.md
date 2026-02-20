# FEECS ERP API Documentation for Flutter Frontend

## Table of Contents

- [Overview](#overview)
- [Authentication Analysis](#authentication-analysis)
- [Current API Endpoints](#current-api-endpoints)
- [RESTful Compliance Analysis](#restful-compliance-analysis)
- [Missing API Endpoints for Flutter](#missing-api-endpoints-for-flutter)
- [Recommendations for Flutter Development](#recommendations-for-flutter-development)
- [Complete API Reference](#complete-api-reference)

---

## Overview

### Project Information

- **Framework**: CodeIgniter 4 with CodeIgniter Shield
- **Authentication**: Session-based (web) + Token-based (available but not configured for APIs)
- **Database**: MySQL/MariaDB
- **API Format**: JSON

### Base URL

```
Production: https://your-domain.com
Development: http://localhost/feecs
```

### Current API Structure

The project has existing API endpoints under various modules, but they are primarily designed for AJAX calls from the web interface, not for mobile app consumption.

---

## Authentication Analysis

### Current Authentication Setup

The project uses **CodeIgniter Shield** with the following authentication methods:

| Filter       | Purpose                         | Suitable for Flutter |
| ------------ | ------------------------------- | -------------------- |
| `session`    | Cookie-based session auth       | No                   |
| `tokens`     | Bearer token authentication     | Yes                  |
| `chain`      | Multiple auth methods           | Yes                  |
| `group`      | Role-based access control       | Yes                  |
| `permission` | Permission-based access control | Yes                  |

### Critical Issue for Flutter

**Current API routes use `session` filter**, which requires cookie-based authentication. This is not suitable for Flutter mobile apps.

```php
// Current configuration (NOT suitable for Flutter)
$routes->group('api/admissions', ['filter' => 'session'], ...);

// Required configuration for Flutter
$routes->group('api/admissions', ['filter' => 'tokens'], ...);
```

### Authentication Endpoints Needed

| Endpoint                     | Method | Purpose                | Status  |
| ---------------------------- | ------ | ---------------------- | ------- |
| `/api/auth/login`            | POST   | Login and get token    | Missing |
| `/api/auth/register`         | POST   | Register new user      | Missing |
| `/api/auth/logout`           | POST   | Invalidate token       | Missing |
| `/api/auth/refresh`          | POST   | Refresh access token   | Missing |
| `/api/auth/user`             | GET    | Get current user info  | Missing |
| `/api/auth/password/reset`   | POST   | Request password reset | Missing |
| `/api/auth/password/confirm` | POST   | Confirm password reset | Missing |

---

## Current API Endpoints

### 1. Admission API (`/api/admissions`)

| Endpoint                 | Method | Description          | Auth    | Flutter Ready |
| ------------------------ | ------ | -------------------- | ------- | ------------- |
| `/api/admissions`        | GET    | List all admissions  | session | No            |
| `/api/admissions/{id}`   | GET    | Get single admission | session | No            |
| `/api/admissions`        | POST   | Create admission     | session | No            |
| `/api/admissions/{id}`   | PUT    | Update admission     | session | No            |
| `/api/admissions/{id}`   | DELETE | Delete admission     | session | No            |
| `/api/admissions/search` | GET    | Search admissions    | session | No            |
| `/api/admissions/filter` | GET    | Filter by status     | session | No            |

### 2. Program API (`/api/programs`)

| Endpoint                        | Method | Description         | Auth    | Flutter Ready |
| ------------------------------- | ------ | ------------------- | ------- | ------------- |
| `/api/programs`                 | GET    | List all programs   | session | No            |
| `/api/programs/{id}`            | GET    | Get single program  | session | No            |
| `/api/programs`                 | POST   | Create program      | session | No            |
| `/api/programs/{id}`            | PUT    | Update program      | session | No            |
| `/api/programs/{id}`            | DELETE | Delete program      | session | No            |
| `/api/programs/search`          | GET    | Search programs     | session | No            |
| `/api/programs/filter`          | GET    | Filter by status    | session | No            |
| `/api/programs/filter/category` | GET    | Filter by category  | session | No            |
| `/api/programs/active`          | GET    | Get active programs | session | No            |
| `/api/programs/categories`      | GET    | Get categories      | session | No            |

### 3. Payment API (`/api/payments`)

| Endpoint                         | Method | Description           | Auth    | Flutter Ready |
| -------------------------------- | ------ | --------------------- | ------- | ------------- |
| `/api/payments`                  | GET    | List all payments     | session | No            |
| `/api/payments/{id}`             | GET    | Get single payment    | session | No            |
| `/api/payments`                  | POST   | Create payment        | session | No            |
| `/api/payments/{id}`             | PUT    | Update payment        | session | No            |
| `/api/payments/{id}/status`      | PUT    | Update payment status | session | No            |
| `/api/payments/search`           | GET    | Search payments       | session | No            |
| `/api/payments/filter/status`    | GET    | Filter by status      | session | No            |
| `/api/payments/filter/method`    | GET    | Filter by method      | session | No            |
| `/api/payments/filter/daterange` | GET    | Filter by date range  | session | No            |
| `/api/payments/student/{reg}`    | GET    | Get by student        | session | No            |
| `/api/payments/statistics`       | GET    | Get statistics        | session | No            |
| `/api/payments/{id}/receipt`     | GET    | Get receipt           | session | No            |
| `/api/payments/{id}/receipt`     | POST   | Upload receipt        | session | No            |

### 4. Invoice API (`/api/invoices`)

| Endpoint                          | Method | Description          | Auth    | Flutter Ready |
| --------------------------------- | ------ | -------------------- | ------- | ------------- |
| `/api/invoices`                   | GET    | List all invoices    | session | No            |
| `/api/invoices/{id}`              | GET    | Get single invoice   | session | No            |
| `/api/invoices`                   | POST   | Create invoice       | session | No            |
| `/api/invoices/{id}`              | PUT    | Update invoice       | session | No            |
| `/api/invoices/{id}`              | DELETE | Delete invoice       | session | No            |
| `/api/invoices/search`            | GET    | Search invoices      | session | No            |
| `/api/invoices/search-admissions` | GET    | Search admissions    | session | No            |
| `/api/invoices/filter/status`     | GET    | Filter by status     | session | No            |
| `/api/invoices/filter/type`       | GET    | Filter by type       | session | No            |
| `/api/invoices/student/{reg}`     | GET    | Get by student       | session | No            |
| `/api/invoices/overdue`           | GET    | Get overdue invoices | session | No            |
| `/api/invoices/{id}/pdf`          | GET    | Generate PDF         | session | No            |
| `/api/invoices/{id}/cancel`       | PUT    | Cancel invoice       | session | No            |

### 5. Classroom API (`/api/classrooms`)

| Endpoint               | Method | Description          | Auth    | Flutter Ready |
| ---------------------- | ------ | -------------------- | ------- | ------------- |
| `/api/classrooms`      | GET    | List all classrooms  | session | No            |
| `/api/classrooms/{id}` | GET    | Get single classroom | session | No            |

**Note**: Classroom API is incomplete - missing POST, PUT, DELETE operations.

### 6. Dormitory API (`/api/dormitories`)

| Endpoint                     | Method | Description               | Auth | Flutter Ready |
| ---------------------------- | ------ | ------------------------- | ---- | ------------- |
| `/api/dormitories`           | GET    | List all dormitories      | none | Yes           |
| `/api/dormitories/{id}`      | GET    | Get single dormitory      | none | Yes           |
| `/api/dormitories/available` | GET    | Get available dormitories | none | Yes           |

**Note**: Dormitory API has no authentication filter, which may be intentional for public access.

### 7. Employee API (`/api/employees`)

| Endpoint              | Method | Description         | Auth    | Flutter Ready |
| --------------------- | ------ | ------------------- | ------- | ------------- |
| `/api/employees`      | GET    | List all employees  | session | No            |
| `/api/employees/{id}` | GET    | Get single employee | session | No            |

**Note**: Employee API is incomplete - missing POST, PUT, DELETE operations.

### 8. Notification API (`/notifications/api`)

| Endpoint                            | Method | Description           | Auth    | Flutter Ready |
| ----------------------------------- | ------ | --------------------- | ------- | ------------- |
| `/notifications/api/unread-count`   | GET    | Get unread count      | session | No            |
| `/notifications/api/list`           | GET    | Get notification list | session | No            |
| `/notifications/api/mark-read/{id}` | POST   | Mark as read          | session | No            |
| `/notifications/api/mark-all-read`  | POST   | Mark all as read      | session | No            |

### 9. User API (`/api/users`)

| Endpoint     | Method | Description    | Auth    | Flutter Ready |
| ------------ | ------ | -------------- | ------- | ------------- |
| `/api/users` | GET    | List all users | session | No            |

**Note**: User API is very incomplete.

### 10. Messaging API (`/messages/api`)

| Endpoint                      | Method | Description       | Auth    | Flutter Ready |
| ----------------------------- | ------ | ----------------- | ------- | ------------- |
| `/messages/api/conversations` | GET    | Get conversations | session | No            |
| `/messages/api/messages/{id}` | GET    | Get messages      | session | No            |
| `/messages/api/mark-read`     | POST   | Mark as read      | session | No            |
| `/messages/api/users/search`  | GET    | Search users      | session | No            |
| `/messages/api/unread-count`  | GET    | Get unread count  | session | No            |

### 11. Frontend API (`/frontend/api`)

| Endpoint                          | Method | Description           | Auth | Flutter Ready |
| --------------------------------- | ------ | --------------------- | ---- | ------------- |
| `/frontend/api/recent-admissions` | GET    | Get recent admissions | none | Yes           |

---

## RESTful Compliance Analysis

### Issues Found

#### 1. Inconsistent HTTP Methods

```php
// Incorrect: Using POST for delete
$routes->post('delete/(:num)', 'ClassroomController::delete/$1');

// Correct: Should use DELETE
$routes->delete('(:num)', 'ClassroomApiController::delete/$1');
```

#### 2. Non-RESTful Route Naming

```php
// Current naming
/api/admissions/filter/status  // Not RESTful

// Better approach
/api/admissions?status=pending  // Query parameter approach
```

#### 3. Mixed Response Formats

Some controllers return different response structures:

```json
// Some endpoints
{"status": "success", "data": [...]}

// Others
{"success": true, "admissions": [...]}
```

#### 4. Missing HTTP Status Codes

- Not using 201 for resource creation
- Not using 204 for successful deletion with no content
- Inconsistent error codes

### RESTful Compliance Score by Module

| Module       | Compliance | Issues                              |
| ------------ | ---------- | ----------------------------------- |
| Admission    | 85%        | Uses session filter                 |
| Program      | 90%        | Uses session filter                 |
| Payment      | 85%        | Uses session filter                 |
| Invoice      | 85%        | Uses session filter                 |
| Classroom    | 40%        | Missing CRUD, uses session          |
| Dormitory    | 70%        | No auth filter (may be intentional) |
| Employee     | 40%        | Missing CRUD, uses session          |
| Notification | 75%        | Uses session filter                 |
| User         | 20%        | Very incomplete                     |
| Messaging    | 60%        | Uses session filter                 |

---

## Missing API Endpoints for Flutter

### Critical Missing Endpoints

#### 1. Authentication Module (NEW)

```
POST   /api/auth/login           - Login with email/password
POST   /api/auth/register        - Register new user
POST   /api/auth/logout          - Logout and invalidate token
POST   /api/auth/refresh         - Refresh access token
GET    /api/auth/user            - Get current authenticated user
POST   /api/auth/password/forgot - Request password reset
POST   /api/auth/password/reset  - Reset password with token
```

#### 2. Student Module (NEW)

```
GET    /api/students             - List all students
GET    /api/students/{id}        - Get student details
GET    /api/students/profile     - Get current student profile
PUT    /api/students/profile     - Update student profile
GET    /api/students/{id}/invoices - Get student invoices
GET    /api/students/{id}/payments - Get student payments
GET    /api/students/{id}/classes - Get student classes
```

#### 3. Dashboard Module (NEW)

```
GET    /api/dashboard/stats      - Get dashboard statistics
GET    /api/dashboard/summary    - Get financial summary
GET    /api/dashboard/activities - Get recent activities
```

#### 4. Profile/Account Module (NEW)

```
GET    /api/profile              - Get current user profile
PUT    /api/profile              - Update profile
POST   /api/profile/avatar       - Upload avatar
PUT    /api/profile/password     - Change password
```

#### 5. File Upload Module (NEW)

```
POST   /api/upload               - General file upload
POST   /api/upload/image         - Image upload
POST   /api/upload/document      - Document upload
```

### Missing CRUD Operations

| Module    | Missing Operations              |
| --------- | ------------------------------- |
| Classroom | POST, PUT, DELETE               |
| Employee  | POST, PUT, DELETE               |
| User      | GET (single), POST, PUT, DELETE |
| Dormitory | POST, PUT, DELETE (admin)       |

---

## Recommendations for Flutter Development

### Phase 1: Authentication Setup (Critical)

1. **Create API Authentication Controller**
   - Implement token-based authentication using CodeIgniter Shield
   - Use `tokens` filter instead of `session` for API routes

2. **Required Changes in Routes**

```php
// Add to app/Config/Routes.php

// API Authentication Routes
$routes->group('api/auth', ['namespace' => 'App\Controllers\Api'], function($routes) {
    $routes->post('login', 'AuthApiController::login');
    $routes->post('register', 'AuthApiController::register');
    $routes->post('logout', 'AuthApiController::logout');
    $routes->post('refresh', 'AuthApiController::refresh');
    $routes->get('user', 'AuthApiController::user');
    $routes->post('password/forgot', 'AuthApiController::forgotPassword');
    $routes->post('password/reset', 'AuthApiController::resetPassword');
});
```

### Phase 2: Convert Existing APIs

1. **Create Token-Protected API Routes**

```php
// Example for Admission API
$routes->group('api/v1/admissions', [
    'namespace' => 'Modules\Admission\Controllers\Api',
    'filter' => 'tokens'  // Use token auth instead of session
], function($routes) {
    $routes->get('/', 'AdmissionApiController::index');
    $routes->get('(:segment)', 'AdmissionApiController::show/$1');
    $routes->post('/', 'AdmissionApiController::create');
    $routes->put('(:segment)', 'AdmissionApiController::update/$1');
    $routes->delete('(:segment)', 'AdmissionApiController::delete/$1');
    $routes->get('search', 'AdmissionApiController::search');
    $routes->get('filter', 'AdmissionApiController::filter');
});
```

### Phase 3: Create Missing APIs

1. **Student API Controller**
2. **Dashboard API Controller**
3. **Profile API Controller**
4. **File Upload Controller**

### Phase 4: Standardize Response Format

```json
// Success Response
{
    "status": "success",
    "data": {...},
    "message": "Operation completed successfully"
}

// Error Response
{
    "status": "error",
    "message": "Error description",
    "errors": {
        "field": ["Validation error message"]
    }
}

// Paginated Response
{
    "status": "success",
    "data": [...],
    "meta": {
        "current_page": 1,
        "per_page": 10,
        "total": 100,
        "total_pages": 10
    }
}
```

---

## Complete API Reference

### Response Headers

```
Content-Type: application/json
Authorization: Bearer {token}  (for protected routes)
```

### Error Codes

| Code | Description                      |
| ---- | -------------------------------- |
| 200  | Success                          |
| 201  | Created                          |
| 204  | No Content (successful deletion) |
| 400  | Bad Request                      |
| 401  | Unauthorized                     |
| 403  | Forbidden                        |
| 404  | Not Found                        |
| 422  | Validation Error                 |
| 500  | Internal Server Error            |

### Pagination Parameters

| Parameter | Type | Default | Description    |
| --------- | ---- | ------- | -------------- |
| page      | int  | 1       | Page number    |
| per_page  | int  | 10      | Items per page |

### Filter Parameters

| Parameter  | Description        |
| ---------- | ------------------ |
| q          | Search keyword     |
| status     | Filter by status   |
| category   | Filter by category |
| start_date | Start date filter  |
| end_date   | End date filter    |

---

## Data Models

### Admission

```json
{
  "id": 1,
  "registration_number": "ADM-2026-001",
  "profile_id": 1,
  "program_id": "uuid",
  "status": "pending|approved|rejected",
  "application_date": "2026-02-01",
  "documents": [],
  "created_at": "2026-02-01 10:00:00",
  "updated_at": "2026-02-01 10:00:00",
  "profile": {
    "full_name": "John Doe",
    "email": "john@example.com",
    "phone": "1234567890"
  },
  "program": {
    "title": "Program Name"
  }
}
```

### Program

```json
{
  "id": "uuid",
  "title": "Program Name",
  "description": "Description",
  "category": "Category",
  "sub_category": "Sub Category",
  "status": "active|inactive",
  "registration_fee": 500.0,
  "tuition_fee": 5000.0,
  "discount": 10.0,
  "duration": "6 months",
  "features": [],
  "facilities": [],
  "thumbnail": "path/to/image"
}
```

### Invoice

```json
{
  "id": 1,
  "invoice_number": "INV-2026-001",
  "registration_number": "ADM-2026-001",
  "description": "Invoice description",
  "amount": 500.0,
  "due_date": "2026-02-15",
  "invoice_type": "registration_fee|tuition_fee|miscellaneous_fee",
  "status": "unpaid|paid|cancelled|overdue",
  "items": [],
  "payments": []
}
```

### Payment

```json
{
  "id": 1,
  "registration_number": "ADM-2026-001",
  "invoice_id": 1,
  "amount": 500.0,
  "payment_method": "cash|bank_transfer",
  "document_number": "TRX-123",
  "payment_date": "2026-02-01",
  "status": "pending|paid|failed|refunded",
  "receipt_file": "path/to/receipt"
}
```

### Classroom

```json
{
  "id": 1,
  "title": "Class Name",
  "program": "Program Name",
  "batch": "2026",
  "instructor": "Instructor Name",
  "schedule": [],
  "members": [],
  "status": "active|inactive"
}
```

### Dormitory

```json
{
  "id": 1,
  "name": "Dormitory Name",
  "building": "Building A",
  "floor": 1,
  "room_number": "101",
  "capacity": 4,
  "occupancy": 2,
  "status": "available|full|maintenance"
}
```

---

## Implementation Priority

### High Priority (Required for MVP)

1. Authentication API (token-based)
2. Student API
3. Profile API
4. Convert existing APIs to use token auth

### Medium Priority

1. Dashboard API
2. File Upload API
3. Complete CRUD for Classroom, Employee, Dormitory

### Low Priority

1. Messaging API improvements
2. Notification API improvements
3. Advanced filtering and search

---

## Next Steps

1. **Review and approve this documentation**
2. **Switch to Code mode** to implement:
   - Authentication API Controller
   - Token-protected API routes
   - Missing API endpoints
3. **Test APIs with Postman/Insomnia**
4. **Update this documentation as APIs are implemented**

---

**Document Version**: 1.0.0  
**Last Updated**: February 18, 2026  
**Author**: Kilo Code Architect
