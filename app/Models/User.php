<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens; //$user->createToken(...) sanctum

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;
//$user->createToken(...) sanctum
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    //$fillable indique que les champs qui peuvent être remplis en masse, 
    // c'est-à-dire que vous pouvez créer ou mettre à jour un utilisateur 
    // en fournissant ces champs dans un tableau. Cela aide à protéger 
    // contre les attaques de type "mass assignment" où un utilisateur
    //  malveillant pourrait essayer de remplir des champs non autorisés.
   // User::create([...])
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
