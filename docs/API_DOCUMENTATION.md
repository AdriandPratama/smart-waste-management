# Smart Waste Management API Documentation

## 1. Overview

Smart Waste Management provides REST API services for user authentication, waste reporting, waste classification, and report management.

The application uses two API services:

| Service | Technology | Description |
|---|---|---|
| Main API | Laravel | Authentication, categories, and waste reports |
| ML API | FastAPI + MobileNetV2 | Image-based waste classification |

### Base URLs

**Laravel API**
```text
http://127.0.0.1:8001/api
```

**FastAPI ML**
```text
http://127.0.0.1:8000
```

**FastAPI Swagger Documentation**
```text
http://127.0.0.1:8000/docs
```

## 2. Authentication

Laravel API uses Laravel Sanctum Bearer Token authentication.

For protected endpoints, include this HTTP header:

```http
Authorization: Bearer YOUR_ACCESS_TOKEN
Accept: application/json
```

A token is returned after successful registration or login.

## 3. Laravel REST API Endpoints

### 3.1 Authentication

#### Register

Create a new user account.

- Method: `POST`
- Endpoint: `/register`
- Authentication: Not required

Request body:

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

Validation:
- `name`: Required, string, maximum 255 characters.
- `email`: Required, valid email, must be unique.
- `password`: Required, minimum 8 characters, must match `password_confirmation`.

Success response: `201 Created`

```json
{
    "message": "Registration successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user"
    },
    "token": "YOUR_ACCESS_TOKEN"
}
```

#### Login

Authenticate an existing user.

- Method: `POST`
- Endpoint: `/login`
- Authentication: Not required

Request body:

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

Success response: `200 OK`

```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user"
    },
    "token": "YOUR_ACCESS_TOKEN"
}
```

#### Logout

Revoke the current access token.

- Method: `POST`
- Endpoint: `/logout`
- Authentication: Required

Success response:

```json
{
    "message": "Logout successful"
}
```

### 3.2 User

#### Get Authenticated User

Retrieve the currently authenticated user's information.

- Method: `GET`
- Endpoint: `/user`
- Authentication: Required

Success response: `200 OK`

Returns the authenticated user object.

### 3.3 Waste Categories

#### Get Categories

Retrieve available waste categories.

- Method: `GET`
- Endpoint: `/categories`
- Authentication: Required

Success response: `200 OK`

```json
[
    {
        "id": 1,
        "name": "Organik",
        "description": "Organic waste"
    },
    {
        "id": 2,
        "name": "Kertas",
        "description": "Paper waste"
    },
    {
        "id": 3,
        "name": "Plastik",
        "description": "Plastic waste"
    }
]
```

The endpoint returns categories named Organik, Kertas, and Plastik, ordered in that sequence.

### 3.4 Waste Classification

#### Classify Waste Image

Upload an image to classify its waste category using the ML service.

- Method: `POST`
- Endpoint: `/classify`
- Authentication: Required
- Content-Type: `multipart/form-data`

Request parameters:

| Parameter | Type | Required | Description |
|---|---|---|---|
| file | File | Yes | Waste image (JPG, JPEG, PNG, or WEBP) |

Maximum file size: 5 MB.

Success response: `200 OK`

```json
{
    "success": true,
    "message": "Waste classification successful.",
    "data": {
        "success": true,
        "filename": "waste.jpg",
        "predicted_class": "plastic",
        "confidence": 96.85,
        "all_predictions": {
            "organic": 1.25,
            "paper": 1.90,
            "plastic": 96.85
        }
    }
}
```

Laravel forwards the uploaded image to the FastAPI `/predict` endpoint and returns its prediction result.

### 3.5 Waste Reports

#### Get All Reports

Retrieve all waste reports with user, category, and image relationships.

- Method: `GET`
- Endpoint: `/reports`
- Authentication: Required
- Access: Admin only

Success response: `200 OK`

Returns an array of waste report objects.

#### Get My Reports

Retrieve reports created by the authenticated user.

- Method: `GET`
- Endpoint: `/my-reports`
- Authentication: Required

Success response: `200 OK`

Returns an array of the user's reports, including category and image relationships.

#### Create Report

Create a new waste report.

- Method: `POST`
- Endpoint: `/reports`
- Authentication: Required
- Content-Type: `multipart/form-data`

Request parameters:

| Parameter | Type | Required | Description |
|---|---|---|---|
| title | String | Yes | Report title |
| description | String | No | Report description |
| category_id | Integer | No | Existing waste category ID |
| latitude | Numeric | No | Latitude (-90 to 90) |
| longitude | Numeric | No | Longitude (-180 to 180) |
| photo | File | No | JPG, JPEG, PNG, or WEBP image, maximum 5 MB |

