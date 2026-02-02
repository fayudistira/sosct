# ERP System API Documentation

## Table of Contents
- [Overview](#overview)
- [Authentication](#authentication)
- [Response Format](#response-format)
- [Error Handling](#error-handling)
- [Admission API](#admission-api)
- [Program API](#program-api)

---

## Overview

Base URL: `http://your-domain.com/api`

All API endpoints return JSON responses and follow RESTful conventions.

---

## Authentication

Currently, the API endpoints are open. In production, you should implement authentication using:
- Bearer tokens
- API keys
- OAuth 2.0

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
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## Admission API

### 1. List All Admissions

**Endpoint:** `GET /api/admissions`

**Query Parameters:**
- `page` (optional) - Page number (default: 1)
- `per_page` (optional) - Items per page (default: 10)

**Example Request:**
```bash
GET /api/admissions?page=1&per_page=20
```

**Example Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "registration_number": "ADM-2026-001",
      "full_name": "John Doe",
      "email": "john@example.com",
      "phone": "1234567890",
      "date_of_birth": "2000-01-15",
      "gender": "male",
      "address": "123 Main St",
      "program": "Computer Science",
      "previous_education": "High School",
      "status": "pending",
      "application_date": "2026-02-01",
      "documents": ["transcript.pdf", "id.pdf"],
      "created_at": "2026-02-01 10:30:00",
      "updated_at": "2026-02-01 10:30:00"
    }
  ],
  "pagination": {
    "current_page": 1,
    "total_pages": 5,
    "per_page": 20,
    "total": 95
  }
}
```

---

### 2. Get Single Admission

**Endpoint:** `GET /api/admissions/{id}`

**Path Parameters:**
- `id` (required) - Admission ID

**Example Request:**
```bash
GET /api/admissions/1
```

**Example Response:**
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "registration_number": "ADM-2026-001",
    "full_name": "John Doe",
    "email": "john@example.com",
    "phone": "1234567890",
    "date_of_birth": "2000-01-15",
    "gender": "male",
    "address": "123 Main St",
    "program": "Computer Science",
    "previous_education": "High School",
    "status": "pending",
    "application_date": "2026-02-01",
    "documents": ["transcript.pdf", "id.pdf"],
    "created_at": "2026-02-01 10:30:00",
    "updated_at": "2026-02-01 10:30:00"
  }
}
```

---

### 3. Create Admission

**Endpoint:** `POST /api/admissions`

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "full_name": "John Doe",
  "email": "john@example.com",
  "phone": "1234567890",
  "date_of_birth": "2000-01-15",
  "gender": "male",
  "address": "123 Main St",
  "program": "Computer Science",
  "previous_education": "High School",
  "status": "pending",
  "application_date": "2026-02-01"
}
```

**Required Fields:**
- `full_name` (string, min: 3 chars)
- `email` (string, valid email)
- `phone` (string)
- `date_of_birth` (date, YYYY-MM-DD)
- `gender` (string: male/female/other)
- `program` (string)

**Optional Fields:**
- `address` (string)
- `previous_education` (string)
- `status` (string: pending/approved/rejected, default: pending)
- `application_date` (date, default: today)
- `documents` (array of strings)

**Example Response:**
```json
{
  "status": "success",
  "message": "Admission created successfully",
  "data": {
    "id": 1,
    "registration_number": "ADM-2026-001",
    "full_name": "John Doe",
    ...
  }
}
```

---

### 4. Update Admission

**Endpoint:** `PUT /api/admissions/{id}`

**Path Parameters:**
- `id` (required) - Admission ID

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "full_name": "John Doe Updated",
  "status": "approved"
}
```

**Note:** You can send partial data. Only provided fields will be updated.

**Example Response:**
```json
{
  "status": "success",
  "message": "Admission updated successfully",
  "data": {
    "id": 1,
    "registration_number": "ADM-2026-001",
    "full_name": "John Doe Updated",
    "status": "approved",
    ...
  }
}
```

---

### 5. Delete Admission

**Endpoint:** `DELETE /api/admissions/{id}`

**Path Parameters:**
- `id` (required) - Admission ID

**Example Request:**
```bash
DELETE /api/admissions/1
```

**Example Response:**
```json
{
  "status": "success",
  "message": "Admission deleted successfully"
}
```

**Note:** This is a soft delete. The record is marked as deleted but not removed from the database.

---

### 6. Search Admissions

**Endpoint:** `GET /api/admissions/search`

**Query Parameters:**
- `q` (required) - Search keyword

**Example Request:**
```bash
GET /api/admissions/search?q=john
```

**Example Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "registration_number": "ADM-2026-001",
      "full_name": "John Doe",
      ...
    }
  ],
  "count": 1
}
```

**Search Fields:**
- Registration number
- Full name
- Email
- Phone
- Program

---

### 7. Filter Admissions by Status

**Endpoint:** `GET /api/admissions/filter`

**Query Parameters:**
- `status` (required) - Status value (pending/approved/rejected)

**Example Request:**
```bash
GET /api/admissions/filter?status=pending
```

**Example Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "registration_number": "ADM-2026-001",
      "status": "pending",
      ...
    }
  ],
  "count": 15
}
```

