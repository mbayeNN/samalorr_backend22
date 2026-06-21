<?php
// test_integration.php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PatientProfile;
use Illuminate\Support\Facades\Http;

// 1. Récupérer une patiente de la BDD
$patient = PatientProfile::find(1);

if (!$patient) {
    die("Erreur : Aucune patiente trouvée en BDD avec l'ID 1. Ajoutez-en une !");
}

echo "Test pour : Patiente ID " . $patient->id . " (" . $patient->semaines_grossesse . " semaines)\n";

// 2. Appel à l'API Python (le cerveau)
$response = Http::get('http://127.0.0.1:8000/predict', [
    'temp' => 40.5, // Température simulée
    'semaines' => $patient->semaines_grossesse,
    'hta' => $patient->antecedent_hypertension ? 1 : 0
]);

// 3. Afficher le résultat
if ($response->successful()) {
    $data = $response->json();
    echo "Résultat IA reçu : " . $data['risque'] . "\n";
    
    // Logique de conseil
    $conseil = ($data['risque'] == 'Élevé') ? "⚠️ Alerte : Risque élevé, reposez-vous." : "✅ Risque faible.";
    echo "Conseil généré : " . $conseil . "\n";
} else {
    echo "Erreur : Impossible de contacter l'API Python. Vérifiez qu'elle tourne !\n";
}