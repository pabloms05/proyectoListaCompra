<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lista extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'owner_id'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * NOTA: Esta relación 'categorias()' puede ser redundante si la nueva lógica 
     * usa productos directamente. La mantengo por si la usas en la vista 'show'.
     */
    public function categorias(): HasMany
    {
        return $this->hasMany(Categoria::class);
    }
    
    // ----------------------------------------------------------------------
    // RELACIÓN AÑADIDA PARA SOLUCIONAR EL ERROR
    // ----------------------------------------------------------------------
    
    /**
     * Relación Muchos a Muchos con Producto.
     * Asumimos que la tabla intermedia es 'lista_producto' y almacena la 'cantidad'.
     */
    public function productos(): BelongsToMany
    {
        // El método belongsToMany crea la relación Muchos a Muchos.
        // - Producto::class: El modelo con el que se relaciona.
        // - 'lista_producto': El nombre de tu tabla pivot (tabla intermedia).
        return $this->belongsToMany(Producto::class, 'lista_producto', 'lista_id', 'producto_id')
                    ->withPivot('cantidad') // Esto es clave para obtener la cantidad
                    ->withTimestamps();
    }
    
    // ----------------------------------------------------------------------
    
    public function sharedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lista_user')
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