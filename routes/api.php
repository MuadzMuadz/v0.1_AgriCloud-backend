<?php

use App\Http\Controllers\Api\FieldImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GrowStagesController;
use App\Http\Controllers\CycleStagesController;
use App\Http\Controllers\CropTemplateController;

use App\Http\Middleware\farmer;
use App\Http\Middleware\admin;

// Role: Public (Guest Access)
Route::get('farmers', [UserController::class, 'farmerList']);
Route::get('farmers/{id}', [UserController::class, 'show']);
Route::get('/farmers/{id}/fields', [FieldController::class, 'listByFarmer']);
Route::get('/farmer-fields/{fieldId}', [FieldController::class, 'show']);
Route::get('/fields/{id}/active-cycle', [CycleController::class, 'activeByField']);
Route::get('/cycles/{id}', [CycleController::class, 'show']);

// Route::get('/cycles/{id}/stages', [CycleStagesController::class, 'listByCycle']);

// -------------------------
// COMMON RESOURCES (Open/Public)
// -------------------------

Route::get('/crop-templates', [CropTemplateController::class, 'index']);
Route::get('/crop-templates/{id}', [CropTemplateController::class, 'show']);

// Route::get('/grow-stages', [GrowStagesController::class, 'index']);
// Route::get('/grow-stages/{id}', [GrowStagesController::class, 'show']);

Route::get('/cycle-stages', [CycleStagesController::class, 'index']);
Route::get('/cycle-stages/{id}', [CycleStagesController::class, 'show']);

//Role: Authentication
Route::prefix('auth')->group(function () {
    Route::get('/', [AuthController::class, 'index']); //Endpoint auth info
    Route::post('/register', [AuthController::class, 'register']); //Register sebagai farmer
    Route::post('/login', [AuthController::class, 'login']); //Login user
    Route::post('/password/reset', [AuthController::class, 'resetPassword']); // Reset Password
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword']); // Lupa Password

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'getUser']); //Ambil profil user yang sedang login
        Route::put('/user/update', [AuthController::class, 'updateProfile']); //Update profil user
        Route::post('/logout', [AuthController::class, 'logout']); //Logout user
        Route::post('/password/update', [AuthController::class, 'updatePassword']); //Ganti password
    });
});

// Role: Farmer
Route::middleware(['auth:sanctum', farmer::class])->group(function()
{
    Route::get('/dashboard', [DashboardController::class, 'index']); //Lihat dashboard (statistik lahan, siklus, dll) (Berhasil testing)
    Route::get('/dashboard/fields', [DashboardController::class, 'fields']); //Lihat statistik lahan
    Route::get('/dashboard/cycles', [DashboardController::class, 'cycles']); //Lihat statistik siklus tanam
    Route::get('/dashboard/cycle-stages', [DashboardController::class, 'cycleStages']); //Lihat statistik tahapan siklus

    // FIELDS
    Route::get('/myfields', [FieldController::class, 'myFields']); //Lihat lahan milik user yang sedang login
    Route::post('/myfields', [FieldController::class, 'store']); //Buat Lahan 
    Route::get('/myfields/{id}', [FieldController::class, 'show']); //Lihat detail lahan
    Route::put('/myfields/{id}', [FieldController::class, 'update']); //Update lahan
    Route::delete('/myfields/{id}', [FieldController::class, 'destroy']); //Hapus Lahan

    // CYCLES
    Route::get('/mycycles', [CycleController::class, 'index']); //Lihat siklus tanam milik user yang sedang login
    Route::post('/mycycles', [CycleController::class, 'store']); //Mulai siklus tanam
    Route::put('/mycycles/{id}', [CycleController::class, 'update']); //Update siklus tanam
    Route::delete('/mycycles/{id}', [CycleController::class, 'destroy']); //Hapus siklus tanam

    // CYCLE STAGES
    // Route::get('/mycycle-stages', [CycleStagesController::class, 'index']); //Lihat tahapan siklus tanam milik user yang sedang login
    // Route::post('/mycycle-stages', [CycleStagesController::class, 'store']); //Tambah tahapan siklus
    // Route::put('/mycycle-stages/{id}', [CycleStagesController::class, 'update']); //Update tahapan
    // Route::delete('/mycycle-stages/{id}', [CycleStagesController::class, 'destroy']); //Hapus Tahapan
});

// -------------------------
// ADMIN ROUTES
// -------------------------

Route::middleware(['auth:sanctum', admin::class])->prefix("/admin")->group(function()
{
    // FIELD
    Route::get('fields', [FieldController::class, 'index']);
    Route::get('fields/{id}', [FieldController::class, 'show']);

    // USER MANAGEMENT
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // CROP TEMPLATE
    Route::get('/crop-templates', [CropTemplateController::class, 'index']);
    Route::post('/crop-templates', [CropTemplateController::class, 'store']);
    Route::put('/crop-templates/{id}', [CropTemplateController::class, 'update']);
    Route::delete('/crop-templates/{id}', [CropTemplateController::class, 'destroy']);

    // GROW STAGES
    Route::get('/grow-stages', [GrowStagesController::class, 'index']);
    Route::post('/grow-stages', [GrowStagesController::class, 'store']);
    Route::put('/grow-stages/{id}', [GrowStagesController::class, 'update']);
    Route::delete('/grow-stages/{id}', [GrowStagesController::class, 'destroy']);

    // CYCLE 
    Route::get('/cycles', [CycleController::class, 'index']);
    Route::get('/cycles/{id}', [CycleController::class, 'show']);

});

// //Role: Authenticated (Farmer dan Admin)
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/users', [UserController::class, 'index']); //Lihat seluruh user (opsional untuk monitoring internal)
// });
