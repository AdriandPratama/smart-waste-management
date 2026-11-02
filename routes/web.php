<?php

use App\Http\Controllers\Web\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\UserDashboardController;
use App\Http\Controllers\Web\UserReportController;
use App\Http\Controllers\Web\AdminUserController;
use App\Http\Controllers\Web\AdminReportController;

//halaman utama
Route::redirect('/', '/login');

// Guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

        Route::get('/admin/users', [AdminUserController::class, 'index'])
    ->name('admin.users.index');

Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy'])
    ->name('admin.users.destroy');

    Route::get('/admin/reports', [AdminReportController::class, 'index'])
    ->name('admin.reports.index');

    Route::put('/admin/reports/{id}/status', [AdminReportController::class, 'updateStatus'])
    ->name('admin.reports.updateStatus');
});

// User
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])
        ->name('user.dashboard');

     Route::get('/user/reports/create', [UserReportController::class, 'create'])
    ->name('user.reports.create');

    Route::post('/user/reports', [UserReportController::class, 'store'])
    ->name('user.reports.store');

    Route::get('/user/reports/{id}', [UserReportController::class, 'show'])
    ->name('user.reports.show');

    Route::get('/user/reports/{id}/edit', [UserReportController::class, 'edit'])
    ->name('user.reports.edit');

Route::put('/user/reports/{id}', [UserReportController::class, 'update'])
    ->name('user.reports.update');

    Route::delete('/user/reports/{id}', [UserReportController::class, 'destroy'])
    ->name('user.reports.destroy');

});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
