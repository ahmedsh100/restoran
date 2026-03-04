<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        $foods = [
            [
                'name' => 'Burger Deluxe',
                'price' => 12.99,
                'category' => 'Main Course',
                'description' => 'Juicy beef patty with fresh lettuce, tomato, and special sauce',
                'image' => 'burger.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pizza Margherita',
                'price' => 10.99,
                'category' => 'Main Course',
                'description' => 'Classic Italian pizza with tomato sauce, mozzarella, and basil',
                'image' => 'pizza.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Caesar Salad',
                'price' => 8.99,
                'category' => 'Appetizer',
                'description' => 'Fresh romaine lettuce with Caesar dressing and croutons',
                'image' => 'salad.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Grilled Salmon',
                'price' => 18.99,
                'category' => 'Main Course',
                'description' => 'Fresh Atlantic salmon with herbs and lemon butter sauce',
                'image' => 'salmon.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chicken Wings',
                'price' => 9.99,
                'category' => 'Appetizer',
                'description' => 'Crispy fried chicken wings with BBQ sauce',
                'image' => 'wings.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pasta Carbonara',
                'price' => 13.99,
                'category' => 'Main Course',
                'description' => 'Creamy pasta with bacon, egg, and parmesan cheese',
                'image' => 'pasta.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chocolate Cake',
                'price' => 6.99,
                'category' => 'Dessert',
                'description' => 'Rich chocolate cake with ganache frosting',
                'image' => 'cake.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tiramisu',
                'price' => 7.99,
                'category' => 'Dessert',
                'description' => 'Classic Italian dessert with coffee and mascarpone',
                'image' => 'tiramisu.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('foods')->insert($foods);
    }
}
