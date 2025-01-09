<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Middleware\PreventLoginIfAuthenticated;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware([PreventLoginIfAuthenticated::class])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/divisions', [DivisionController::class, 'index']);
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::put('/employees/{uuid}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{uuid}', [EmployeeController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
