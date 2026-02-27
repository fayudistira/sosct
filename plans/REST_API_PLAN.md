# RESTful API Implementation Plan

## Overview

This plan outlines creating a complete RESTful API for the SOSCT application, making it a backend-only system with a separate frontend.

## Authentication Strategy

**Technology:** CodeIgniter Shield with Access Tokens

- Uses Shield's built-in Token-based authentication
- API clients include `Authorization: Bearer <token>` header
- More secure than API keys, supports token expiration

## Modules & API Endpoints

### 1. Authentication API (`/api/auth`)

- `POST /api/auth/login` - Login with credentials, receive token
- `POST /api/auth/register` - Register new user
- `POST /api/auth/logout` - Invalidate token
- `GET /api/auth/me` - Get current user info

### 2. Users API (`/api/users`) - EXTEND

**Existing:** index (list users with pagination)
**Add:**

- `GET /api/users/{id}` - Get single user
- `POST /api/users` - Create user
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user
- `PUT /api/users/{id}/activate` - Activate user
- `PUT /api/users/{id}/deactivate` - Deactivate user
- `POST /api/users/{id}/assign-group` - Assign user to group
- `POST /api/users/{id}/assign-permission` - Assign permission

### 3. Admission API (`/api/admissions`) - EXTEND

**Existing:** index, show, search, filter
**Add:**

- `POST /api/admissions` - Create admission
- `PUT /api/admissions/{id}` - Update admission
- `DELETE /api/admissions/{id}` - Delete admission
- `POST /api/admissions/{id}/approve` - Approve admission
- `POST /api/admissions/{id}/reject` - Reject admission
- `POST /api/admissions/{id}/promote` - Promote to student

### 4. Student API (`/api/students`) - NEW

- `GET /api/students` - List students with pagination
- `GET /api/students/{id}` - Get single student
- `PUT /api/students/{id}` - Update student
- `DELETE /api/students/{id}` - Delete student
- `GET /api/students/{id}/payments` - Get student payments
- `GET /api/students/{id}/invoices` - Get student invoices

### 5. Program API (`/api/programs`) - EXTEND

**Existing:** Full CRUD + search + filter
**Add:**

- `GET /api/programs/categories` - Get program categories
- `GET /api/programs/active` - Get active programs

### 6. Payment API (`/api/payments`) - COMPLETE

Already has comprehensive endpoints.

### 7. Invoice API (`/api/invoices`) - COMPLETE

Already has comprehensive endpoints.

### 8. Installments API (`/api/installments`) - NEW

- `GET /api/installments` - List installments
- `GET /api/installments/{id}` - Get installment details
- `GET /api/installments/student/{reg_number}` - Get student's installments
- `PUT /api/installments/{id}` - Update installment

### 9. Dormitory API (`/api/dormitories`)**Existing:** index - EXTEND

, available, show
**Add:**

- `POST /api/dormitories` - Create dormitory
- `PUT /api/dormitories/{id}` - Update dormitory
- `DELETE /api/dormitories/{id}` - Delete dormitory
- `POST /api/dormitories/{id}/assign` - Assign student
- `POST /api/dormitories/{id}/unassign` - Unassign student

### 10. Classroom API (`/api/classrooms`) - EXTEND

**Existing:** index, show
**Add:**

- `POST /api/classrooms` - Create classroom
- `PUT /api/classrooms/{id}` - Update classroom
- `DELETE /api/classrooms/{id}` - Delete classroom

### 11. Employee API (`/api/employees`) - EXTEND

**Existing:** index, show
**Add:**

- `POST /api/employees` - Create employee
- `PUT /api/employees/{id}` - Update employee
- `DELETE /api/employees/{id}` - Delete employee

### 12. Account/Profile API (`/api/profiles`) - NEW

- `GET /api/profiles` - List profiles
- `GET /api/profiles/{id}` - Get profile
- `POST /api/profiles` - Create profile
- `PUT /api/profiles/{id}` - Update profile
- `DELETE /api/profiles/{id}` - Delete profile

### 13. Messaging API (`/api/messages`) - NEW

- `GET /api/messages` - List conversations
- `GET /api/messages/{id}` - Get conversation with messages
- `POST /api/messages` - Send message
- `POST /api/messages/conversations` - Create conversation

### 14. Notifications API (`/api/notifications`) - COMPLETE

Already has comprehensive endpoints.

### 15. Dashboard API (`/api/dashboard`) - NEW

- `GET /api/dashboard/stats` - Get dashboard statistics
- `GET /api/dashboard/recent-admissions` - Recent admissions
- `GET /api/dashboard/recent-payments` - Recent payments
- `GET /api/dashboard/overdue-invoices` - Overdue invoices

### 16. Settings API (`/api/settings`) - NEW

- `GET /api/settings` - Get all settings
- `GET /api/settings/{key}` - Get setting by key
- `PUT /api/settings/{key}` - Update setting

## Implementation Order

1. **Create API Authentication Filter** - Shield token-based auth
2. **Create Base API Controller** - Common functionality
3. **Extend Users API**
4. **Extend Admission API**
5. **Create Student API**
6. **Extend Program API**
7. **Create Installments API**
8. **Extend Dormitory API**
9. **Extend Classroom API**
10. **Extend Employee API**
11. **Create Account/Profile API**
12. **Create Messaging API**
13. **Create Dashboard API**
14. **Create Settings API**
15. **Update All Routes**
16. **Create API Documentation**

## API Response Format

### Success

```json
{
  "status": "success",
  "data": { ... },
  "message": "Optional message"
}
```

### Error

```json
{
  "status": "error",
  "message": "Error description",
  "errors": { ... }
}
```

### Pagination

```json
{
  "status": "success",
  "data": [ ... ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 100,
    "total_pages": 10
  }
}
```

## HTTP Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error
