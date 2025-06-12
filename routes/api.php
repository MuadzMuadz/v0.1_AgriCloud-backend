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
// use App\Role;
use Illuminate\Container\Attributes\Auth;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::prefix('auth')->group(function () {
    Route::get('/', [AuthController::class, 'index']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
    Route::post('/password/update', [AuthController::class, 'updatePassword'])->middleware('auth:sanctum');
    Route::get('/user', [AuthController::class, 'getUser'])->middleware('auth:sanctum');
    route::put('/user/update', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');
});


// Farmer routes
Route::prefix('farmers')->group(function () {
    // Add farmer-specific routes here in the future
})->middleware(['auth:sanctum', 'role:farmer']);

// Public routes
Route::prefix('public')->group(function () {
    // Add routes accessible by all users (farmers and public) here in the future
});

// admin routes
Route::get('/admin', [DashboardController::class, 'index'])->middleware('auth:sanctum');

// User routes

Route::get('/users', [UserController::class, 'index']);       
Route::post('/users', [UserController::class, 'store']);   
Route::get('/users/{id}', [UserController::class, 'show']);   
Route::put('/users/{id}', [UserController::class, 'update']); 
Route::delete('/users/{id}', [UserController::class, 'destroy']); 

// Crop Template routes

Route::get('/crop-templates', [CropTemplateController::class, 'index']);         
Route::post('/crop-templates', [CropTemplateController::class, 'store']);        
Route::get('/crop-templates/{id}', [CropTemplateController::class, 'show']);   
Route::put('/crop-templates/{id}', [CropTemplateController::class, 'update']); 
Route::delete('/crop-templates/{id}', [CropTemplateController::class, 'destroy']);

// Cycle routes

Route::get('/cycles', [CycleController::class, 'index']);         
Route::post('/cycles', [CycleController::class, 'store']);        
Route::get('/cycles/{id}', [CycleController::class, 'show']);
Route::put('/cycles/{id}', [CycleController::class, 'update']);
Route::delete('/cycles/{id}', [CycleController::class, 'destroy']);

// Cycle Stages routes

Route::get('/cycle-stages', [CycleStagesController::class, 'index']);         
Route::post('/cycle-stages', [CycleStagesController::class, 'store']);        
Route::get('/cycle-stages/{id}', [CycleStagesController::class, 'show']);   
Route::put('/cycle-stages/{id}', [CycleStagesController::class, 'update']); 
Route::delete('/cycle-stages/{id}', [CycleStagesController::class, 'destroy']);


// Field routes

Route::get('/fields', [FieldController::class, 'index']);         
Route::post('/fields', [FieldController::class, 'store'])->middleware('auth:sanctum');        
Route::get('/fields/{id}', [FieldController::class, 'show']);   
Route::put('/fields/{id}', [FieldController::class, 'update']); 
Route::delete('/fields/{id}', [FieldController::class, 'destroy']);


// Grow Stages routes
// Route::apiResource('grow-stages', GrowStagesController::class);
Route::get('/grow-stages', [GrowStagesController::class, 'index']);         
Route::post('/grow-stages', [GrowStagesController::class, 'store']);        
Route::get('/grow-stages/{id}', [GrowStagesController::class, 'show']);   
Route::put('/grow-stages/{id}', [GrowStagesController::class, 'update']); 
Route::delete('/grow-stages/{id}', [GrowStagesController::class, 'destroy']);

// Farmer Warehouse routes

Route::get('/farmer-warehouses', [FarmerWarehouseController::class, 'index']);         
Route::post('/farmer-warehouses', [FarmerWarehouseController::class, 'store'])->middleware('auth:sanctum');        
Route::get('/farmer-warehouses/{id}', [FarmerWarehouseController::class, 'show']);   
Route::put('/farmer-warehouses/{id}', [FarmerWarehouseController::class, 'update']); 
Route::delete('/farmer-warehouses/{id}', [FarmerWarehouseController::class, 'destroy']);