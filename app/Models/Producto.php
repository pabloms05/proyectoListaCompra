<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'name',
        'categoria_id',
        'unidad_medida',
        'image_path',
    ];

    // Accessors para compatibilidad (la BD usa 'name', pero queremos acceder como 'nombre' también)
    public function getNombreAttribute()
    {
        return $this->name;
    }

    // 🧾 Categoría del producto
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id_categoria');
    }

    // 📋 Listas donde aparece este producto
    public function items()
    {
        return $this->hasMany(ItemLista::class, 'id_producto');
    }
}
