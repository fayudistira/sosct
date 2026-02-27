# SOSCT Application Flow Documentation

## Overview

This document describes the complete application flow for the SOSCT (Student Online System for CT) ERP system, starting from when an applicant submits an admission form until the student graduates or leaves the system.

---

## Flow Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                           ADMISSION FLOW                                        │
└─────────────────────────────────────────────────────────────────────────────────┘

    ┌──────────────┐
    │   Applicant  │
    │ Submits Form │
    └──────┬───────┘
           │
           ▼
┌──────────────────────────────┐
│    Create Admission Record   │
│    Status: PENDING          │
└──────────────┬───────────────┘
               │
               ▼
    ┌─────────────────────┐
    │   Staff Reviews     │
    │   Application       │
    └──────────┬──────────┘
               │
       ┌───────┴───────┐
       ▼               ▼
┌─────────────┐   ┌────────────┐
│   APPROVE   │   │  REJECT    │
└──────┬──────┘   └─────┬──────┘
       │                │
       ▼                ▼
┌─────────────────────────────────┐
│    Promote to Student           │
│    1. Create User Account       │
│    2. Create Profile Link       │
│    3. Create Student Record    │
└──────────────┬──────────────────┘
               │
               ▼
    ┌─────────────────────┐
    │   Payment Setup     │
    │   - Create Invoice  │
    │   - Setup Installments│
    └──────────┬──────────┘
               │
               ▼
    ┌─────────────────────┐
    │   Student Active   │
    │   in System        │
    └─────────────────────┘
```

---

## Step-by-Step Flow

### Phase 1: Admission Application

#### 1.1 Applicant Submits Form

- **API Endpoint:** `POST /api/admissions`
- **Frontend Action:** Applicant fills out admission form
- **Data Required:**
  ```json
  {
    "profile_id": 1,
    "program_id": 1,
    "full_name": "John Doe",
    "email": "john@example.com",
    "phone": "1234567890",
    "date_of_birth": "2000-01-15",
    "gender": "male",
    "address": "123 Main St",
    "citizen_id": "1234567890",
    "previous_education": "High School"
  }
  ```
- **Result:** Creates admission record with status `pending`

#### 1.2 Admission Created

- **Registration Number Generated:** `ADM-2026-0001`
- **Status:** `pending`
- **Application Date:** Current date

---

### Phase 2: Admission Review

#### 2.1 Staff Reviews Application

- **API Endpoint:** `GET /api/admissions/{id}`
- **Frontend Action:** Staff views admission details
- **Checklist:**
  - Verify documents
  - Check eligibility
  - Verify program availability

#### 2.2 Approve Application

- **API Endpoint:** `POST /api/admissions/{id}/approve`
- **Request Body:**
  ```json
  {
    "notes": "All documents verified. Eligible for enrollment."
  }
  ```
- **Result:**
  - Status changes to `approved`
  - `reviewed_date` set to current datetime

#### 2.3 Reject Application (Alternative)

- **API Endpoint:** `POST /api/admissions/{id}/reject`
- **Request Body:**
  ```json
  {
    "notes": "Incomplete documents. Does not meet eligibility criteria."
  }
  ```
- **Result:**
  - Status changes to `rejected`
  - `reviewed_date` set to current datetime

---

### Phase 3: Promote to Student

#### 3.1 Staff Promotes Admission

- **API Endpoint:** `POST /api/admissions/{id}/promote`
- **Process:**
  1. Create User Account (Shield)
     - Username: citizen_id or phone
     - Password: phone number
     - Group: `student`
     - Status: `active`
  2. Update Profile with user_id
  3. Create Student Record
     - Student Number: `STU-2026-0001`
     - Status: `active`
     - Enrollment Date: Current date

- **Response:**
  ```json
  {
    "status": "success",
    "message": "Admission promoted to student successfully",
    "data": {
      "student_number": "STU-2026-0001",
      "username": "1234567890",
      "password": "1234567890"
    }
  }
  ```

#### 3.2 Student Logs In

- **API Endpoint:** `POST /api/auth/login`
- **Credentials:** Username = citizen_id, Password = phone
- **Token:** Receives Bearer token for API access

---

### Phase 4: Payment & Enrollment

#### 4.1 Invoice Creation

- **API Endpoint:** `POST /api/invoices`
- **Data:**
  ```json
  {
    "registration_number": "STU-2026-0001",
    "profile_id": 1,
    "program_id": 1,
    "invoice_type": "tuition",
    "total_amount": 5000000,
    "due_date": "2026-03-15"
  }
  ```

#### 4.2 Installment Setup (Optional)

- **API Endpoint:** `POST /api/installments`
- **Creates payment schedule for student**

#### 4.3 Payment Processing

- **API Endpoint:** `POST /api/payments`
- **Data:**
  ```json
  {
    "registration_number": "STU-2026-0001",
    "invoice_id": 1,
    "amount": 500000,
    "payment_date": "2026-03-01",
    "payment_method": "bank_transfer",
    "status": "paid"
  }
  ```

---

### Phase 5: Student Lifecycle

#### 5.1 Classroom Assignment

- **API Endpoint:** `POST /api/classrooms`
- **Student assigned to program batch/class**

#### 5.2 Dormitory Assignment (Optional)

- **API Endpoint:** `POST /api/dormitories/{id}/assign`
- **For boarding students**

---

## Complete API Call Sequence

### From Admission to Active Student

```bash
# 1. Submit Admission Application
curl -X POST http://localhost:8080/api/admissions \
  -H "Content-Type: application/json" \
  -d '{
    "profile_id": 1,
    "program_id": 1,
    "full_name": "John Doe",
    "email": "john@example.com",
    "phone": "1234567890"
  }'

