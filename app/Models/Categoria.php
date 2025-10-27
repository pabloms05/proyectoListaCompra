<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'name', 'imagen', 'lista_id'
    ];

    public function lista()
    {
        return $this->belongsTo(Lista::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}

