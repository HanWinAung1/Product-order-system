<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Mechanical Keyboard', 'price' => 89.99, 'stock' => 50],
            ['name' => 'Gaming Mouse', 'price' => 45.50, 'stock' => 100],
            ['name' => '27-inch Monitor', 'price' => 299.99, 'stock' => 30],
            ['name' => 'Webcam HD', 'price' => 59.00, 'stock' => 25],
            ['name' => 'USB-C Hub', 'price' => 35.00, 'stock' => 80],
            ['name' => 'Wireless Headphones', 'price' => 120.00, 'stock' => 40],
            ['name' => 'Laptop Stand', 'price' => 25.99, 'stock' => 60],
            ['name' => 'Desk Mat (Large)', 'price' => 15.50, 'stock' => 150],
            ['name' => 'External SSD 1TB', 'price' => 110.00, 'stock' => 20],
            ['name' => 'LED Desk Lamp', 'price' => 42.00, 'stock' => 35],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
