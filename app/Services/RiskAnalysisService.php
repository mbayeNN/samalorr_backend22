<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RiskAnalysisService
{
    public function getRiskPrediction($temp, $semaines, $hta)
    {
        // Appel à notre API Python locale
        $response = Http::get('http://127.0.0.1:8000/predict', [
            'temp' => $temp,
            'semaines' => $semaines,
            'hta' => $hta
        ]);

        return $response->json()['risque'] ?? 'Inconnu';
    }
}