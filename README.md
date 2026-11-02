# Smart Waste Management System

A web and mobile-based Smart Waste Management System designed to help users report waste locations, manage waste reports, and support waste classification using Machine Learning.

## Project Overview

This project is developed as part of the Fullstack Developer Technical Coding Test at Bilcode Technology.

### Technology Stack

- **Backend:** Laravel 12
- **Database:** MySQL
- **Authentication:** Laravel Sanctum
- **Frontend:** Laravel Blade (planned)
- **Mobile:** Flutter (planned)
- **Machine Learning:** Python, TensorFlow/Keras (planned)
- **AI Service:** FastAPI (planned)

## Current Features

- User registration and login.
- Admin and User role management.
- Token-based API authentication.
- Create, read, update, and delete waste reports.
- Waste report ownership validation.
- Waste report status management.
- Report status history.
- Admin-only access to all reports and status updates.
- GPS coordinate storage.

## Database Structure

Main database tables:

- `users`
- `waste_categories`
- `waste_reports`
- `report_images`
- `report_status_histories`
- `personal_access_tokens`

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/AdriandPratama/smart-waste-management.git
cd smart-waste-management
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

Copy the environment file:

```bash
cp .env.example .env
```

On Windows PowerShell, use:

```powershell
Copy-Item .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 4. Configure Database

Create a MySQL database named:

```text
smart_waste_management
```

Configure the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_waste_management
DB_USERNAME=root
DB_PASSWORD=
```

Update the database username and password according to your local configuration.

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Start Development Server

```bash
php artisan serve
```

Application URL:

```text
http://127.0.0.1:8000
```

## API Documentation

Base URL:

```text
http://127.0.0.1:8000/api
```

### Authentication

| Method | Endpoint | Description |
|---|---|---|
| POST | `/register` | Register a new user |
| POST | `/login` | User login |
| POST | `/logout` | User logout |
| GET | `/user` | Get authenticated user |

### Waste Reports

| Method | Endpoint | Access |
|---|---|---|
| GET | `/my-reports` | User / Admin |
| POST | `/reports` | User / Admin |
| GET | `/reports/{id}` | Owner / Admin |
| PUT | `/reports/{id}` | Owner / Admin |
| DELETE | `/reports/{id}` | Owner / Admin |
| GET | `/reports` | Admin |
| PATCH | `/reports/{id}/status` | Admin |

### Authentication Header

Protected endpoints require a Sanctum bearer token.

```http
Authorization: Bearer YOUR_ACCESS_TOKEN
Accept: application/json
```

### Report Status Values

- `pending`
- `verified`
- `in_progress`
- `completed`
- `rejected`

## API Testing

API endpoints can be tested using Thunder Client or Postman.

## Project Status

The initial backend and REST API implementation has been completed, including authentication, report CRUD operations, status management, and role-based authorization.

Frontend, mobile application, and Machine Learning integration are part of the subsequent development stages.

## Developer

**Adriand Pratama**

GitHub: [AdriandPratama]([https](https://github.com/AdriandPratama/smart-waste-management.git)])
