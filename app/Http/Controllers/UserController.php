<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function toggleVerification(Request $request)
    {
        $user = $request->user();
        
        // Bascule le statut (true devient false, false devient true)
        $user->is_profile_verified = !$user->is_profile_verified;
        $user->save();

        return response()->json([
            'message' => 'Statut mis à jour avec succès !',
            'is_profile_verified' => $user->is_profile_verified
        ]);
    }
}