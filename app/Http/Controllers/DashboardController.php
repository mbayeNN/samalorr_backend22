<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\WeatherService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request, WeatherService $weatherService)
    {
        $user = $request->user();
        $lat = $user->last_lat ?? 14.7167; 
        $lon = $user->last_lon ?? -17.4677;

        $temp = $weatherService->getTemperature($lat, $lon);
        
        // --- CALCUL DYNAMIQUE ---
        // Le résultat est forcé en (int) pour garantir un type simple pour Flutter
        $semaines = 0;
        if ($user->start_pregnancy_date) {
            $semaines = (int) Carbon::parse($user->start_pregnancy_date)->diffInWeeks(Carbon::now());
        }

        $hta = $user->has_hypertension ? 1 : 0;

        // Appel vers l'IA Python
        $response = Http::timeout(5)->get("http://127.0.0.1:5000/predict", [
            'temp' => $temp,
            'semaines' => $semaines,
            'hta' => $hta
        ]);

        $risque = 'FAIBLE'; 
        $conseil = 'Restez bien hydratée.';

        if ($response->successful()) {
            $iaData = $response->json();
            $risque = $iaData['niveau_risque'] ?? 'FAIBLE';
            $conseil = $iaData['conseil'] ?? 'Restez bien hydratée.';
        } else {
            $conseil = 'Service d\'analyse temporairement indisponible.';
        }

        return response()->json([
            'user_name' => $user->name,
            'weeks_of_pregnancy' => $semaines, // Envoyé en tant qu'entier pur
            'is_profile_verified' => (bool) $user->is_profile_verified,
            'temperature' => $temp,
            'risque_ia' => $risque,
            'conseil' => $conseil,
            'next_appointment' => '15 Juillet 2026'
        ]);
    }
}