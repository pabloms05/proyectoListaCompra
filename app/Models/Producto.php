<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_producto',
        'nombre',
        'id_categoria',
        'unidad_medida',
    ];

    // 🧾 Categoría del producto
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    // 📋 Listas donde aparece este producto
    public function items()
    {
        return $this->hasMany(ItemLista::class, 'id_producto');
    }
}
