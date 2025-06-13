<?php

use App\Http\Controllers\Api\FieldImageController;
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
// use App\Role;
use Illuminate\Container\Attributes\Auth;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


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

    
    Route::post('/fields', [FieldController::class, 'store']); //Buat Lahan
    Route::put('/fields/{id}', [FieldController::class, 'update']); //Update lahan
    Route::delete('/fields/{id}', [FieldController::class, 'destroy']); //Hapus Lahan
    Route::post('/fields/{id}/image', [FieldImageController::class, 'store']); //Upload gambar lahan
    Route::delete('/fields/{id}/image', [FieldImageController::class, 'destroy']); //Hapus gambar lahan     
    Route::get('/fields/{id}/image', [FieldImageController::class, 'show']); //Lihat gambar lahan
    Route::get('/fields/{id}/images', [FieldImageController::class, 'index']); //Lihat semua gambar lahan
    Route::get('/fields/{id}/images/{imageId}', [FieldImageController::class, 'show']); //Lihat gambar lahan berdasarkan ID
    Route::delete('/fields/{id}/images/{imageId}', [FieldImageController::class, 'destroy']); //Hapus gambar lahan berdasarkan ID
    Route::get('/fields/{id}/images', [FieldImageController::class, 'index']); //Lihat semua gambar lahan   

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
