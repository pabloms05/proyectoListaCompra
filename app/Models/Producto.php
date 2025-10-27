<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'name', 'cantidad', 'imagen', 'completed', 'categoria_id'
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function lista()
    {
        // relación indirecta: la lista de la categoría
        return $this->categoria->lista;
    }
}
