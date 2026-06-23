<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SymptomeReport extends Model
{
    // Indique les champs qui peuvent être remplis via le formulaire/API
    protected $fillable = ['user_id', 'symptomes', 'remarque'];

    // Indique à Laravel que la colonne 'symptomes' est un tableau JSON
    protected $casts = [
        'symptomes' => 'array',
    ];

    // Définit la relation avec l'utilisateur (optionnel mais utile)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}