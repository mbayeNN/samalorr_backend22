<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    // Récupérer l'historique complet avec le nom de la patiente
    public function history($user_id)
    {
        // On récupère l'utilisateur pour avoir son nom
        $user = User::findOrFail($user_id);
        
        // On récupère tous ses dossiers médicaux
        $records = MedicalRecord::where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'patient_name' => $user->name, // Assurez-vous que le modèle User possède bien 'name'
            'history' => $records
        ]);
    }

    // Créer une nouvelle entrée de consultation
    public function store(Request $request, $user_id)
    {
        $data = $request->all();
        $data['user_id'] = $user_id;

        $record = MedicalRecord::create($data);

        return response()->json([
            'message' => 'Consultation enregistrée avec succès', 
            'data' => $record
        ], 201);
    }
}