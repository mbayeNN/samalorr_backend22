<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use App\Models\PatientProfile;
use App\Services\RiskAnalysisService;

Schedule::call(function () {
    $patients = PatientProfile::all();
    foreach ($patients as $patient) {
        // Logique d'analyse automatique ici
        app(RiskAnalysisService::class)->calculateRisk($patient);
    }
})->dailyAt('08:00'); // Analyse chaque matin à 8h