INDONESIAN VERSION

# Smart Waste Management System

Smart Waste Management System merupakan aplikasi berbasis web dan mobile yang dirancang untuk membantu pengguna melaporkan lokasi sampah, mengelola laporan sampah, serta mengklasifikasikan gambar sampah menggunakan Machine Learning.

## Gambaran Umum Proyek

Proyek ini dikembangkan sebagai bagian dari Technical Coding Test Fullstack Developer di Bilcode Technology.

Sistem terdiri dari backend dan aplikasi web menggunakan Laravel, aplikasi mobile menggunakan Flutter, serta layanan Machine Learning berbasis Python.

## Teknologi yang Digunakan

| Komponen | Teknologi |
|---|---|
| Backend | Laravel 12 |
| Web Frontend | Laravel Blade |
| Database | MySQL |
| Autentikasi | Laravel Sanctum |
| Aplikasi Mobile | Flutter, Dart |
| Machine Learning | Python, TensorFlow, Keras, MobileNetV2 |
| Layanan ML API | FastAPI, Uvicorn |

## Arsitektur Sistem

Aplikasi ini terdiri dari tiga komponen utama:

1. **Laravel Backend dan Web Application**: Menangani autentikasi, hak akses pengguna, laporan sampah, pengelolaan laporan, serta komunikasi dengan layanan Machine Learning.
2. **Flutter Mobile Application**: Menyediakan akses melalui perangkat mobile untuk pelaporan dan klasifikasi sampah.
3. **Machine Learning Service**: Memproses gambar sampah dan memprediksi kategorinya.

### Alamat Server Lokal

| Layanan | URL |
|---|---|
| Laravel Backend dan Web | http://127.0.0.1:8001 |
| Laravel REST API | http://127.0.0.1:8001/api |
| FastAPI Machine Learning | http://127.0.0.1:8000 |
| Dokumentasi Swagger FastAPI | http://127.0.0.1:8000/docs |

Alamat tersebut digunakan untuk pengembangan lokal. Jika aplikasi di-deploy ke server, alamat perlu disesuaikan.

## Fitur Aplikasi

### Backend dan Web Application
- Registrasi dan login pengguna.
- Pengelolaan role Admin dan User.
- Autentikasi API menggunakan Laravel Sanctum.
- Create, read, update, dan delete laporan sampah.
- Validasi kepemilikan laporan.
- Pengelolaan status laporan sampah.
- Riwayat perubahan status laporan.
- Admin dapat mengakses seluruh laporan dan memperbarui statusnya.
- Penyimpanan koordinat GPS.
- Integrasi dengan layanan Machine Learning.

### Mobile Application
- Autentikasi pengguna.
- Pelaporan sampah melalui aplikasi mobile.
- Pemilihan kategori sampah.
- Klasifikasi gambar sampah secara otomatis.
- Pemilihan kategori secara manual.

### Machine Learning
- Klasifikasi gambar menggunakan MobileNetV2.
- Tiga kategori sampah: Organic, Paper, dan Plastic.
- Layanan FastAPI untuk integrasi model.
- Evaluasi model dan pembuatan laporan hasil klasifikasi.

## Hasil Evaluasi Machine Learning

Model MobileNetV2 dievaluasi menggunakan dataset pengujian yang terdiri dari 512 gambar.

| Metrik Evaluasi | Hasil |
|---|---:|
| Jumlah Gambar Pengujian | 512 |
| Prediksi Benar | 496 |
| Akurasi | 96,88% |
| Loss | 0,1074 |

### Hasil Klasifikasi

| Kategori | Precision | Recall | F1-Score |
|---|---:|---:|---:|
| Organic | 1,00 | 0,98 | 0,99 |
| Paper | 0,95 | 0,94 | 0,94 |
| Plastic | 0,97 | 0,98 | 0,97 |

Detail hasil evaluasi tersedia di direktori `ml/results/`.

## Struktur Database

Tabel utama yang digunakan:

- `users`
- `waste_categories`
- `waste_reports`
- `report_images`
- `report_status_histories`
- `personal_access_tokens`

