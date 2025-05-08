<?php

namespace App\Http\Controllers;

use App\Models\Drink;

class CatalogController extends Controller
{
    public function index()
    {
        $drinks = \App\Models\Drink::with('ingredients')->get();
        return view('catalog', compact('drinks'));
    }

    public function show($id)
    {
        $drink = \App\Models\Drink::with('ingredients')->findOrFail($id);
        $allIngredients = \App\Models\Ingredient::all();

        return view('drink', compact('drink', 'allIngredients'));
    }
}
