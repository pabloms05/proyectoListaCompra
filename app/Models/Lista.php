<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    use HasFactory;

    protected $table = 'listas';
    protected $primaryKey = 'id_lista';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_lista',
        'owner_id',
        'nombre',
        'compartida',
    ];

    // 🧍‍♂️ Usuario creador
    public function creador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 👥 Usuarios con acceso compartido
    public function usuariosCompartidos()
    {
        return $this->belongsToMany(User::class, 'lista_compartida', 'id_lista', 'user_id');
    }

    // 🛒 Productos (a través de items)
    public function items()
    {
        return $this->hasMany(ItemLista::class, 'id_lista');
    }
    public function productos()
    {
        return $this->belongsToMany(
            Producto::class,
            'item_lista',
            'id_lista',
            'id_producto'
        )->withPivot('cantidad', 'comprado', 'notas')->withTimestamps();
    }
}
