<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RiskAnalysisService;
use App\Models\PatientProfile;
use App\Models\Alert;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    protected $riskService;

    public function __construct(RiskAnalysisService $riskService)
    {
        $this->riskService = $riskService;
    }

    public function evaluate($patient_id)
    {
        $patient = PatientProfile::findOrFail($patient_id);

        // Appel du service pour obtenir la prédiction de l'IA
        $niveau_risque = $this->riskService->getRiskPrediction(
            40.5, // Remplacez par la vraie valeur météo issue de votre WeatherService
            $patient->semaines_grossesse,
            $patient->antecedent_hypertension ? 1 : 0
        );

        // Enregistrement de l'alerte dans la base de données
        $alert = Alert::create([
            'patient_id' => $patient->id,
            'niveau_risque' => $niveau_risque,
            'temperature_relevee' => 40.5,
            'conseils_donnes' => ($niveau_risque == 'Élevé') ? "Urgence : Restez au frais." : "RAS"
        ]);

        return response()->json(['status' => 'success', 'data' => $alert]);
    }
}
