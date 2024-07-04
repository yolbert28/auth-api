<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function ($router) {
    Route::post('register', [AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::apiResource('permission', PermissionController::class);

Route::apiResource('role', RoleController::class);
Route::prefix('role')->group(
    function ($router) {
        Route::post('assignRole',[RoleController::class, 'assignRole']);
        Route::post('removeRole',[RoleController::class, 'removeRole']);
        Route::post('givePermission',[RoleController::class, 'givePermission']);
        Route::post('revokePermission',[RoleController::class, 'revokePermission']);
});