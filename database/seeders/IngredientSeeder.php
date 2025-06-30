<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            ['name' => 'Сироп клубничный', 'price' => 5.0, 'stock' => 300, 'threshold' => 100],
            ['name' => 'Сок апельсиновый', 'price' => 4.0, 'stock' => 500, 'threshold' => 150],
            ['name' => 'Кола', 'price' => 3.0, 'stock' => 400, 'threshold' => 100],
            ['name' => 'Лёд', 'price' => 1.0, 'stock' => 1000, 'threshold' => 200],
            ['name' => 'Мята', 'price' => 2.5, 'stock' => 100, 'threshold' => 50],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
