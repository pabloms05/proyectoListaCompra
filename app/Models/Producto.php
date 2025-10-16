<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cantidad',
        'imagen',
        'completed',
        'categoria_id',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    /**
     * El producto pertenece a una categoría.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Obtener la lista a la que pertenece indirectamente el producto.
     */
    public function lista()
    {
        return $this->categoria->lista();
    }

    /**
     * Marcar este producto como completado.
     */
    public function marcarComoCompletado(): void
    {
        $this->completed = true;
        $this->save();
    }
}
