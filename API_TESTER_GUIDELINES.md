# API Tester Guidelines

This document provides guidelines for testing the SOSCT application APIs using the built-in API Tester tool.

## Access

Open your browser and navigate to:

```
/tools/api-tester
```

Or access from the Tools menu at `/tools` and click on "API Tester".

---

## Quick Start

### 1. Testing Public Endpoints (No Authentication)

**Example: Login API**

| Field   | Value                                                       |
| ------- | ----------------------------------------------------------- |
| Method  | `POST`                                                      |
| URL     | `http://localhost:8080/api/auth/login`                      |
| Headers | `{"Content-Type": "application/json"}`                      |
| Body    | `{"email": "admin@example.com", "password": "password123"}` |

**Steps:**

1. Select `POST` from the method dropdown
2. Enter the URL
3. In the Headers tab, ensure `Content-Type: application/json` is set
4. Switch to the Body tab
5. Enter the JSON payload
6. Click **🚀 Send Request**
7. Check the response for:
   - Status code (200 = success)
   - Token in the response (for login)

---

### 2. Testing Protected Endpoints (With Authentication)

Most API endpoints require authentication via Bearer token.

**Step 1: Get a Token**

| Field   | Value                                                       |
| ------- | ----------------------------------------------------------- |
| Method  | `POST`                                                      |
| URL     | `http://localhost:8080/api/auth/login`                      |
| Headers | `{"Content-Type": "application/json"}`                      |
| Body    | `{"email": "admin@example.com", "password": "password123"}` |

Copy the `token` value from the response.

**Step 2: Use Token for Protected Requests**

| Field    | Value                                   |
| -------- | --------------------------------------- |
| Method   | `GET`                                   |
| URL      | `http://localhost:8080/api/profiles/me` |
| Headers  | `{"Content-Type": "application/json"}`  |
| Auth Tab | Select `Bearer Token`, enter your token |

Click **Send Request** - you should get a 200 OK response with profile data.

---

## Available API Endpoints

### Authentication (Public)

| Endpoint             | Method | Description       |
| -------------------- | ------ | ----------------- |
| `/api/auth/login`    | POST   | User login        |
| `/api/auth/register` | POST   | User registration |

### Authentication (Protected)

| Endpoint           | Method | Description      |
| ------------------ | ------ | ---------------- |
| `/api/auth/logout` | POST   | User logout      |
| `/api/auth/me`     | GET    | Get current user |

### Profiles

| Endpoint             | Method | Description              |
| -------------------- | ------ | ------------------------ |
| `/api/profiles`      | GET    | List all profiles        |
| `/api/profiles`      | POST   | Create profile           |
| `/api/profiles/{id}` | GET    | Get profile by ID        |
| `/api/profiles/{id}` | PUT    | Update profile           |
| `/api/profiles/{id}` | DELETE | Delete profile           |
| `/api/profiles/me`   | GET    | Get current user profile |

### Programs

| Endpoint             | Method | Description       |
| -------------------- | ------ | ----------------- |
| `/api/programs`      | GET    | List all programs |
| `/api/programs`      | POST   | Create program    |
| `/api/programs/{id}` | GET    | Get program by ID |
| `/api/programs/{id}` | PUT    | Update program    |
| `/api/programs/{id}` | DELETE | Delete program    |

### Admissions

| Endpoint               | Method | Description         |
| ---------------------- | ------ | ------------------- |
| `/api/admissions`      | GET    | List all admissions |
| `/api/admissions`      | POST   | Create admission    |
| `/api/admissions/{id}` | GET    | Get admission by ID |
| `/api/admissions/{id}` | PUT    | Update admission    |
| `/api/admissions/{id}` | DELETE | Delete admission    |

### Payments

| Endpoint             | Method | Description       |
| -------------------- | ------ | ----------------- |
| `/api/payments`      | GET    | List all payments |
| `/api/payments`      | POST   | Create payment    |
| `/api/payments/{id}` | GET    | Get payment by ID |
| `/api/payments/{id}` | PUT    | Update payment    |
| `/api/payments/{id}` | DELETE | Delete payment    |

### Invoices

| Endpoint                 | Method | Description       |
| ------------------------ | ------ | ----------------- |
| `/api/invoices`          | GET    | List all invoices |
| `/api/invoices`          | POST   | Create invoice    |
| `/api/invoices/{id}`     | GET    | Get invoice by ID |
| `/api/invoices/{id}`     | PUT    | Update invoice    |
| `/api/invoices/{id}`     | DELETE | Delete invoice    |
| `/api/invoices/{id}/pay` | POST   | Pay invoice       |

### Installments

| Endpoint                     | Method | Description           |
| ---------------------------- | ------ | --------------------- |
| `/api/installments`          | GET    | List all installments |
| `/api/installments/{id}`     | GET    | Get installment by ID |
| `/api/installments/{id}/pay` | POST   | Pay installment       |

