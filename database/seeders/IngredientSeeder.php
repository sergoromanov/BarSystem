<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            ['Ром', 3.5],
            ['Мята', 1.0],
            ['Сахар', 0.5],
            ['Лайм', 2.0],
            ['Содовая', 0.8],
            ['Ананас', 1.8],
            ['Кокосовое молоко', 2.5],
            ['Кофе', 1.5],
            ['Ячмень', 1.0],
            ['Хмель', 0.7],
            ['Виноград', 2.2],
        ];

        foreach ($ingredients as [$name, $price]) {
            \App\Models\Ingredient::create([
                'name' => $name,
                'price' => $price,
            ]);
        }
    }
}
