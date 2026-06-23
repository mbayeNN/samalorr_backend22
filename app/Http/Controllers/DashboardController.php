<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\WeatherService;
use Carbon\Carbon;
use App\Models\MedicalRecord;

class DashboardController extends Controller
{
    public function index(Request $request, WeatherService $weatherService)
    {
        $user = $request->user();
        
        // Récupération sécurisée des coordonnées
        $lat = $request->query('lat', 14.7167);
        $lon = $request->query('lon', -17.4677);

        $temp = $weatherService->getTemperature($lat, $lon);
        $semaines = $user->start_pregnancy_date ? (int) Carbon::parse($user->start_pregnancy_date)->diffInWeeks(Carbon::now()) : 0;

        $dernierRecord = MedicalRecord::where('user_id', $user->id)
            ->whereNotNull('next_appointment_date')
            ->orderBy('next_appointment_date', 'asc')
            ->first();

        $response = Http::timeout(5)->get("http://127.0.0.1:5000/predict", [
            'temp' => $temp,
            'semaines' => $semaines,
            'hta' => $user->has_hypertension ? 1 : 0
        ]);

        return response()->json([
            'user_name' => $user->name,
            'weeks_of_pregnancy' => $semaines,
            'temperature' => $temp,
            // AJOUT DE LA LIGNE CI-DESSOUS
            'is_profile_verified' => $user->is_profile_verified, 
            'risque_ia' => $response->successful() ? $response->json('niveau_risque') : 'FAIBLE',
            'conseil' => $response->successful() ? $response->json('conseil') : 'Restez bien hydratée.',
            'next_appointment' => $dernierRecord ? Carbon::parse($dernierRecord->next_appointment_date)->format('d F Y') : null
        ]);
    }
}