<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\SymptomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MedicalRecordController;
use Illuminate\Support\Facades\Route;

// --- ROUTES PUBLIQUES ---
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// --- ROUTES PROTÉGÉES (Nécessitent le Token Bearer) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Utilisateurs
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users/{id}/toggle', [UserController::class, 'toggleVerification']);
    
    // Dossiers Médicaux (Utilisez uniquement celles-ci)
    Route::get('/medical-records/history', [MedicalRecordController::class, 'getHistory']);
    Route::post('/medical-records', [MedicalRecordController::class, 'store']);
    
    // Dashboard & Autres
    Route::get('/dashboard-data', [DashboardController::class, 'index']);
    Route::post('/symptomes', [SymptomeController::class, 'store']);
    Route::post('/test-verify', [UserController::class, 'toggleVerification']);
});