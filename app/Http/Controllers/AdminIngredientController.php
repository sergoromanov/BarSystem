<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class AdminIngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::orderBy('name')->get();
        return view('admin.ingredients.index', compact('ingredients'));
    }

    public function create()
    {
        return view('admin.ingredients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'threshold' => 'required|integer|min:0',
            'unit' => 'required|string|max:10',
        ]);

        Ingredient::create($request->only('name', 'price', 'stock', 'threshold', 'unit'));

        return redirect()->route('admin.ingredients.index')->with('success', 'Ингредиент добавлен');
    }

    public function edit($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        return view('admin.ingredients.edit', compact('ingredient'));
    }

    public function update(Request $request, $id)
    {
        $ingredient = \App\Models\Ingredient::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name,' . $id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'threshold' => 'required|integer|min:0',
            'unit' => 'required|string|max:10',
        ]);

        $ingredient->update($request->only('name', 'price', 'stock', 'threshold', 'unit'));

        return redirect()->route('admin.ingredients.index')->with('success', 'Ингредиент обновлён');
    }
    public function destroy($id)
    {
        Ingredient::findOrFail($id)->delete();
        return redirect()->route('admin.ingredients.index')->with('success', 'Ингредиент удалён');
    }
}
