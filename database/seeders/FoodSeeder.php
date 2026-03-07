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
                'image' => 'assets/img/menu-1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pizza Margherita',
                'price' => 10.99,
                'category' => 'Main Course',
                'description' => 'Classic Italian pizza with tomato sauce, mozzarella, and basil',
                'image' => 'assets/img/menu-2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Caesar Salad',
                'price' => 8.99,
                'category' => 'Appetizer',
                'description' => 'Fresh romaine lettuce with Caesar dressing and croutons',
                'image' => 'assets/img/menu-3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Grilled Salmon',
                'price' => 18.99,
                'category' => 'Main Course',
                'description' => 'Fresh Atlantic salmon with herbs and lemon butter sauce',
                'image' => 'assets/img/menu-4.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chicken Wings',
                'price' => 9.99,
                'category' => 'Appetizer',
                'description' => 'Crispy fried chicken wings with BBQ sauce',
                'image' => 'assets/img/menu-5.jpg',
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