# Response: { "status": "success", "data": { "id": 1, "registration_number": "ADM-2026-0001", "status": "pending" } }

# 2. Review Admission (Staff logs in first)
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@example.com", "password": "admin123"}'

# Response: { "access_token": "xxx" }

# 3. Approve Admission
curl -X POST http://localhost:8080/api/admissions/1/approve \
  -H "Authorization: Bearer xxx" \
  -H "Content-Type: application/json" \
  -d '{"notes": "Approved for enrollment"}'

# Response: { "status": "success", "data": { "id": 1, "status": "approved" } }

# 4. Promote to Student
curl -X POST http://localhost:8080/api/admissions/1/promote \
  -H "Authorization: Bearer xxx"

# Response: { "status": "success", "data": { "student_number": "STU-2026-0001" } }

# 5. Create Invoice for Student
curl -X POST http://localhost:8080/api/invoices \
  -H "Authorization: Bearer xxx" \
  -H "Content-Type: application/json" \
  -d '{
    "registration_number": "STU-2026-0001",
    "invoice_type": "tuition",
    "total_amount": 5000000,
    "due_date": "2026-04-01"
  }'

# 6. Record Payment
curl -X POST http://localhost:8080/api/payments \
  -H "Authorization: Bearer xxx" \
  -H "Content-Type: application/json" \
  -d '{
    "registration_number": "STU-2026-0001",
    "invoice_id": 1,
    "amount": 500000,
    "payment_method": "bank_transfer",
    "status": "paid"
  }'

# 7. Student Can Now Login
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "john@example.com", "password": "1234567890"}'

# Response: { "access_token": "student_token", "data": { "groups": ["student"] } }

# 8. Student Views Their Profile
curl -X GET http://localhost:8080/api/students/me \
  -H "Authorization: Bearer student_token"

# 9. Student Views Their Invoices
curl -X GET http://localhost:8080/api/invoices/student/STU-2026-0001 \
  -H "Authorization: Bearer student_token"

# 10. Student Views Their Payments
curl -X GET http://localhost:8080/api/payments/student/STU-2026-0001 \
  -H "Authorization: Bearer student_token"
```

---

## User Roles & Permissions

| Role       | Permissions                          |
| ---------- | ------------------------------------ |
| Superadmin | Full system access                   |
| Admin      | Manage admissions, students, reports |
| Staff      | Process admissions, view reports     |
| Teacher    | View classrooms, students            |
| Student    | View own profile, payments, invoices |
| Frontline  | Handle admissions, inquiries         |

---

## Common Workflows

### Workflow 1: New Student Enrollment

```
1. Frontend submits admission form → POST /api/admissions
2. Staff reviews → GET /api/admissions/{id}
3. Staff approves → POST /api/admissions/{id}/approve
4. Admin promotes to student → POST /api/admissions/{id}/promote
5. Admin creates invoice → POST /api/invoices
6. Student makes payment → POST /api/payments
7. Student logs in → POST /api/auth/login
8. Student views profile → GET /api/students/me
```

### Workflow 2: Payment Processing

```
1. Student logs in → POST /api/auth/login
2. Student views invoices → GET /api/invoices/student/{reg_number}
3. Student makes payment → POST /api/payments
4. Admin verifies → GET /api/payments/{id}
5. Admin updates status → PUT /api/payments/{id}/status
```

### Workflow 3: Program Switching

```
1. Student requests switch → (Frontend handles request)
2. Staff reviews → GET /api/admissions/{id}
3. Staff processes switch → POST /api/admissions/{id}/switch
4. New program invoice created → POST /api/invoices
5. Payment processed → POST /api/payments
```

---

## Status Values Reference

### Admission Status

- `pending` - Awaiting review
- `approved` - Approved, awaiting promotion
- `rejected` - Not accepted

### Student Status

- `active` - Currently enrolled
- `inactive` - Temporarily inactive
- `graduated` - Completed program
- `dropped` - Left program
- `suspended` - Temporarily suspended

### Invoice Status

- `unpaid` - Payment due
- `paid` - Fully paid
- `partial` - Partially paid
- `cancelled` - Cancelled

### Payment Status

- `pending` - Awaiting verification
- `paid` - Verified
- `failed` - Failed
- `refunded` - Refunded

---

## Error Handling

All API errors return consistent format:

```json
{
  "status": "error",
  "message": "Human readable error message",
  "errors": {
    "field": "Field specific error"
  }
}
```

Common HTTP Status Codes:

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error
