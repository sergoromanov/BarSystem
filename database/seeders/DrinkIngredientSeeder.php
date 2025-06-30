<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Drink;
use App\Models\Ingredient;

class DrinkIngredientSeeder extends Seeder
{
    public function run(): void
    {
        $mojito = Drink::where('name', 'Мохито')->first();
        $mojito->ingredients()->attach([
            Ingredient::where('name', 'Ром')->first()->id => ['amount' => '50 мл'],
            Ingredient::where('name', 'Мята')->first()->id => ['amount' => '10 г'],
            Ingredient::where('name', 'Сахар')->first()->id => ['amount' => '15 г'],
            Ingredient::where('name', 'Лайм')->first()->id => ['amount' => '20 г'],
            Ingredient::where('name', 'Содовая')->first()->id => ['amount' => '100 мл'],
        ]);

        $pina = Drink::where('name', 'Пина Колада')->first();
        $pina->ingredients()->attach([
            Ingredient::where('name', 'Ром')->first()->id => ['amount' => '40 мл'],
            Ingredient::where('name', 'Ананас')->first()->id => ['amount' => '50 мл'],
            Ingredient::where('name', 'Кокосовое молоко')->first()->id => ['amount' => '30 мл'],
        ]);

        $wine = Drink::where('name', 'Каберне Совиньон')->first();
        $wine->ingredients()->attach([
            Ingredient::where('name', 'Виноград')->first()->id => ['amount' => '150 мл'],
        ]);
        $latte = Drink::where('name', 'Латте')->first();
        $latte->ingredients()->attach([
            Ingredient::where('name', 'Кофе')->first()->id => ['amount' => '50 мл'],
            Ingredient::where('name', 'Молоко')->first()->id => ['amount' => '150 мл'],
            Ingredient::where('name', 'Сахар')->first()->id => ['amount' => '10 г'],
        ]);

        $negroni = Drink::where('name', 'Негрони')->first();
        $negroni->ingredients()->attach([
            Ingredient::where('name', 'Джин')->first()->id => ['amount' => '30 мл'],
            Ingredient::where('name', 'Кампари')->first()->id => ['amount' => '30 мл'],
            Ingredient::where('name', 'Красный вермут')->first()->id => ['amount' => '30 мл'],
        ]);

        $irish = Drink::where('name', 'Айриш Кофе')->first();
        $irish->ingredients()->attach([
            Ingredient::where('name', 'Кофе')->first()->id => ['amount' => '80 мл'],
            Ingredient::where('name', 'Виски')->first()->id => ['amount' => '40 мл'],
            Ingredient::where('name', 'Взбитые сливки')->first()->id => ['amount' => '30 мл'],
        ]);

        $shardonnay = Drink::where('name', 'Шардоне')->first();
        $shardonnay->ingredients()->attach([
            Ingredient::where('name', 'Виноград')->first()->id => ['amount' => '150 мл'],
        ]);

        $espresso = Drink::where('name', 'Эспрессо')->first();
        $espresso->ingredients()->attach([
            Ingredient::where('name', 'Кофе')->first()->id => ['amount' => '50 мл'],
        ]);
    }
}
