<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Récupère la liste de tous les utilisateurs (patientes).
     * Appelée par le frontend via GET /api/users
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Bascule le statut de vérification du profil d'une patiente.
     * Appelée par le frontend via POST /api/users/{id}/toggle
     * * @param int $id L'ID de la patiente à modifier
     */
    public function toggleVerification($id)
    {
        // Recherche de la patiente dans la base de données
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'Patiente non trouvée'], 404);
        }
        
        // Bascule le statut : si true devient false, et inversement
        $user->is_profile_verified = !$user->is_profile_verified;
        $user->save();

        return response()->json([
            'message' => 'Statut mis à jour avec succès !',
            'is_profile_verified' => $user->is_profile_verified
        ]);
    }
}