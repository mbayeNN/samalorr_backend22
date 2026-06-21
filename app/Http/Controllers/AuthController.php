<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Méthode d'inscription
public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'phone' => 'required|string|unique:users',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:6',
        'start_pregnancy_date' => 'required|date',
        'city' => 'required|string',
    ]);

    $user = User::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'email' => $request->email,
        'city' => $request->city,
        'start_pregnancy_date' => $request->start_pregnancy_date,
        'password' => Hash::make($request->password),
    ]);

    // --- AJOUTEZ CES LIGNES ---
    // On génère le token d'accès pour le nouvel utilisateur
    $token = $user->createToken('auth_token')->plainTextToken;

    // On renvoie le token dans la réponse
    return response()->json([
        'message' => 'Succès', 
        'user' => $user,
        'token' => $token 
    ], 201);
}

    // Méthode de connexion
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => $user
        ], 200);
    }
}