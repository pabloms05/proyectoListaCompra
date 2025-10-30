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
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function listas()
    {
        return $this->belongsToMany(Lista::class, 'item_lista', 'producto_id', 'lista_id')
                    ->withPivot('cantidad', 'comprado', 'notas')
                    ->withTimestamps();
    }
}
