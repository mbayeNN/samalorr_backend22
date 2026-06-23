<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SymptomeReport;
use App\Services\AiService; // <--- AJOUTEZ CETTE LIGNE

class SymptomeController extends Controller
{
    public function store(Request $request) 
{
    $validated = $request->validate([
        'symptomes' => 'required|array',
        'remarque' => 'nullable|string',
    ]);

    $report = $request->user()->symptomeReports()->create([
        'symptomes' => $validated['symptomes'],
        'remarque' => $validated['remarque']
    ]);

    // Appel à votre service IA (que nous créerons ensuite)
    $conseil = \App\Services\AiService::genererConseil($report);

    return response()->json([
        'message' => 'Déclaration enregistrée', 
        'conseil' => $conseil
    ], 201);
}
}