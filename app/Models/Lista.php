<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lista extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'owner_id',
    ];

    /**
     * La lista pertenece a un usuario que la creó (owner).
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Las categorías de esta lista.
     */
    public function categorias(): HasMany
    {
        return $this->hasMany(Categoria::class);
    }

    /**
     * Usuarios con quienes se ha compartido esta lista.
     */
    public function sharedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lista_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Verificar si un usuario dado tiene acceso (propietario o compartido).
     */
    public function userHasAccess(int $userId): bool
    {
        if ($this->owner_id === $userId) {
            return true;
        }
        // verificar en pivot
        return $this->sharedUsers()->where('user_id', $userId)->exists();
    }
}
