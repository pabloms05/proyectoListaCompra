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
                'precio' => 2.50,
            ],
            [
                'name' => 'Plátano',
                'categoria_id' => 1,
                'unidad_medida' => 'kg',
                'precio' => 1.80,
            ],
            [
                'name' => 'Naranja',
                'categoria_id' => 1,
                'unidad_medida' => 'kg',
                'precio' => 1.90,
            ],
            [
                'name' => 'Pera',
                'categoria_id' => 1,
                'unidad_medida' => 'kg',
                'precio' => 2.30,
            ],
            [
                'name' => 'Uvas',
                'categoria_id' => 1,
                'unidad_medida' => 'kg',
                'precio' => 3.50,
            ],

            // Verduras (id: 2)
            [
                'name' => 'Lechuga',
                'categoria_id' => 2,
                'unidad_medida' => 'unidad',
                'precio' => 1.20,
            ],
            [
                'name' => 'Tomate',
                'categoria_id' => 2,
                'unidad_medida' => 'kg',
                'precio' => 2.80,
            ],
            [
                'name' => 'Zanahoria',
                'categoria_id' => 2,
                'unidad_medida' => 'kg',
                'precio' => 1.50,
            ],
            [
                'name' => 'Cebolla',
                'categoria_id' => 2,
                'unidad_medida' => 'kg',
                'precio' => 1.30,
            ],
            [
                'name' => 'Pimiento',
                'categoria_id' => 2,
                'unidad_medida' => 'kg',
                'precio' => 3.20,
            ],

            // Carnes (id: 3)
            [
                'name' => 'Pechuga de Pollo',
                'categoria_id' => 3,
                'unidad_medida' => 'kg',
                'precio' => 6.50,
            ],
            [
                'name' => 'Carne Picada',
                'categoria_id' => 3,
                'unidad_medida' => 'kg',
                'precio' => 7.80,
            ],
            [
                'name' => 'Lomo de Cerdo',
                'categoria_id' => 3,
                'unidad_medida' => 'kg',
                'precio' => 8.90,
            ],
            [
                'name' => 'Chuletas de Cordero',
                'categoria_id' => 3,
                'unidad_medida' => 'kg',
                'precio' => 15.50,
            ],
            [
                'name' => 'Solomillo de Ternera',
                'categoria_id' => 3,
                'unidad_medida' => 'kg',
                'precio' => 18.90,
            ],

            // Pescados y Mariscos (id: 4)
            [
                'name' => 'Salmón',
                'categoria_id' => 4,
                'unidad_medida' => 'kg',
                'precio' => 16.50,
            ],
            [
                'name' => 'Merluza',
                'categoria_id' => 4,
                'unidad_medida' => 'kg',
                'precio' => 12.80,
            ],
            [
                'name' => 'Gambas',
                'categoria_id' => 4,
                'unidad_medida' => 'kg',
                'precio' => 22.50,
            ],
            [
                'name' => 'Mejillones',
                'categoria_id' => 4,
                'unidad_medida' => 'kg',
                'precio' => 4.90,
            ],
            [
                'name' => 'Atún Fresco',
                'categoria_id' => 4,
                'unidad_medida' => 'kg',
                'precio' => 14.90,
            ],

            // Lácteos (id: 5)
            [
                'name' => 'Leche Entera',
                'categoria_id' => 5,
                'unidad_medida' => 'litro',
                'precio' => 1.10,
            ],
            [
                'name' => 'Yogur Natural',
                'categoria_id' => 5,
                'unidad_medida' => 'pack',
                'precio' => 2.50,
            ],
            [
                'name' => 'Queso Fresco',
                'categoria_id' => 5,
                'unidad_medida' => 'unidad',
                'precio' => 3.20,
            ],
            [
                'name' => 'Mantequilla',
                'categoria_id' => 5,
                'unidad_medida' => 'unidad',
                'precio' => 2.80,
            ],
            [
                'name' => 'Huevos',
                'categoria_id' => 5,
                'unidad_medida' => 'docena',
                'precio' => 3.50,
            ],

            // Panadería y Cereales (id: 6)
            [
                'name' => 'Pan Integral',
                'categoria_id' => 6,
                'unidad_medida' => 'unidad',
                'precio' => 1.80,
            ],
            [
                'name' => 'Baguette',
                'categoria_id' => 6,
                'unidad_medida' => 'unidad',
                'precio' => 0.90,
            ],
            [
                'name' => 'Cereales Desayuno',
                'categoria_id' => 6,
                'unidad_medida' => 'caja',
                'precio' => 3.50,
            ],
            [
                'name' => 'Arroz',
                'categoria_id' => 6,
                'unidad_medida' => 'kg',
                'precio' => 1.80,
            ],
            [
                'name' => 'Pasta',
                'categoria_id' => 6,
                'unidad_medida' => 'paquete',
                'precio' => 1.20,
            ],

            // Bebidas (id: 7)
            [
                'name' => 'Agua Mineral',
                'categoria_id' => 7,
                'unidad_medida' => 'litro',
                'precio' => 0.50,
            ],
            [
                'name' => 'Refresco Cola',
                'categoria_id' => 7,
                'unidad_medida' => 'litro',
                'precio' => 1.80,
            ],
            [
                'name' => 'Zumo Naranja',
                'categoria_id' => 7,
                'unidad_medida' => 'litro',
                'precio' => 2.20,
            ],
            [
                'name' => 'Cerveza',
                'categoria_id' => 7,
                'unidad_medida' => 'pack',
                'precio' => 8.50,
            ],
            [
                'name' => 'Vino Tinto',
                'categoria_id' => 7,
                'unidad_medida' => 'botella',
                'precio' => 6.90,
            ],

            // Snacks y Dulces (id: 8)
            [
                'name' => 'Patatas Fritas',
                'categoria_id' => 8,
                'unidad_medida' => 'bolsa',
                'precio' => 1.50,
            ],
            [
                'name' => 'Chocolate',
                'categoria_id' => 8,
                'unidad_medida' => 'tableta',
                'precio' => 2.30,
            ],
            [
                'name' => 'Galletas',
                'categoria_id' => 8,
                'unidad_medida' => 'paquete',
                'precio' => 2.80,
            ],
            [
                'name' => 'Frutos Secos',
                'categoria_id' => 8,
                'unidad_medida' => 'bolsa',
                'precio' => 4.50,
            ],
            [
                'name' => 'Gominolas',
                'categoria_id' => 8,
                'unidad_medida' => 'bolsa',
                'precio' => 1.80,
            ],

            // Congelados (id: 9)
            [
                'name' => 'Pizza Congelada',
                'categoria_id' => 9,
                'unidad_medida' => 'unidad',
                'precio' => 3.50,
            ],
            [
                'name' => 'Verduras Congeladas',
                'categoria_id' => 9,
                'unidad_medida' => 'bolsa',
                'precio' => 2.20,
            ],
            [
                'name' => 'Helado',
                'categoria_id' => 9,
                'unidad_medida' => 'litro',
                'precio' => 4.50,
            ],
            [
                'name' => 'Pescado Congelado',
                'categoria_id' => 9,
                'unidad_medida' => 'kg',
                'precio' => 8.90,
            ],
            [
                'name' => 'Croquetas',
                'categoria_id' => 9,
                'unidad_medida' => 'caja',
                'precio' => 5.50,
            ],

            // Limpieza y Hogar (id: 10)
            [
                'name' => 'Detergente Lavadora',
                'categoria_id' => 10,
                'unidad_medida' => 'botella',
                'precio' => 8.50,
            ],
            [
                'name' => 'Papel Higiénico',
                'categoria_id' => 10,
                'unidad_medida' => 'pack',
                'precio' => 6.90,
            ],
            [
                'name' => 'Lavavajillas',
                'categoria_id' => 10,
                'unidad_medida' => 'botella',
                'precio' => 3.50,
            ],
            [
                'name' => 'Suavizante',
                'categoria_id' => 10,
                'unidad_medida' => 'botella',
                'precio' => 4.20,
            ],
            [
                'name' => 'Bayetas',
                'categoria_id' => 10,
                'unidad_medida' => 'pack',
                'precio' => 2.50,
            ],
        ];

        foreach ($productos as $producto) {
            DB::table('productos')->insert([
                'name' => $producto['name'],
                'categoria_id' => $producto['categoria_id'],
                'unidad_medida' => $producto['unidad_medida'],
                'precio' => $producto['precio'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}

