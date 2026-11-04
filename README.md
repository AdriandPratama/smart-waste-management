# Smart Waste Management System

A web and mobile-based Smart Waste Management System designed to help users report waste locations, manage waste reports, and classify waste images using Machine Learning.

## Project Overview

This project was developed as part of the Fullstack Developer Technical Coding Test at Bilcode Technology.

The system consists of a Laravel backend and web application, a Flutter mobile application, and a Python-based Machine Learning service.

## Technology Stack

| Component | Technology |
|---|---|
| Backend | Laravel 12 |
| Web Frontend | Laravel Blade |
| Database | MySQL |
| Authentication | Laravel Sanctum |
| Mobile Application | Flutter, Dart |
| Machine Learning | Python, TensorFlow, Keras, MobileNetV2 |
| ML API Service | FastAPI, Uvicorn |

## System Architecture

The application uses three main components:

1. **Laravel Backend and Web Application**: Handles authentication, user roles, waste reports, report management, and communication with the Machine Learning service.
2. **Flutter Mobile Application**: Provides mobile access to the waste reporting and classification features.
3. **Machine Learning Service**: Processes waste images and predicts their categories.

### Local Development URLs

| Service | URL |
|---|---|
| Laravel Backend and Web | http://127.0.0.1:8001 |
| Laravel REST API | http://127.0.0.1:8001/api |
| FastAPI Machine Learning | http://127.0.0.1:8000 |
| FastAPI Swagger Documentation | http://127.0.0.1:8000/docs |

These addresses are for local development. They must be adjusted when deploying the application to a server.

## Features

### Backend and Web Application
- User registration and login.
- Admin and User role management.
- Token-based API authentication using Laravel Sanctum.
- Create, read, update, and delete waste reports.
- Waste report ownership validation.
- Waste report status management.
- Report status history.
- Admin access to all reports and status updates.
- GPS coordinate storage.
- Integration with the Machine Learning service.

### Mobile Application
- User authentication.
- Waste reporting through the mobile application.
- Waste category selection.
- Automatic waste image classification.
- Manual category selection.

### Machine Learning
- Image classification using MobileNetV2.
- Three waste categories: Organic, Paper, and Plastic.
- FastAPI service for model integration.
- Model evaluation and classification report generation.

## Machine Learning Evaluation

The MobileNetV2 model was evaluated using a test dataset containing 512 images.

| Evaluation Metric | Result |
|---|---:|
| Test Images | 512 |
| Correct Predictions | 496 |
| Accuracy | 96.88% |
| Loss | 0.1074 |

### Classification Results

| Category | Precision | Recall | F1-Score |
|---|---:|---:|---:|
| Organic | 1.00 | 0.98 | 0.99 |
| Paper | 0.95 | 0.94 | 0.94 |
| Plastic | 0.97 | 0.98 | 0.97 |

Detailed evaluation results are available in the `ml/results/` directory.

## Database Structure

Main database tables:

- `users`
- `waste_categories`
- `waste_reports`
- `report_images`
- `report_status_histories`
- `personal_access_tokens`

## Installation and Setup

### 1. Clone Repository

```bash
git clone https://github.com/AdriandPratama/smart-waste-management.git
cd smart-waste-management
```

### 2. Backend and Web Application Setup

Install PHP dependencies:

```bash
composer install
```

Copy the environment file:

```powershell
Copy-Item .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### 3. Configure Database

Create a MySQL database named:

```text
smart_waste_management
```

Configure the database connection in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_waste_management
DB_USERNAME=root
DB_PASSWORD=
```

Update the database credentials according to your local configuration.

Run migrations:

```bash
php artisan migrate
```

### 4. Configure Machine Learning API URL

Add or update the following variable in `.env`:

```env
ML_API_URL=http://127.0.0.1:8000
```

### 5. Start Laravel Server

Run the Laravel application on port 8001:

```bash
php artisan serve --port=8001
```

Application URL:

```text
http://127.0.0.1:8001
```

### 6. Machine Learning Service Setup

Open a separate terminal and navigate to the ML directory:

```powershell
cd ml
```

Create and activate a virtual environment if it does not already exist:

```powershell
python -m venv venv
.\venv\Scripts\Activate.ps1
```

Install dependencies:

```bash
pip install -r requirements.txt
```

Start the FastAPI service:

```bash
uvicorn app.main:app --host 127.0.0.1 --port 8000 --reload
```

FastAPI URL:

```text
http://127.0.0.1:8000
```

Swagger documentation:

```text
http://127.0.0.1:8000/docs
```

### 7. Mobile Application Setup

Open another terminal and navigate to the mobile directory:

```powershell
cd mobile
```

Install Flutter dependencies:

```bash
flutter pub get
```

Run the application:

```bash
flutter run
```

Make sure Flutter is installed and an Android emulator or physical device is available.

## Running the Complete System

For local development, the following services must be running:

1. MySQL database.
2. Laravel backend and web application on port `8001`.
3. FastAPI Machine Learning service on port `8000`.
4. Flutter application on an Android emulator or physical device.

## API Documentation

Laravel REST API base URL:

```text
http://127.0.0.1:8001/api
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

Protected endpoints require a Laravel Sanctum bearer token.

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

Laravel API endpoints can be tested using Postman or Thunder Client.

The Machine Learning API can be inspected and tested through FastAPI Swagger documentation at:

```text
http://127.0.0.1:8000/docs
```

## Project Status

The backend, web application, mobile application, and Machine Learning components have been implemented as part of the technical coding test.

The Machine Learning model achieved 96.88% accuracy on the test dataset.

## Developer

**Adriand Pratama**

GitHub: https://github.com/AdriandPratama




