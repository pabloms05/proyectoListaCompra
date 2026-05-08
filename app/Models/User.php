<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Lista;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $google_id
 * @method HasMany listas()
 * @method HasMany listasCreadas()
 * @method BelongsToMany sharedLists()
 */
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
        'google_id'
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
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // 🔗 Relaciones
    
    /**
     * Obtiene las listas que el usuario ha creado
     * @return HasMany
     */
    public function listasCreadas(): HasMany
    {
        return $this->hasMany(Lista::class, 'owner_id');
    }

    /**
     * Obtiene las listas que el usuario ha creado
     * @return HasMany
     */
    public function listas(): HasMany
    {
        return $this->hasMany(Lista::class, 'owner_id');
    }

    /**
     * Obtiene las listas compartidas con este usuario
     * @return BelongsToMany
     */
    public function sharedLists(): BelongsToMany
    {
        return $this->belongsToMany(Lista::class, 'lista_user', 'user_id', 'id_lista')
            ->withPivot('role')
            ->withTimestamps();
    }
    
}
