<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_categoria',
        'nombre',
    ];

    // 🛍️ Productos de esta categoría
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_categoria');
    }
}