---

## Program API

### 1. List All Programs

**Endpoint:** `GET /api/programs`

**Query Parameters:**
- `page` (optional) - Page number (default: 1)
- `per_page` (optional) - Items per page (default: 10)

**Example Request:**
```bash
GET /api/programs?page=1&per_page=20
```

**Example Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440000",
      "title": "Computer Science",
      "description": "Bachelor of Science in Computer Science",
      "thumbnail": "cs-thumb.jpg",
      "features": ["Modern Curriculum", "Expert Faculty", "Industry Projects"],
      "facilities": ["Computer Labs", "Library", "WiFi"],
      "extra_facilities": ["Hostel", "Cafeteria"],
      "registration_fee": "500.00",
      "tuition_fee": "5000.00",
      "discount": "10.00",
      "category": "Engineering",
      "sub_category": "Computer Science",
      "status": "active",
      "created_at": "2026-02-01 10:30:00",
      "updated_at": "2026-02-01 10:30:00"
    }
  ],
  "pagination": {
    "current_page": 1,
    "total_pages": 3,
    "per_page": 20,
    "total": 45
  }
}
```

---

### 2. Get Single Program

**Endpoint:** `GET /api/programs/{id}`

**Path Parameters:**
- `id` (required) - Program UUID

**Example Request:**
```bash
GET /api/programs/550e8400-e29b-41d4-a716-446655440000
```

**Example Response:**
```json
{
  "status": "success",
  "data": {
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "title": "Computer Science",
    "description": "Bachelor of Science in Computer Science",
    "thumbnail": "cs-thumb.jpg",
    "features": ["Modern Curriculum", "Expert Faculty", "Industry Projects"],
    "facilities": ["Computer Labs", "Library", "WiFi"],
    "extra_facilities": ["Hostel", "Cafeteria"],
    "registration_fee": "500.00",
    "tuition_fee": "5000.00",
    "discount": "10.00",
    "category": "Engineering",
    "sub_category": "Computer Science",
    "status": "active",
    "created_at": "2026-02-01 10:30:00",
    "updated_at": "2026-02-01 10:30:00"
  }
}
```

---

### 3. Create Program

**Endpoint:** `POST /api/programs`

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "title": "Computer Science",
  "description": "Bachelor of Science in Computer Science",
  "thumbnail": "cs-thumb.jpg",
  "features": ["Modern Curriculum", "Expert Faculty", "Industry Projects"],
  "facilities": ["Computer Labs", "Library", "WiFi"],
  "extra_facilities": ["Hostel", "Cafeteria"],
  "registration_fee": "500.00",
  "tuition_fee": "5000.00",
  "discount": "10.00",
  "category": "Engineering",
  "sub_category": "Computer Science",
  "status": "active"
}
```

**Required Fields:**
- `title` (string, min: 3 chars, max: 255 chars)
- `status` (string: active/inactive, default: active)

