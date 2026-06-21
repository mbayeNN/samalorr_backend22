<?php

// routes/api.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// On utilise le middleware 'auth:sanctum'
// Si le token est absent, il renverra une erreur 401 au lieu d'une redirection
Route::middleware('auth:sanctum')->get('/dashboard-data', [DashboardController::class, 'index']);
Route::middleware('auth:sanctum')->post('/test-verify', [App\Http\Controllers\UserController::class, 'toggleVerification']);