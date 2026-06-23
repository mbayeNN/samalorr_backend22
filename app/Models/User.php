<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'city',
        'start_pregnancy_date',
        'is_profile_verified',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_profile_verified' => 'boolean', 
    ];

    /**
     * Relation avec les rapports de symptômes
     * (C'est ce qui manquait pour corriger l'erreur 500)
     */
    public function symptomeReports()
    {
        return $this->hasMany(SymptomeReport::class);
    }
}