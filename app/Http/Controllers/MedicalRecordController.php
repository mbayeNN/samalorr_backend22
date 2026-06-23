<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Ajout pour le débogage

class MedicalRecordController extends Controller
{
    /**
     * Récupère l'historique de l'utilisateur connecté via Sanctum.
     */
    public function getHistory(Request $request)
    {
        // 1. Récupération forcée de l'ID via le guard sanctum
        $userId = $request->user('sanctum') ? $request->user('sanctum')->id : null;

        // 2. Log de débogage : vérifiez votre fichier storage/logs/laravel.log
        Log::info("Tentative de récupération historique. UserID trouvé : " . ($userId ?? 'AUCUN'));

        if (!$userId) {
            return response()->json(['message' => 'Non authentifié ou token invalide'], 401);
        }

        // 3. Récupération des données
        $records = MedicalRecord::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        Log::info("Nombre de records trouvés pour user $userId : " . $records->count());

        return response()->json([
            'patient_name' => $request->user('sanctum')->name,
            'history' => $records
        ]);
    }

    /**
     * Enregistre une consultation.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'weight' => 'required|numeric',
            'blood_pressure' => 'required|string',
        ]);

        // Utilisation explicite de l'utilisateur Sanctum
        $validatedData['user_id'] = $request->user('sanctum')->id;

        $record = MedicalRecord::create($validatedData);

        return response()->json([
            'message' => 'Consultation enregistrée', 
            'data' => $record
        ], 201);
    }
}