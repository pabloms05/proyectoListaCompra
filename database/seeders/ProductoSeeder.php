<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            [
                'name' => 'Manzana Roja',
                'categoria_id' => 1, // Frutas
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Lechuga',
                'categoria_id' => 2, // Verduras
                'unidad_medida' => 'unidad',
            ],
            [
                'name' => 'Pechuga de Pollo',
                'categoria_id' => 3, // Carnes
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Leche Entera',
                'categoria_id' => 5, // Lácteos
                'unidad_medida' => 'litro',
            ],
            [
                'name' => 'Pan Integral',
                'categoria_id' => 6, // Panadería
                'unidad_medida' => 'unidad',
            ],
        ];

        foreach ($productos as $producto) {
            DB::table('productos')->insert([
                'name' => $producto['name'],
                'categoria_id' => $producto['categoria_id'],
                'unidad_medida' => $producto['unidad_medida'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}

