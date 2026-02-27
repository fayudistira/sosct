# ERP System API Documentation

## Table of Contents

- [Overview](#overview)
- [Authentication](#authentication)
- [Response Format](#response-format)
- [Error Handling](#error-handling)
- [API Endpoints](#api-endpoints)
  - [Authentication](#authentication-api)
  - [Users](#users-api)
  - [Admissions](#admissions-api)
  - [Students](#students-api)
  - [Programs](#programs-api)
  - [Payments](#payments-api)
  - [Invoices](#invoices-api)
  - [Installments](#installments-api)
  - [Dormitories](#dormitories-api)
  - [Classrooms](#classrooms-api)
  - [Employees](#employees-api)
  - [Profiles](#profiles-api)
  - [Messages](#messages-api)
  - [Notifications](#notifications-api)
  - [Dashboard](#dashboard-api)

---

## Overview

Base URL: `http://your-domain.com/api`

All API endpoints return JSON responses and follow RESTful conventions.

**Note:** Most endpoints require authentication (except login, register, and public endpoints).

---

## Authentication

### Using Bearer Token

This API uses CodeIgniter Shield's Token authentication. To access protected endpoints:

1. **Login** to get an access token
2. Include the token in the `Authorization` header

```bash
curl -X GET http://your-domain.com/api/users \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### Token Generation

Tokens are generated when you login. They are valid for 1 year by default.

---

## Response Format

### Success Response

```json
{
  "status": "success",
  "data": { ... },
  "message": "Optional success message"
}
```

### Error Response

```json
{
  "status": "error",
  "message": "Error description",
  "errors": { ... }
}
```

### Pagination Response

```json
{
  "status": "success",
  "data": [ ... ],
  "pagination": {
    "current_page": 1,
    "total_pages": 10,
    "per_page": 10,
    "total": 95
  }
}
```

---

## Error Handling

### HTTP Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## API Endpoints

### Authentication API

#### 1. Login

**Endpoint:** `POST /api/auth/login`

**Request Body:**

```json
{
  "email": "user@example.com",
  "password": "your-password"
}
```

**Example Response:**

```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "email": "user@example.com",
      "username": "user123",
      "groups": ["admin"],
      "permissions": []
    },
    "access_token": "abc123...",
    "token_type": "Bearer",
    "token_expiry": "2027-02-27 03:56:06"
  }
}
```

---

#### 2. Register

**Endpoint:** `POST /api/auth/register`

**Request Body:**

```json
{
  "email": "newuser@example.com",
  "password": "password123",
  "password_confirm": "password123"
}
```

---

#### 3. Logout

**Endpoint:** `POST /api/auth/logout`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 4. Get Current User

**Endpoint:** `GET /api/auth/me`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 5. Refresh Token

**Endpoint:** `POST /api/auth/refresh`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 6. Change Password

**Endpoint:** `POST /api/auth/change-password`

**Headers:** `Authorization: Bearer TOKEN`

**Request Body:**

```json
{
  "current_password": "old-password",
  "new_password": "new-password",
  "new_password_confirm": "new-password"
}
```

---

### Users API

#### 1. List Users

**Endpoint:** `GET /api/users`

**Query Parameters:**

- `page` (optional) - Page number
- `per_page` (optional) - Items per page (default: 10)
- `q` (optional) - Search keyword
- `status` (optional) - Filter by status (active/inactive)
- `role` (optional) - Filter by role

**Headers:** `Authorization: Bearer TOKEN`

---

#### 2. Get User

**Endpoint:** `GET /api/users/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 3. Create User

**Endpoint:** `POST /api/users`

**Headers:** `Authorization: Bearer TOKEN`

**Request Body:**

```json
{
  "email": "user@example.com",
  "password": "password123",
  "username": "username",
  "groups": ["user"],
  "active": true
}
```

---

#### 4. Update User

**Endpoint:** `PUT /api/users/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 5. Delete User

**Endpoint:** `DELETE /api/users/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 6. Activate User

**Endpoint:** `PUT /api/users/{id}/activate`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 7. Deactivate User

**Endpoint:** `PUT /api/users/{id}/deactivate`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 8. Assign User to Group

**Endpoint:** `POST /api/users/{id}/assign-group`

**Headers:** `Authorization: Bearer TOKEN`

**Request Body:**

```json
{
  "group": "admin"
}
```

---

#### 9. Get User Statistics

**Endpoint:** `GET /api/users/statistics`

**Headers:** `Authorization: Bearer TOKEN`

---

### Admissions API

#### 1. List Admissions

**Endpoint:** `GET /api/admissions`

**Query Parameters:**

- `page`, `per_page`, `q`, `status`, `sort`, `order`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 2. Get Admission

**Endpoint:** `GET /api/admissions/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 3. Create Admission

**Endpoint:** `POST /api/admissions`

**Headers:** `Authorization: Bearer TOKEN`

**Request Body:**

```json
{
  "profile_id": 1,
  "program_id": 1,
  "full_name": "John Doe",
  "email": "john@example.com",
  "phone": "1234567890"
}
```

---

#### 4. Update Admission

**Endpoint:** `PUT /api/admissions/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 5. Delete Admission

**Endpoint:** `DELETE /api/admissions/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 6. Approve Admission

**Endpoint:** `POST /api/admissions/{id}/approve`

**Headers:** `Authorization: Bearer TOKEN`

**Request Body:**

```json
{
  "notes": "Approved for enrollment"
}
```

---

#### 7. Reject Admission

**Endpoint:** `POST /api/admissions/{id}/reject`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 8. Promote to Student

**Endpoint:** `POST /api/admissions/{id}/promote`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 9. Search Admissions

**Endpoint:** `GET /api/admissions/search?q={keyword}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 10. Get Admission Statistics

**Endpoint:** `GET /api/admissions/statistics`

**Headers:** `Authorization: Bearer TOKEN`

---

### Students API

#### 1. List Students

**Endpoint:** `GET /api/students`

**Query Parameters:**

- `page`, `per_page`, `q`, `status`, `program_id`, `batch`, `sort`, `order`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 2. Get Student

**Endpoint:** `GET /api/students/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 3. Get Student by Number

**Endpoint:** `GET /api/students/number/{student_number}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 4. Update Student

**Endpoint:** `PUT /api/students/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 5. Delete Student

**Endpoint:** `DELETE /api/students/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 6. Get Student Payments

**Endpoint:** `GET /api/students/{id}/payments`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 7. Get Student Invoices

**Endpoint:** `GET /api/students/{id}/invoices`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 8. Get Student Installments

**Endpoint:** `GET /api/students/{id}/installments`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 9. Get Current User's Student Profile

**Endpoint:** `GET /api/students/me`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 10. Get Student Statistics

**Endpoint:** `GET /api/students/statistics`

**Headers:** `Authorization: Bearer TOKEN`

---

### Programs API

#### 1. List Programs

**Endpoint:** `GET /api/programs`

**Query Parameters:**

- `page`, `per_page`, `q`, `status`, `category`, `language`, `language_level`, `sort`, `order`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 2. Get Program

**Endpoint:** `GET /api/programs/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 3. Create Program

**Endpoint:** `POST /api/programs`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 4. Update Program

**Endpoint:** `PUT /api/programs/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 5. Delete Program

**Endpoint:** `DELETE /api/programs/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 6. Get Active Programs

**Endpoint:** `GET /api/programs/active`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 7. Get Program Categories

**Endpoint:** `GET /api/programs/categories`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 8. Get Program Languages

**Endpoint:** `GET /api/programs/languages`

**Headers:** `Authorization: Bearer TOKEN`

---

### Payments API

#### 1. List Payments

**Endpoint:** `GET /api/payments`

**Query Parameters:**

- `page`, `per_page`, `q`, `status`, `method`, `start_date`, `end_date`, `sort`, `order`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 2. Get Payment

**Endpoint:** `GET /api/payments/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 3. Create Payment

**Endpoint:** `POST /api/payments`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 4. Update Payment

**Endpoint:** `PUT /api/payments/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 5. Update Payment Status

**Endpoint:** `PUT /api/payments/{id}/status`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 6. Get Student Payments

**Endpoint:** `GET /api/payments/student/{registration_number}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 7. Get Payment Statistics

**Endpoint:** `GET /api/payments/statistics`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 8. Upload Receipt

**Endpoint:** `POST /api/payments/{id}/receipt`

**Headers:** `Authorization: Bearer TOKEN`

---

### Invoices API

#### 1. List Invoices

**Endpoint:** `GET /api/invoices`

**Query Parameters:**

- `page`, `per_page`, `status`, `type`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 2. Get Invoice

**Endpoint:** `GET /api/invoices/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 3. Create Invoice

**Endpoint:** `POST /api/invoices`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 4. Update Invoice

**Endpoint:** `PUT /api/invoices/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 5. Delete Invoice

**Endpoint:** `DELETE /api/invoices/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 6. Get Student Invoices

**Endpoint:** `GET /api/invoices/student/{registration_number}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 7. Get Overdue Invoices

**Endpoint:** `GET /api/invoices/overdue`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 8. Generate Invoice PDF

**Endpoint:** `GET /api/invoices/{id}/pdf`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 9. Cancel Invoice

**Endpoint:** `PUT /api/invoices/{id}/cancel`

**Headers:** `Authorization: Bearer TOKEN`

---

### Installments API

#### 1. List Installments

**Endpoint:** `GET /api/installments`

**Query Parameters:**

- `page`, `per_page`, `status`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 2. Get Installment

**Endpoint:** `GET /api/installments/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 3. Update Installment

**Endpoint:** `PUT /api/installments/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 4. Get Student Installments

**Endpoint:** `GET /api/installments/student/{registration_number}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 5. Get Installment Statistics

**Endpoint:** `GET /api/installments/statistics`

**Headers:** `Authorization: Bearer TOKEN`

---

### Dormitories API

#### 1. List Dormitories

**Endpoint:** `GET /api/dormitories`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 2. Get Available Dormitories

**Endpoint:** `GET /api/dormitories/available`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 3. Get Dormitory

**Endpoint:** `GET /api/dormitories/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 4. Create Dormitory

**Endpoint:** `POST /api/dormitories`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 5. Update Dormitory

**Endpoint:** `PUT /api/dormitories/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 6. Delete Dormitory

**Endpoint:** `DELETE /api/dormitories/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 7. Assign Student to Dormitory

**Endpoint:** `POST /api/dormitories/{id}/assign`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 8. Unassign Student from Dormitory

**Endpoint:** `POST /api/dormitories/{id}/unassign`

**Headers:** `Authorization: Bearer TOKEN`

---

### Classrooms API

#### 1. List Classrooms

**Endpoint:** `GET /api/classrooms`

**Query Parameters:**

- `page`, `per_page`, `q`, `status`, `sort`, `order`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 2. Get Classroom

**Endpoint:** `GET /api/classrooms/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 3. Create Classroom

**Endpoint:** `POST /api/classrooms`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 4. Update Classroom

**Endpoint:** `PUT /api/classrooms/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 5. Delete Classroom

**Endpoint:** `DELETE /api/classrooms/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

### Employees API

#### 1. List Employees

**Endpoint:** `GET /api/employees`

**Query Parameters:**

- `page`, `per_page`, `q`, `status`, `department`, `sort`, `order`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 2. Get Employee

**Endpoint:** `GET /api/employees/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 3. Create Employee

**Endpoint:** `POST /api/employees`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 4. Update Employee

**Endpoint:** `PUT /api/employees/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 5. Delete Employee

**Endpoint:** `DELETE /api/employees/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

### Profiles API

#### 1. List Profiles

**Endpoint:** `GET /api/profiles`

**Query Parameters:**

- `page`, `per_page`, `q`, `sort`, `order`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 2. Get Profile

**Endpoint:** `GET /api/profiles/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 3. Create Profile

**Endpoint:** `POST /api/profiles`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 4. Update Profile

**Endpoint:** `PUT /api/profiles/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 5. Delete Profile

**Endpoint:** `DELETE /api/profiles/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 6. Get Current User's Profile

**Endpoint:** `GET /api/profiles/me`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 7. Update Current User's Profile

**Endpoint:** `PUT /api/profiles/me`

**Headers:** `Authorization: Bearer TOKEN`

---

### Messages API

#### 1. Get User's Conversations

**Endpoint:** `GET /api/messages/conversations`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 2. Get Conversation with Messages

**Endpoint:** `GET /api/messages/conversations/{id}`

**Query Parameters:**

- `limit` (default: 50)
- `offset` (default: 0)

**Headers:** `Authorization: Bearer TOKEN`

---

#### 3. Create Conversation

**Endpoint:** `POST /api/messages/conversations`

**Headers:** `Authorization: Bearer TOKEN`

**Request Body:**

```json
{
  "type": "private",
  "title": "Conversation Title",
  "participant_ids": [2, 3]
}
```

---

#### 4. Send Message

**Endpoint:** `POST /api/messages/{conversation_id}`

**Headers:** `Authorization: Bearer TOKEN`

**Request Body:**

```json
{
  "message_text": "Hello!"
}
```

---

#### 5. Mark Conversation as Read

**Endpoint:** `POST /api/messages/{conversation_id}/read`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 6. Get Unread Count

**Endpoint:** `GET /api/messages/unread`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 7. Add Participant

**Endpoint:** `POST /api/messages/conversations/{id}/participants`

**Headers:** `Authorization: Bearer TOKEN`

**Request Body:**

```json
{
  "user_id": 5
}
```

---

### Notifications API

#### 1. Get Unread Count

**Endpoint:** `GET /notifications/api/unread-count`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 2. Get Notification List

**Endpoint:** `GET /notifications/api/list`

**Query Parameters:**

- `limit` (default: 10)

**Headers:** `Authorization: Bearer TOKEN`

---

#### 3. Mark Notification as Read

**Endpoint:** `POST /notifications/api/mark-read/{id}`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 4. Mark All as Read

**Endpoint:** `POST /notifications/api/mark-all-read`

**Headers:** `Authorization: Bearer TOKEN`

---

### Dashboard API

#### 1. Get Dashboard Statistics

**Endpoint:** `GET /api/dashboard/stats`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 2. Get Quick Overview

**Endpoint:** `GET /api/dashboard/overview`

**Headers:** `Authorization: Bearer TOKEN`

---

#### 3. Get Recent Admissions

**Endpoint:** `GET /api/dashboard/recent-admissions`

**Query Parameters:**

- `limit` (default: 10)

**Headers:** `Authorization: Bearer TOKEN`

---

#### 4. Get Recent Payments

**Endpoint:** `GET /api/dashboard/recent-payments`

**Query Parameters:**

- `limit` (default: 10)

**Headers:** `Authorization: Bearer TOKEN`

---

#### 5. Get Overdue Invoices

**Endpoint:** `GET /api/dashboard/overdue-invoices`

**Query Parameters:**

- `limit` (default: 10)

**Headers:** `Authorization: Bearer TOKEN`

---

#### 6. Get Revenue Chart

**Endpoint:** `GET /api/dashboard/revenue-chart`

**Query Parameters:**

- `year` (default: current year)

**Headers:** `Authorization: Bearer TOKEN`

---

#### 7. Get Admissions Chart

**Endpoint:** `GET /api/dashboard/admissions-chart`

**Query Parameters:**

- `year` (default: current year)

**Headers:** `Authorization: Bearer TOKEN`

---

## Summary

This API provides comprehensive access to all application features:

| Module         | Endpoints                                             |
| -------------- | ----------------------------------------------------- |
| Authentication | Login, Register, Logout, Me, Refresh, Change Password |
| Users          | CRUD, Activate, Deactivate, Assign Group, Statistics  |
| Admissions     | CRUD, Approve, Reject, Promote, Search, Statistics    |
| Students       | CRUD, Payments, Invoices, Installments, Statistics    |
| Programs       | CRUD, Search, Filter, Categories, Languages           |
| Payments       | CRUD, Status Update, Statistics, Receipt Upload       |
| Invoices       | CRUD, PDF Generation, Cancel, Overdue                 |
| Installments   | CRUD, Student Installments, Statistics                |
| Dormitories    | CRUD, Assign, Unassign, Available                     |
| Classrooms     | CRUD                                                  |
| Employees      | CRUD                                                  |
| Profiles       | CRUD, Me, Update Me                                   |
| Messages       | Conversations, Messages, Send, Mark Read              |
| Notifications  | List, Mark Read, Mark All Read                        |
| Dashboard      | Stats, Overview, Charts, Recent Data                  |
