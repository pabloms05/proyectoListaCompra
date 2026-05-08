<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nombre' => 'Frutas',
                'image' => 'https://cdn-icons-png.flaticon.com/512/415/415733.png',
            ],
            [
                'nombre' => 'Verduras',
                'image' => 'https://cdn-icons-png.flaticon.com/512/766/766149.png',
            ],
            [
                'nombre' => 'Carnes',
                'image' => 'https://cdn-icons-png.flaticon.com/512/706/706195.png',
            ],
            [
                'nombre' => 'Pescados y Mariscos',
                'image' => 'https://cdn-icons-png.flaticon.com/512/1046/1046784.png',
            ],
            [
                'nombre' => 'Lácteos',
                'image' => 'https://cdn-icons-png.flaticon.com/512/2965/2965567.png',
            ],
            [
                'nombre' => 'Panadería y Cereales',
                'image' => 'https://cdn-icons-png.flaticon.com/512/415/415747.png',
            ],
            [
                'nombre' => 'Bebidas',
                'image' => 'https://cdn-icons-png.flaticon.com/512/2738/2738651.png',
            ],
            [
                'nombre' => 'Snacks y Dulces',
                'image' => 'https://cdn-icons-png.flaticon.com/512/3075/3075977.png',
            ],
            [
                'nombre' => 'Congelados',
                'image' => 'https://cdn-icons-png.flaticon.com/512/1046/1046798.png',
            ],
            [
                'nombre' => 'Limpieza y Hogar',
                'image' => 'https://cdn-icons-png.flaticon.com/512/1046/1046874.png',
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categorias')->insert([
                'nombre' => $category['nombre'],
                'image' => $category['image'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
