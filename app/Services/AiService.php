<?php

namespace App\Services;

class AiService
{
    public static function genererConseil($report)
    {
        // Ici, vous mettrez plus tard l'appel à votre API IA (OpenAI, Gemini, etc.)
        // Pour l'instant, on simule une réponse pour vérifier que la redirection fonctionne
        return "Basé sur vos symptômes (" . implode(", ", $report->symptomes) . "), nous vous conseillons de vous reposer et de consulter un médecin si cela persiste.";
    }
}