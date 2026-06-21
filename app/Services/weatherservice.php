<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function getTemperature($lat, $lon)
    {
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => env('OPENWEATHER_API_KEY'), // Mettez la clé dans votre fichier .env
            'units' => 'metric'
        ]);

        return $response->json()['main']['temp'] ?? null;
    }
}