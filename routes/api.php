<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CropTemplateController;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\CycleStagesController;
use App\Http\Controllers\FarmerWarehouseController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\GrowStagesController;
use App\Http\Controllers\UserController;


// Role: Public (Guest / Belum Login)
Route::get('/users/{id}', [UserController::class, 'show']); //Lihat profil user (misal ditampilkan di landing page)              (Berhasil testing)
Route::get('/farmer-warehouses', [FarmerWarehouseController::class, 'index']); //Lihat daftar gudang petani                      (Berhasil testing tapi data masih kosong)
Route::get('/farmer-warehouses/{id}', [FarmerWarehouseController::class, 'show']); //Lihat detail gudang
Route::get('/fields', [FieldController::class, 'index']); //Lihat daftar lahan                                                   (Berhasil testing tapi data masih kosong)
Route::get('/fields/{id}', [FieldController::class, 'show']); //Lihat detail lahan
Route::get('/crop-templates', [CropTemplateController::class, 'index']); //Lihat daftar template tanaman                         (Berhasil testing tapi data masih kosong)
Route::get('/crop-templates/{id}', [CropTemplateController::class, 'show']); //Lihat detail template tanaman
Route::get('/grow-stages', [GrowStagesController::class, 'index']); //Lihat daftar grow stages                                   (Berhasil testing tapi data masih kosong)
Route::get('/grow-stages/{id}', [GrowStagesController::class, 'show']); //Lihat detail grow stage
Route::get('/cycles', [CycleController::class, 'index']); //Lihat daftar siklus tanam                                            (Berhasil testing tapi data masih kosong)
Route::get('/cycles/{id}', [CycleController::class, 'show']); //Lihat detail sikus tanam
Route::get('/cycle-stages', [CycleStagesController::class, 'index']); //Lihat daftar tahapan siklus                              (Berhasil testing tapi data masih kosong)
Route::get('/cycle-stages/{id}', [CycleStagesController::class, 'show']); //Lihat detail tahapan siklus

// Role: Farmer
Route::middleware(['auth:sanctum', 'role:farmer'])->group(function () {
    Route::put('/farmer-warehouses/{id}', [FarmerWarehouseController::class, 'update']); //update gudang
    Route::delete('/farmer-warehouses/{id}', [FarmerWarehouseController::class, 'destroy']); //Hapus gudang
    Route::post('/farmer-warehouses', [FarmerWarehouseController::class, 'store']); //Buat gudang

    Route::put('/fields/{id}', [FieldController::class, 'update']); //Update lahan
    Route::delete('/fields/{id}', [FieldController::class, 'destroy']); //Hapus Lahan
    Route::post('/fields', [FieldController::class, 'store']); //Buat Lahan

    Route::post('/cycles', [CycleController::class, 'store']); //Mulai siklus tanam
    Route::put('/cycles/{id}', [CycleController::class, 'update']); //Update siklus tanam
    Route::delete('/cycles/{id}', [CycleController::class, 'destroy']); //Hapus siklus tanam

    Route::post('/cycle-stages', [CycleStagesController::class, 'store']); //Tambah tahapan siklus
    Route::put('/cycle-stages/{id}', [CycleStagesController::class, 'update']); //Update tahapan
    Route::delete('/cycle-stages/{id}', [CycleStagesController::class, 'destroy']); //Hapus Tahapan
});

//Role: Admin Only
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/users', [UserController::class, 'store']); //Buat user baru (bisa untuk admin/farmer)
    Route::put('/users/{id}', [UserController::class, 'update']); //Update user
    Route::delete('/users/{id}', [UserController::class, 'destroy']); //Hapus user

    Route::post('/crop-templates', [CropTemplateController::class, 'store']); //Tambah template tanaman
    Route::put('/crop-templates/{id}', [CropTemplateController::class, 'update']); //Update template tanaman
    Route::delete('/crop-templates/{id}', [CropTemplateController::class, 'destroy']); //Hapus template tanaman

    Route::post('/grow-stages', [GrowStagesController::class, 'store']); //Tambah grow stage
    Route::put('/grow-stages/{id}', [GrowStagesController::class, 'update']); //Update grow stage
    Route::delete('/grow-stages/{id}', [GrowStagesController::class, 'destroy']); //Hapus grow stage
});

