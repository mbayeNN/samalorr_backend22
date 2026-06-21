<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientProfile extends Model
{
    // Ajoutez cette ligne pour autoriser l'insertion de ces champs
    protected $fillable = [
        'semaines_grossesse',
        'antecedent_hypertension',
        'diabete_gestationnel',
        'latitude',
        'longitude',
        // Ajoutez aussi user_id ou d'autres champs si nécessaire
    ];
}