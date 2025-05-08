<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Drink;

class DrinkSeeder extends Seeder
{
    public function run(): void
    {
        Drink::create([
            'name' => 'Мохито',
            'category' => 'Коктейли',
            'image_url' => 'mohito.jpg',
            'price' => 250,
        ]);

        Drink::create([
            'name' => 'Пина Колада',
            'category' => 'Коктейли',
            'image_url' => 'pina_colada.jpg',
            'price' => 280,
        ]);

        Drink::create([
            'name' => 'Каберне Совиньон',
            'category' => 'Вино',
            'image_url' => 'wine.jpg',
            'price' => 350,
        ]);

    }
}