**Optional Fields:**
- `description` (text)
- `thumbnail` (string, file path)
- `features` (array of strings)
- `facilities` (array of strings)
- `extra_facilities` (array of strings)
- `registration_fee` (decimal)
- `tuition_fee` (decimal)
- `discount` (decimal, max: 100)
- `category` (string)
- `sub_category` (string)

**Example Response:**
```json
{
  "status": "success",
  "message": "Program created successfully",
  "data": {
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "title": "Computer Science",
    ...
  }
}
```

---

### 4. Update Program

**Endpoint:** `PUT /api/programs/{id}`

**Path Parameters:**
- `id` (required) - Program UUID

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "title": "Computer Science - Updated",
  "tuition_fee": "5500.00",
  "discount": "15.00"
}
```

**Note:** You can send partial data. Only provided fields will be updated.

**Example Response:**
```json
{
  "status": "success",
  "message": "Program updated successfully",
  "data": {
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "title": "Computer Science - Updated",
    "tuition_fee": "5500.00",
    "discount": "15.00",
    ...
  }
}
```

---

### 5. Delete Program

**Endpoint:** `DELETE /api/programs/{id}`

**Path Parameters:**
- `id` (required) - Program UUID

**Example Request:**
```bash
DELETE /api/programs/550e8400-e29b-41d4-a716-446655440000
```

**Example Response:**
```json
{
  "status": "success",
  "message": "Program deleted successfully"
}
```

**Note:** This is a soft delete. The record is marked as deleted but not removed from the database.

---

### 6. Search Programs

**Endpoint:** `GET /api/programs/search`

**Query Parameters:**
- `q` (required) - Search keyword

**Example Request:**
```bash
GET /api/programs/search?q=computer
```

**Example Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440000",
      "title": "Computer Science",
      ...
    }
  ],
  "count": 2
}
```

**Search Fields:**
- Title
- Description
- Category

---

### 7. Filter Programs by Status

**Endpoint:** `GET /api/programs/filter`

**Query Parameters:**
- `status` (required) - Status value (active/inactive)

**Example Request:**
```bash
GET /api/programs/filter?status=active
```

**Example Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440000",
      "title": "Computer Science",
      "status": "active",
      ...
    }
  ],
  "count": 25
}
```

---

### 8. Filter Programs by Category

**Endpoint:** `GET /api/programs/filter/category`

**Query Parameters:**
- `category` (required) - Category name

**Example Request:**
```bash
GET /api/programs/filter/category?category=Engineering
```

**Example Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440000",
      "title": "Computer Science",
      "category": "Engineering",
      ...
    }
  ],
  "count": 8
}
```

---

### 9. Get Active Programs

**Endpoint:** `GET /api/programs/active`

**Example Request:**
```bash
GET /api/programs/active
```

**Example Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440000",
      "title": "Computer Science",
      "status": "active",
      ...
    }
  ],
  "count": 25
}
```

---

### 10. Get Programs by Category (Statistics)

**Endpoint:** `GET /api/programs/categories`

**Example Request:**
```bash
GET /api/programs/categories
```

**Example Response:**
```json
{
  "status": "success",
  "data": [
    {
      "category": "Engineering",
      "total": 15
    },
    {
      "category": "Business",
      "total": 10
    },
    {
      "category": "Arts",
      "total": 8
    }
  ]
}
```

---

## Code Examples

### JavaScript (Fetch API)

#### Get All Admissions
```javascript
fetch('http://your-domain.com/api/admissions?page=1&per_page=10')
  .then(response => response.json())
  .then(data => {
    console.log(data.data); // Array of admissions
    console.log(data.pagination); // Pagination info
  })
  .catch(error => console.error('Error:', error));
```

#### Create Admission
```javascript
fetch('http://your-domain.com/api/admissions', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    full_name: 'John Doe',
    email: 'john@example.com',
    phone: '1234567890',
    date_of_birth: '2000-01-15',
    gender: 'male',
    program: 'Computer Science'
  })
})
  .then(response => response.json())
  .then(data => {
    console.log('Success:', data);
  })
  .catch(error => console.error('Error:', error));
