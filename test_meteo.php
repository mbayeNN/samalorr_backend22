<?php
// test_meteo.php

// 1. Charger les dépendances
require __DIR__.'/vendor/autoload.php';

// 2. Initialiser l'application Laravel
$app = require_once __DIR__.'/bootstrap/app.php';

// 3. Initialiser le noyau pour activer les Facades (Http, DB, etc.)
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

// Remplacez par votre vraie clé API
$apiKey = 'e19cca63208f8ec4bb4a2fb4153e90d1'; 
$lat = 14.7167; // Dakar
$lon = -17.4677;

// 4. Appel à l'API météo
$response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
    'lat' => $lat,
    'lon' => $lon,
    'appid' => $apiKey,
    'units' => 'metric' 
]);

if ($response->successful()) {
    $data = $response->json();
    $temp = $data['main']['temp'];
    echo "La température actuelle à vos coordonnées est de : " . $temp . "°C\n";
} else {
    echo "Erreur lors de la récupération : " . $response->body();
}