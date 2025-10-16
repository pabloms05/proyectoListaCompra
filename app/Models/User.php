<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

/**
 * Obtiene las listas que el usuario ha creado (owner)
 *
 * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Lista>
 */
public function listas(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(Lista::class, 'owner_id');
}

/**
 * Obtiene las listas que le han sido compartidas al usuario
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Lista>
 */
public function listasCompartidas(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
{
    return $this->belongsToMany(Lista::class, 'lista_user')
                ->withPivot('role')
                ->withTimestamps();
}

}