```

#### Update Program
```javascript
fetch('http://your-domain.com/api/programs/550e8400-e29b-41d4-a716-446655440000', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    title: 'Computer Science - Updated',
    tuition_fee: '5500.00'
  })
})
  .then(response => response.json())
  .then(data => {
    console.log('Success:', data);
  })
  .catch(error => console.error('Error:', error));
```

#### Search Programs
```javascript
const keyword = 'computer';
fetch(`http://your-domain.com/api/programs/search?q=${encodeURIComponent(keyword)}`)
  .then(response => response.json())
  .then(data => {
    console.log('Search results:', data.data);
    console.log('Total found:', data.count);
  })
  .catch(error => console.error('Error:', error));
```

---

### JavaScript (Axios)

#### Get All Programs
```javascript
import axios from 'axios';

axios.get('http://your-domain.com/api/programs', {
  params: {
    page: 1,
    per_page: 20
  }
})
  .then(response => {
    console.log(response.data.data); // Array of programs
    console.log(response.data.pagination); // Pagination info
  })
  .catch(error => console.error('Error:', error));
```

#### Create Program
```javascript
axios.post('http://your-domain.com/api/programs', {
  title: 'Computer Science',
  description: 'Bachelor of Science in Computer Science',
  category: 'Engineering',
  status: 'active',
  tuition_fee: '5000.00'
})
  .then(response => {
    console.log('Success:', response.data);
  })
  .catch(error => {
    if (error.response) {
      console.error('Error:', error.response.data);
    }
  });
```

#### Delete Admission
```javascript
axios.delete('http://your-domain.com/api/admissions/1')
  .then(response => {
    console.log('Deleted:', response.data.message);
  })
  .catch(error => console.error('Error:', error));
```

---

### PHP (cURL)

#### Get All Admissions
```php
<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://your-domain.com/api/admissions?page=1&per_page=10');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
print_r($data['data']); // Array of admissions
?>
```

#### Create Program
```php
<?php
$data = [
    'title' => 'Computer Science',
    'description' => 'Bachelor of Science in Computer Science',
    'category' => 'Engineering',
    'status' => 'active'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://your-domain.com/api/programs');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
print_r($result);
?>
```

---

### Python (Requests)

#### Get All Programs
```python
import requests

response = requests.get('http://your-domain.com/api/programs', params={
    'page': 1,
    'per_page': 20
})

data = response.json()
print(data['data'])  # Array of programs
print(data['pagination'])  # Pagination info
```

#### Create Admission
```python
import requests

payload = {
    'full_name': 'John Doe',
    'email': 'john@example.com',
    'phone': '1234567890',
    'date_of_birth': '2000-01-15',
    'gender': 'male',
    'program': 'Computer Science'
}

response = requests.post('http://your-domain.com/api/admissions', json=payload)
data = response.json()
print(data)
```

#### Update Program
```python
import requests

payload = {
    'title': 'Computer Science - Updated',
    'tuition_fee': '5500.00'
}

response = requests.put(
    'http://your-domain.com/api/programs/550e8400-e29b-41d4-a716-446655440000',
    json=payload
)

data = response.json()
print(data)
```

---

## Testing with Postman

### Import Collection

You can create a Postman collection with these endpoints:

1. Create a new collection named "ERP System API"
2. Add environment variables:
   - `base_url`: `http://your-domain.com`
3. Add requests for each endpoint documented above

### Example Postman Request

**Get All Admissions:**
- Method: `GET`
- URL: `{{base_url}}/api/admissions`
- Params: 
  - `page`: `1`
  - `per_page`: `10`

**Create Program:**
- Method: `POST`
- URL: `{{base_url}}/api/programs`
- Headers:
  - `Content-Type`: `application/json`
- Body (raw JSON):
```json
{
  "title": "Computer Science",
  "category": "Engineering",
  "status": "active"
}
```

---

## Rate Limiting

Currently, there are no rate limits. In production, consider implementing:
- 100 requests per minute per IP
- 1000 requests per hour per API key

---

## Support

For questions or issues, contact the backend development team.

---

**Last Updated:** February 2, 2026
**Version:** 1.0.0