### Students

| Endpoint             | Method | Description       |
| -------------------- | ------ | ----------------- |
| `/api/students`      | GET    | List all students |
| `/api/students`      | POST   | Create student    |
| `/api/students/{id}` | GET    | Get student by ID |
| `/api/students/{id}` | PUT    | Update student    |
| `/api/students/{id}` | DELETE | Delete student    |

### Employees

| Endpoint              | Method | Description        |
| --------------------- | ------ | ------------------ |
| `/api/employees`      | GET    | List all employees |
| `/api/employees`      | POST   | Create employee    |
| `/api/employees/{id}` | GET    | Get employee by ID |
| `/api/employees/{id}` | PUT    | Update employee    |
| `/api/employees/{id}` | DELETE | Delete employee    |

### Classrooms

| Endpoint               | Method | Description         |
| ---------------------- | ------ | ------------------- |
| `/api/classrooms`      | GET    | List all classrooms |
| `/api/classrooms`      | POST   | Create classroom    |
| `/api/classrooms/{id}` | GET    | Get classroom by ID |
| `/api/classrooms/{id}` | PUT    | Update classroom    |
| `/api/classrooms/{id}` | DELETE | Delete classroom    |

### Dormitories

| Endpoint                | Method | Description          |
| ----------------------- | ------ | -------------------- |
| `/api/dormitories`      | GET    | List all dormitories |
| `/api/dormitories`      | POST   | Create dormitory     |
| `/api/dormitories/{id}` | GET    | Get dormitory by ID  |
| `/api/dormitories/{id}` | PUT    | Update dormitory     |
| `/api/dormitories/{id}` | DELETE | Delete dormitory     |

### Dashboard

| Endpoint               | Method | Description              |
| ---------------------- | ------ | ------------------------ |
| `/api/dashboard/stats` | GET    | Get dashboard statistics |

---

## Common Test Cases

### Test 1: User Login

```json
// Headers
{"Content-Type": "application/json"}

// Body
{
  "email": "admin@example.com",
  "password": "password123"
}
```

**Expected Response:**

```json
{
  "success": true,
  "message": "Login successful",
  "user": {
    "id": "1",
    "email": "admin@example.com",
    "username": "admin"
  },
  "token": "eyJ0eXAiOiJKV1Q..."
}
```

### Test 2: Get Profile with Token

```json
// Headers
{"Content-Type": "application/json"}

// Auth
Type: Bearer Token
Value: (paste token from login)
```

**URL:** `GET /api/profiles/me`

### Test 3: Create New Program

```json
// Headers
{
  "Content-Type": "application/json",
  "Authorization": "Bearer YOUR_TOKEN_HERE"
}

// Body
{
  "name": "HSK 1 Chinese",
  "description": "Beginner Chinese course",
  "price": 1500000,
  "duration": 30,
  "language": "Chinese",
  "level": "Beginner"
}
```

### Test 4: Create Invoice

```json
// Headers
{
  "Content-Type": "application/json",
  "Authorization": "Bearer YOUR_TOKEN_HERE"
}

// Body
{
  "student_id": 1,
  "program_id": 1,
  "amount": 1500000,
  "due_date": "2026-03-15",
  "status": "unpaid",
  "items": [
    {
      "description": "HSK 1 Course Fee",
      "quantity": 1,
      "unit_price": 1500000
    }
  ]
}
```

### Test 5: Search Programs

**URL:** `GET /api/programs?search=HSK`

### Test 6: Filter Invoices by Status

**URL:** `GET /api/invoices?status=unpaid`

---

## Troubleshooting

### CORS Errors

If you see CORS errors:

- The API tester runs in browser and makes cross-origin requests
- Ensure the API server has CORS headers configured
- For local development, you may need to disable CORS in your browser temporarily

### 401 Unauthorized

- Token may be expired or invalid
- Re-login to get a new token
- Ensure token is correctly entered in the Auth tab

### 403 Forbidden

- You don't have permission to access this resource
- Check your user role and permissions

### 404 Not Found

- Check the endpoint URL is correct
- Ensure trailing slashes are consistent

### 422 Validation Error

- Check the request body format
- Verify all required fields are present
- Check for JSON syntax errors

### 500 Internal Server Error

- Check the server logs for details
- Verify database connection
- Check required parameters

---

## Tips

1. **Use History**: The API tester saves your request history - click on past requests to reload them
2. **Copy Response**: Use the copy button to quickly copy responses
3. **Check Timing**: Response time is shown below the status
4. **JSON Validation**: The tool validates JSON in headers and body
5. **Multiple Auth Methods**: Supports Bearer Token, Basic Auth, and API Key

---

## Testing External APIs

You can also use this tool to test external APIs:

```json
// Example: Test JSONPlaceholder API
Method: GET
URL: https://jsonplaceholder.typicode.com/todos/1
Headers: {}
```

This is useful for testing and debugging API integrations.
