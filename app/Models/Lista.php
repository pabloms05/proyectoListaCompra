<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lista extends Model
{
    use HasFactory;

    protected $table = 'listas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'description', 'owner_id'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // ----------------------------------------------------------------------
    // RELACIÓN AÑADIDA PARA SOLUCIONAR EL ERROR
    // ----------------------------------------------------------------------
    
    /**
     * Relación Muchos a Muchos con Producto.
     * La tabla intermedia es 'item_lista' y almacena 'cantidad', 'comprado' y 'notas'.
     */
    public function productos(): BelongsToMany
    {
        // El método belongsToMany crea la relación Muchos a Muchos.
        // - Producto::class: El modelo con el que se relaciona.
        // - 'item_lista': El nombre de tu tabla pivot (tabla intermedia).
        return $this->belongsToMany(Producto::class, 'item_lista', 'lista_id', 'producto_id')
                    ->withPivot('cantidad', 'comprado', 'notas') // Incluir campos de la tabla pivot
                    ->withTimestamps();
    }
    
    // ----------------------------------------------------------------------
    
    public function sharedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lista_user', 'lista_id', 'user_id', 'id_lista', 'id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function userHasAccess($userId)
    {
        if ($this->owner_id === $userId) {
            return true;
        }
        return $this->sharedUsers()->where('user_id', $userId)->exists();
    }
}