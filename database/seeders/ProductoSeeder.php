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
                'id_producto' => 'PROD001',
                'nombre' => 'Manzana Roja',
                'id_categoria' => 1, // Frutas
                'unidad_medida' => 'kg',
            ],
            [
                'id_producto' => 'PROD002',
                'nombre' => 'Lechuga',
                'id_categoria' => 2, // Verduras
                'unidad_medida' => 'unidad',
            ],
            [
                'id_producto' => 'PROD003',
                'nombre' => 'Pechuga de Pollo',
                'id_categoria' => 3, // Carnes
                'unidad_medida' => 'kg',
            ],
            [
                'id_producto' => 'PROD004',
                'nombre' => 'Leche Entera',
                'id_categoria' => 5, // Lácteos
                'unidad_medida' => 'litro',
            ],
            [
                'id_producto' => 'PROD005',
                'nombre' => 'Pan Integral',
                'id_categoria' => 6, // Panadería
                'unidad_medida' => 'unidad',
            ],
        ];

        foreach ($productos as $producto) {
            DB::table('productos')->insert([
                'id_producto' => $producto['id_producto'],
                'nombre' => $producto['nombre'],
                'id_categoria' => $producto['id_categoria'],
                'unidad_medida' => $producto['unidad_medida'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}

