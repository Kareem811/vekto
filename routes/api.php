<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminSubCategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // Route::post('/logout-all', [AuthController::class, 'logoutFromAllDevices']);
    Route::middleware(RoleMiddleware::class)->group(function () {
        Route::resource('/superadmincategory', AdminCategoryController::class);
        Route::resource('/superadminproducts', AdminCategoryController::class);
        Route::resource('/adminsubcategory', AdminSubCategoryController::class);
    });
});
Route::resource('/products', ProductController::class);
