<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemLista extends Model
{
    use HasFactory;

    protected $table = 'item_lista';
    protected $primaryKey = null; // clave compuesta
    public $incrementing = false;

    protected $fillable = [
        'id_lista',
        'id_producto',
        'cantidad',
        'comprado',
        'notas',
    ];

    // 🔗 Relaciones
    public function lista()
    {
        return $this->belongsTo(Lista::class, 'id_lista');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}