## Instalasi dan Konfigurasi

### 1. Clone Repository

```bash
git clone https://github.com/AdriandPratama/smart-waste-management.git
cd smart-waste-management
```

### 2. Persiapan Backend dan Web Application

Install dependency PHP:

```bash
composer install
```

Salin file environment:

```powershell
Copy-Item .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 3. Konfigurasi Database

Buat database MySQL dengan nama:

```text
smart_waste_management
```

Atur koneksi database pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_waste_management
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan username dan password dengan konfigurasi database lokal.

Jalankan migration:

```bash
php artisan migrate
```

### 4. Konfigurasi URL Machine Learning API

Tambahkan atau sesuaikan variabel berikut pada file `.env`:

```env
ML_API_URL=http://127.0.0.1:8000
```

### 5. Menjalankan Laravel Server

Jalankan Laravel pada port 8001:

```bash
php artisan serve --port=8001
```

Alamat aplikasi:

```text
http://127.0.0.1:8001
```

### 6. Persiapan Machine Learning Service

Buka terminal baru dan masuk ke direktori ML:

```powershell
cd ml
```

Buat dan aktifkan virtual environment jika belum tersedia:

```powershell
python -m venv venv
.\venv\Scripts\Activate.ps1
```

Install dependency:

```bash
pip install -r requirements.txt
```

Jalankan layanan FastAPI:

```bash
uvicorn app.main:app --host 127.0.0.1 --port 8000 --reload
```

Alamat FastAPI:

```text
http://127.0.0.1:8000
```

Dokumentasi Swagger:

```text
http://127.0.0.1:8000/docs
```

### 7. Persiapan Mobile Application

Buka terminal baru dan masuk ke direktori mobile:

```powershell
cd mobile
```

Install dependency Flutter:

```bash
flutter pub get
```

Jalankan aplikasi:

```bash
flutter run
```

Pastikan Flutter sudah terpasang dan emulator Android atau perangkat fisik tersedia.

## Menjalankan Seluruh Sistem

Untuk menjalankan sistem secara lokal, pastikan layanan berikut aktif:

1. Database MySQL.
2. Laravel backend dan web application pada port `8001`.
3. FastAPI Machine Learning pada port `8000`.
4. Flutter application melalui emulator Android atau perangkat fisik.

## Dokumentasi API

Base URL Laravel REST API:

```text
http://127.0.0.1:8001/api
```

### Autentikasi

| Method | Endpoint | Keterangan |
|---|---|---|
| POST | `/register` | Registrasi pengguna |
| POST | `/login` | Login pengguna |
| POST | `/logout` | Logout pengguna |
| GET | `/user` | Mendapatkan data pengguna yang sedang login |

### Waste Reports

| Method | Endpoint | Hak Akses |
|---|---|---|
| GET | `/my-reports` | User / Admin |
| POST | `/reports` | User / Admin |
| GET | `/reports/{id}` | Owner / Admin |
| PUT | `/reports/{id}` | Owner / Admin |
| DELETE | `/reports/{id}` | Owner / Admin |
| GET | `/reports` | Admin |
| PATCH | `/reports/{id}/status` | Admin |

### Header Autentikasi

Endpoint yang dilindungi memerlukan bearer token dari Laravel Sanctum.

```http
Authorization: Bearer YOUR_ACCESS_TOKEN
Accept: application/json
```

### Status Laporan

- `pending`
- `verified`
- `in_progress`
- `completed`
- `rejected`

## Pengujian API

Endpoint Laravel REST API dapat diuji menggunakan Postman atau Thunder Client.

Machine Learning API dapat diperiksa dan diuji melalui dokumentasi Swagger FastAPI:

```text
http://127.0.0.1:8000/docs
```

## Status Proyek

Komponen backend, web application, mobile application, dan Machine Learning telah diimplementasikan sebagai bagian dari technical coding test.

Model Machine Learning memperoleh akurasi sebesar 96,88% pada dataset pengujian.

## Developer

**Adriand Pratama**

GitHub: https://github.com/AdriandPratama
