<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CropTemplateController;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\CycleStagesController;
use App\Http\Controllers\FarmerWarehouseController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\GrowStagesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
});
// })->middleware('auth:sanctum');

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
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
