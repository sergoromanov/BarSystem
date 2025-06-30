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
        Drink::create([
            'name' => 'Латте',
            'category' => 'Кофе',
            'image_url' => 'latte.jpg',
            'price' => 190,
        ]);

        Drink::create([
            'name' => 'Негрони',
            'category' => 'Коктейли',
            'image_url' => 'negroni.jpg',
            'price' => 300,
        ]);

        Drink::create([
            'name' => 'Айриш Кофе',
            'category' => 'Кофе',
            'image_url' => 'irish_coffee.jpg',
            'price' => 260,
        ]);

        Drink::create([
            'name' => 'Шардоне',
            'category' => 'Вино',
            'image_url' => 'chardonnay.jpg',
            'price' => 340,
        ]);

        Drink::create([
            'name' => 'Эспрессо',
            'category' => 'Кофе',
            'image_url' => 'espresso.jpg',
            'price' => 120,
        ]);

    }
}
