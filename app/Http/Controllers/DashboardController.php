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
        $lat = $request->query('lat', 14.7167);
        $lon = $request->query('lon', -17.4677);

        $temp = $weatherService->getTemperature($lat, $lon);
        $semaines = $user->start_pregnancy_date ? (int) Carbon::parse($user->start_pregnancy_date)->diffInWeeks(Carbon::now()) : 0;

        $dernierRecord = MedicalRecord::where('user_id', $user->id)
            ->whereNotNull('next_appointment_date')
            ->orderBy('next_appointment_date', 'asc')
            ->first();

        // Appel vers FastAPI
        $response = Http::timeout(5)->get("http://127.0.0.1:5000/predict_smart", [
            'lat' => $lat,
            'lon' => $lon,
            'semaines' => $semaines,
            'hta' => $user->has_hypertension ? 1 : 0
        ]);

        return response()->json([
            'user_name' => $user->name,
            'weeks_of_pregnancy' => $semaines,
            'temperature' => $temp,
            'is_profile_verified' => $user->is_profile_verified,
            'risque_ia' => $response->successful() ? $response->json('niveau_risque') : 'FAIBLE',
            // On mappe 'conseil' de l'IA vers 'conseil_ia' attendu par Flutter
            'conseil_ia' => $response->successful() ? $response->json('conseil') : 'Restez bien hydratée.',
            'notifications' => $response->successful() ? $response->json('notifications') : [],
            'next_appointment' => $dernierRecord ? Carbon::parse($dernierRecord->next_appointment_date)->format('d F Y') : null
        ]);
    }
}