The report is created with the initial status `pending`. The system also stores image information and creates an initial status history record.

Success response: `201 Created`

```json
{
    "message": "Waste report created successfully.",
    "data": {
        "id": 1,
        "user_id": 1,
        "category_id": 3,
        "title": "Plastic waste near the road",
        "description": "Plastic bottles found near the roadside.",
        "latitude": 1.1234567,
        "longitude": 104.1234567,
        "status": "pending"
    }
}
```

#### Get Report Details

Retrieve a specific report.

- Method: `GET`
- Endpoint: `/reports/{wasteReport}`
- Authentication: Required

Access rules:
- Admin can access any report.
- Regular users can access only their own reports.

Success response: `200 OK`

Returns the report with user, category, images, and status history relationships.

#### Update Report

Update an existing report.

- Method: `PUT`
- Endpoint: `/reports/{wasteReport}`
- Authentication: Required

Access rules:
- Admin can update any report.
- Regular users can update only their own reports.

Request body:

```json
{
    "title": "Updated waste report",
    "description": "Updated description.",
    "category_id": 2,
    "latitude": 1.1234567,
    "longitude": 104.1234567
}
```

All fields are optional except that a provided title must be valid and non-empty.

Success response: `200 OK`

```json
{
    "message": "Waste report updated successfully.",
    "data": {}
}
```

The `data` field contains the updated report object.

#### Delete Report

Delete an existing report.

- Method: `DELETE`
- Endpoint: `/reports/{wasteReport}`
- Authentication: Required

Access rules:
- Admin can delete any report.
- Regular users can delete only their own reports.

Success response: `200 OK`

```json
{
    "message": "Waste report deleted successfully."
}
```

#### Update Report Status

Update the status of a waste report.

- Method: `PATCH`
- Endpoint: `/reports/{wasteReport}/status`
- Authentication: Required
- Access: Admin only

Request body:

```json
{
    "status": "verified",
    "notes": "Report has been verified."
}
```

Allowed status values:

- `pending`
- `verified`
- `in_progress`
- `completed`
- `rejected`

The system records the previous status, new status, user who made the change, and optional notes in the status history table.

Success response: `200 OK`

```json
{
    "message": "Report status updated successfully.",
    "data": {
        "id": 1,
        "status": "verified"
    }
}
```

## 4. FastAPI Machine Learning Service

The ML service uses a trained MobileNetV2 model to classify waste images into three categories:

- `organic`
- `paper`
- `plastic`

### 4.1 Health Check

- Method: `GET`
- Endpoint: `/`
- Authentication: Not required

Success response:

```json
{
    "message": "Smart Waste Management ML API",
    "status": "running",
    "categories": [
        "organic",
        "paper",
        "plastic"
    ]
}
```

### 4.2 Predict Waste Category

Upload an image directly to the ML service.

- Method: `POST`
- Endpoint: `/predict`
- Authentication: Not required
- Content-Type: `multipart/form-data`

Request parameters:

| Parameter | Type | Required | Description |
|---|---|---|---|
| file | File | Yes | Image to classify |

Success response: `200 OK`

```json
{
    "success": true,
    "filename": "waste.jpg",
    "predicted_class": "plastic",
    "confidence": 96.85,
    "all_predictions": {
        "organic": 1.25,
        "paper": 1.90,
        "plastic": 96.85
    }
}
```

The image is converted to RGB, resized to 224 × 224 pixels, and processed by the MobileNetV2 model.

## 5. HTTP Status Codes

| Status Code | Description |
|---|---|
| 200 | Request successful |
| 201 | Resource created successfully |
| 400 | Invalid image or image processing error |
| 401 | Authentication required or invalid token |
| 403 | User does not have permission |
| 404 | Requested resource not found |
| 422 | Validation error |
| 500 | Internal server or ML connection error |
| 502 | ML service returned a failed response |

## 6. API Testing

The APIs can be tested using:

- Postman
- Flutter mobile application
- Browser for GET endpoints
- FastAPI Swagger UI: `http://127.0.0.1:8000/docs`

## 7. Notes

- Laravel Sanctum manages authentication tokens.
- Protected Laravel endpoints require a valid Bearer Token.
- Admin-only endpoints are protected by the `role:admin` middleware.
- Laravel handles the main application requests and forwards image classification requests to FastAPI.
- FastAPI performs image classification using the trained MobileNetV2 model.
- The local base URLs above are intended for local development and must be changed when deploying the application.
