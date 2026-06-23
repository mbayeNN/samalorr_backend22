<?php

// routes/api.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SymptomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MedicalRecordController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/users', [UserController::class, 'index']);
Route::post('/users/{id}/toggle', [UserController::class, 'toggleVerification']);
Route::get('/medical-records/{user_id}', [MedicalRecordController::class, 'show']);
Route::post('/medical-records/{user_id}', [MedicalRecordController::class, 'store']);
Route::get('/medical-records/{user_id}/history', [MedicalRecordController::class, 'history']);
Route::get('/dashboard', [DashboardController::class, 'index']);

// On utilise le middleware 'auth:sanctum'
// Si le token est absent, il renverra une erreur 401 au lieu d'une redirection
Route::middleware('auth:sanctum')->get('/dashboard-data', [DashboardController::class, 'index']);
Route::middleware('auth:sanctum')->post('/test-verify', [App\Http\Controllers\UserController::class, 'toggleVerification']);
Route::middleware('auth:sanctum')->post('/symptomes', [SymptomeController::class, 'store']);

