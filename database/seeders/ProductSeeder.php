<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Teclado mecanico',
                'description' => 'Teclado mecanico con iluminacion RGB.',
                'price' => 65.99,
                'stock' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Mouse inalambrico',
                'description' => 'Mouse ergonomico con conexion USB.',
                'price' => 24.50,
                'stock' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Audifonos USB',
                'description' => 'Audifonos con microfono para llamadas y videojuegos.',
                'price' => 39.99,
                'stock' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Monitor 24 pulgadas',
                'description' => 'Monitor Full HD con entrada HDMI.',
                'price' => 189.99,
                'stock' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Webcam Full HD',
                'description' => 'Camara web con resolucion 1080p.',
                'price' => 49.99,
                'stock' => 12,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}
