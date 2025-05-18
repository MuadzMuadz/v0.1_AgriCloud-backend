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


Route::prefix('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\AuthController::class, 'index']);
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/password/reset', [\App\Http\Controllers\AuthController::class, 'resetPassword']);
    Route::post('/password/update', [\App\Http\Controllers\AuthController::class, 'updatePassword'])->middleware('auth:sanctum');
    Route::get('/user', [\App\Http\Controllers\AuthController::class, 'getUser'])->middleware('auth:sanctum');
    route::put('/user/update', [\App\Http\Controllers\AuthController::class, 'updateUser'])->middleware('auth:sanctum');
});



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

// Farmer Warehouse routes

Route::get('/farmer-warehouses', [FarmerWarehouseController::class, 'index']);         
Route::post('/farmer-warehouses', [FarmerWarehouseController::class, 'store']);        
Route::get('/farmer-warehouses/{id}', [FarmerWarehouseController::class, 'show']);   
Route::put('/farmer-warehouses/{id}', [FarmerWarehouseController::class, 'update']); 
Route::delete('/farmer-warehouses/{id}', [FarmerWarehouseController::class, 'destroy']);


// Field routes

Route::get('/fields', [FieldController::class, 'index']);         
Route::post('/fields', [FieldController::class, 'store']);        
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

