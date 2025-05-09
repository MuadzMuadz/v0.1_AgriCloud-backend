<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AdminController;
// use App\Http\Controllers\AuthController;
use App\Http\Controllers\CropTemplateController;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\CycleStagesController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\FarmerWarehouseController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\GrowStagesController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\UserController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware([\App\Http\Middleware\AuthMiddleware::class . ':admin,farmer,guest'])->group(function () {
    Route::get('/auth', [\App\Http\Controllers\AuthController::class, 'index']);
    Route::post('/auth/register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/auth/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/auth/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});



// User routes
Route::apiResource('users', UserController::class);

// Crop Template routes
Route::apiResource('crop-templates', CropTemplateController::class);

// Cycle routes
Route::apiResource('cycles', CycleController::class);

// Cycle Stages routes
Route::apiResource('cycle-stages', CycleStagesController::class);

// Farmer Warehouse routes
Route::apiResource('farmer-warehouses', FarmerWarehouseController::class);

// Field routes
Route::apiResource('fields', FieldController::class);

// Grow Stages routes
Route::apiResource('grow-stages', GrowStagesController::class);
