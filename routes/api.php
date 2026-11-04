
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WasteReportController;
use App\Models\WasteCategory;
use App\Http\Controllers\Api\ClassificationController;

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes yang membutuhkan login
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/classify', [ClassificationController::class, 'classify']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    // Get Categories
    Route::get('/categories', function () {
        return response()->json(
            WasteCategory::select('id', 'name', 'description')->get()
        );
    });

    // User dan Admin
    Route::get('/my-reports', [WasteReportController::class, 'myReports']);
    Route::post('/reports', [WasteReportController::class, 'store']);

    Route::get('/reports/{wasteReport}', [WasteReportController::class, 'show']);
    Route::put('/reports/{wasteReport}', [WasteReportController::class, 'update']);
    Route::delete('/reports/{wasteReport}', [WasteReportController::class, 'destroy']);

    // Khusus Admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/reports', [WasteReportController::class, 'index']);

        Route::patch('/reports/{wasteReport}/status', [WasteReportController::class, 'updateStatus']);
    });
});