//Role: Authenticated (Farmer dan Admin)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']); //Lihat seluruh user (opsional untuk monitoring internal)
});
Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']); //Ambil profil user yang sedang login
    Route::put('/user/update', [AuthController::class, 'updateProfile']); //Update profil user
    Route::post('/logout', [AuthController::class, 'logout']); //Logout user
    Route::post('/password/update', [AuthController::class, 'updatePassword']); //Ganti password
});

//Role: Authentication
Route::prefix('auth')->group(function () {
    Route::get('/', [AuthController::class, 'index']); //Endpoint auth info
    Route::post('/register', [AuthController::class, 'register']); //Register sebagai farmer
    Route::post('/login', [AuthController::class, 'login']); //Login user
    Route::post('/password/reset', [AuthController::class, 'resetPassword']); // Reset Password
});




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Farmer routes
Route::prefix('farmers')->group(function () {
    // Add farmer-specific routes here in the future
});

// Public routes
Route::prefix('public')->group(function () {
    // Add routes accessible by all users (farmers and public) here in the future
});

// Dashboard routes
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth:sanctum');

// User routes
// Route::get('/users', [UserController::class, 'index']);
// Route::post('/users', [UserController::class, 'store']);
// Route::get('/users/{id}', [UserController::class, 'show']);
// Route::put('/users/{id}', [UserController::class, 'update']);
// Route::delete('/users/{id}', [UserController::class, 'destroy']);

// Crop Template routes

// Route::get('/crop-templates', [CropTemplateController::class, 'index']);
// Route::post('/crop-templates', [CropTemplateController::class, 'store']);
// Route::get('/crop-templates/{id}', [CropTemplateController::class, 'show']);
// Route::put('/crop-templates/{id}', [CropTemplateController::class, 'update']);
// Route::delete('/crop-templates/{id}', [CropTemplateController::class, 'destroy']);

// Cycle routes

// Route::get('/cycles', [CycleController::class, 'index']);
// Route::post('/cycles', [CycleController::class, 'store']);
// Route::get('/cycles/{id}', [CycleController::class, 'show']);
// Route::put('/cycles/{id}', [CycleController::class, 'update']);
// Route::delete('/cycles/{id}', [CycleController::class, 'destroy']);

// Cycle Stages routes

// Route::get('/cycle-stages', [CycleStagesController::class, 'index']);
// Route::post('/cycle-stages', [CycleStagesController::class, 'store']);
// Route::get('/cycle-stages/{id}', [CycleStagesController::class, 'show']);
// Route::put('/cycle-stages/{id}', [CycleStagesController::class, 'update']);
// Route::delete('/cycle-stages/{id}', [CycleStagesController::class, 'destroy']);

// Farmer Warehouse routes

// Route::get('/farmer-warehouses', [FarmerWarehouseController::class, 'index']);
// Route::post('/farmer-warehouses', [FarmerWarehouseController::class, 'store'])->middleware('auth:sanctum');
// Route::get('/farmer-warehouses/{id}', [FarmerWarehouseController::class, 'show']);
// Route::put('/farmer-warehouses/{id}', [FarmerWarehouseController::class, 'update']);
// Route::delete('/farmer-warehouses/{id}', [FarmerWarehouseController::class, 'destroy']);


// Field routes

// Route::get('/fields', [FieldController::class, 'index']);
// Route::post('/fields', [FieldController::class, 'store'])->middleware('auth:sanctum');
// Route::get('/fields/{id}', [FieldController::class, 'show']);
// Route::put('/fields/{id}', [FieldController::class, 'update']);
// Route::delete('/fields/{id}', [FieldController::class, 'destroy']);


// Grow Stages routes
Route::apiResource('grow-stages', GrowStagesController::class);
// Route::get('/grow-stages', [GrowStagesController::class, 'index']);
// Route::post('/grow-stages', [GrowStagesController::class, 'store']);
// Route::get('/grow-stages/{id}', [GrowStagesController::class, 'show']);
// Route::put('/grow-stages/{id}', [GrowStagesController::class, 'update']);
// Route::delete('/grow-stages/{id}', [GrowStagesController::class, 'destroy']);

