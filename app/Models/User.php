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
        'weeks_of_pregnancy',
        'is_profile_verified', // <--- AJOUTEZ CECI
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // <--- AJOUTEZ CECI pour manipuler facilement le booléen
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_profile_verified' => 'boolean', 
    ];
}