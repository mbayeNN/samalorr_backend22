<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\WeatherService;

class DashboardController extends Controller
{
    public function index(Request $request, WeatherService $weatherService)
    {
        $user = $request->user();
        $lat = $user->last_lat ?? 14.7167; 
        $lon = $user->last_lon ?? -17.4677;

        $temp = $weatherService->getTemperature($lat, $lon);
        
        $semaines = (int)($user->weeks_of_pregnancy ?? 0);
        $hta = $user->has_hypertension ? 1 : 0;

        // Appel vers le moteur IA Python
        $response = Http::timeout(5)->get("http://127.0.0.1:5000/predict", [
            'temp' => $temp,
            'semaines' => $semaines,
            'hta' => $hta
        ]);

        // Initialisation des variables avec des valeurs par défaut
        $risque = 'FAIBLE'; 
        $conseil = 'Restez bien hydratée.';

        if ($response->successful()) {
            $iaData = $response->json();
            $risque = $iaData['niveau_risque'] ?? 'FAIBLE';
            $conseil = $iaData['conseil'] ?? 'Restez bien hydratée.';
        } else {
            $conseil = 'Service d\'analyse temporairement indisponible.';
        }

        // Retour de la réponse
        return response()->json([
            'user_name' => $user->name,
            'weeks_of_pregnancy' => $semaines,
            'is_profile_verified' => (bool) $user->is_profile_verified,
            'temperature' => $temp,
            'risque_ia' => $risque, // La variable est maintenant bien définie !
            'conseil' => $conseil
        ]);
    }
}