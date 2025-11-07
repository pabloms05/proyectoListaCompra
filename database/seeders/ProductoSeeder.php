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
            // Frutas (id: 1)
            [
                'name' => 'Manzana Roja',
                'categoria_id' => 1,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Plátano',
                'categoria_id' => 1,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Naranja',
                'categoria_id' => 1,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Pera',
                'categoria_id' => 1,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Uvas',
                'categoria_id' => 1,
                'unidad_medida' => 'kg',
            ],

            // Verduras (id: 2)
            [
                'name' => 'Lechuga',
                'categoria_id' => 2,
                'unidad_medida' => 'unidad',
            ],
            [
                'name' => 'Tomate',
                'categoria_id' => 2,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Zanahoria',
                'categoria_id' => 2,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Cebolla',
                'categoria_id' => 2,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Pimiento',
                'categoria_id' => 2,
                'unidad_medida' => 'kg',
            ],

            // Carnes (id: 3)
            [
                'name' => 'Pechuga de Pollo',
                'categoria_id' => 3,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Carne Picada',
                'categoria_id' => 3,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Lomo de Cerdo',
                'categoria_id' => 3,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Chuletas de Cordero',
                'categoria_id' => 3,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Solomillo de Ternera',
                'categoria_id' => 3,
                'unidad_medida' => 'kg',
            ],

            // Pescados y Mariscos (id: 4)
            [
                'name' => 'Salmón',
                'categoria_id' => 4,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Merluza',
                'categoria_id' => 4,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Gambas',
                'categoria_id' => 4,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Mejillones',
                'categoria_id' => 4,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Atún Fresco',
                'categoria_id' => 4,
                'unidad_medida' => 'kg',
            ],

            // Lácteos (id: 5)
            [
                'name' => 'Leche Entera',
                'categoria_id' => 5,
                'unidad_medida' => 'litro',
            ],
            [
                'name' => 'Yogur Natural',
                'categoria_id' => 5,
                'unidad_medida' => 'pack',
            ],
            [
                'name' => 'Queso Fresco',
                'categoria_id' => 5,
                'unidad_medida' => 'unidad',
            ],
            [
                'name' => 'Mantequilla',
                'categoria_id' => 5,
                'unidad_medida' => 'unidad',
            ],
            [
                'name' => 'Huevos',
                'categoria_id' => 5,
                'unidad_medida' => 'docena',
            ],

            // Panadería y Cereales (id: 6)
            [
                'name' => 'Pan Integral',
                'categoria_id' => 6,
                'unidad_medida' => 'unidad',
            ],
            [
                'name' => 'Baguette',
                'categoria_id' => 6,
                'unidad_medida' => 'unidad',
            ],
            [
                'name' => 'Cereales Desayuno',
                'categoria_id' => 6,
                'unidad_medida' => 'caja',
            ],
            [
                'name' => 'Arroz',
                'categoria_id' => 6,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Pasta',
                'categoria_id' => 6,
                'unidad_medida' => 'paquete',
            ],

            // Bebidas (id: 7)
            [
                'name' => 'Agua Mineral',
                'categoria_id' => 7,
                'unidad_medida' => 'litro',
            ],
            [
                'name' => 'Refresco Cola',
                'categoria_id' => 7,
                'unidad_medida' => 'litro',
            ],
            [
                'name' => 'Zumo Naranja',
                'categoria_id' => 7,
                'unidad_medida' => 'litro',
            ],
            [
                'name' => 'Cerveza',
                'categoria_id' => 7,
                'unidad_medida' => 'pack',
            ],
            [
                'name' => 'Vino Tinto',
                'categoria_id' => 7,
                'unidad_medida' => 'botella',
            ],

            // Snacks y Dulces (id: 8)
            [
                'name' => 'Patatas Fritas',
                'categoria_id' => 8,
                'unidad_medida' => 'bolsa',
            ],
            [
                'name' => 'Chocolate',
                'categoria_id' => 8,
                'unidad_medida' => 'tableta',
            ],
            [
                'name' => 'Galletas',
                'categoria_id' => 8,
                'unidad_medida' => 'paquete',
            ],
            [
                'name' => 'Frutos Secos',
                'categoria_id' => 8,
                'unidad_medida' => 'bolsa',
            ],
            [
                'name' => 'Gominolas',
                'categoria_id' => 8,
                'unidad_medida' => 'bolsa',
            ],

            // Congelados (id: 9)
            [
                'name' => 'Pizza Congelada',
                'categoria_id' => 9,
                'unidad_medida' => 'unidad',
            ],
            [
                'name' => 'Verduras Congeladas',
                'categoria_id' => 9,
                'unidad_medida' => 'bolsa',
            ],
            [
                'name' => 'Helado',
                'categoria_id' => 9,
                'unidad_medida' => 'litro',
            ],
            [
                'name' => 'Pescado Congelado',
                'categoria_id' => 9,
                'unidad_medida' => 'kg',
            ],
            [
                'name' => 'Croquetas',
                'categoria_id' => 9,
                'unidad_medida' => 'caja',
            ],

            // Limpieza y Hogar (id: 10)
            [
                'name' => 'Detergente Lavadora',
                'categoria_id' => 10,
                'unidad_medida' => 'botella',
            ],
            [
                'name' => 'Papel Higiénico',
                'categoria_id' => 10,
                'unidad_medida' => 'pack',
            ],
            [
                'name' => 'Lavavajillas',
                'categoria_id' => 10,
                'unidad_medida' => 'botella',
            ],
            [
                'name' => 'Suavizante',
                'categoria_id' => 10,
                'unidad_medida' => 'botella',
            ],
            [
                'name' => 'Bayetas',
                'categoria_id' => 10,
                'unidad_medida' => 'pack',
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

