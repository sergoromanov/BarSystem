<?php

namespace App\Http\Controllers;

use App\Models\Drink;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class AdminDrinkController extends Controller
{
    public function index()
    {
        $drinks = Drink::with('ingredients')->orderBy('created_at', 'desc')->get();
        return view('admin.drinks.index', compact('drinks'));
    }

    public function create()
    {
        $ingredients = Ingredient::all();
        return view('admin.drinks.create', compact('ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ingredients' => 'array',
            'ingredients.*' => 'exists:ingredients,id',
            'amounts' => 'array',
        ]);

        $drink = Drink::create($request->only('name', 'category', 'price', 'image_url'));

        foreach ($request->ingredients ?? [] as $id) {
            $amount = $request->amounts[$id] ?? '';
            $drink->ingredients()->attach($id, ['amount' => $amount]);
        }

        return redirect()->route('admin.drinks.index')->with('success', 'Напиток добавлен');
        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $filename);
            $drink->image_url = $filename;
            $drink->save();
        }

    }

    public function edit($id)
    {
        $drink = Drink::with('ingredients')->findOrFail($id);
        $ingredients = Ingredient::all();
        return view('admin.drinks.edit', compact('drink', 'ingredients'));
    }

    public function update(Request $request, $id)
    {
        $drink = Drink::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ingredients' => 'array',
            'ingredients.*' => 'exists:ingredients,id',
            'amounts' => 'array',
        ]);

        $drink->update($request->only('name', 'category', 'price', 'image_url'));
        $drink->ingredients()->detach();

        foreach ($request->ingredients ?? [] as $id) {
            $amount = $request->amounts[$id] ?? '';
            $drink->ingredients()->attach($id, ['amount' => $amount]);
        }

        return redirect()->route('admin.drinks.index')->with('success', 'Напиток обновлён');
        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $filename);
            $drink->image_url = $filename;
            $drink->save();
        }

    }

    public function destroy($id)
    {
        $drink = Drink::findOrFail($id);
        $drink->delete();
        return redirect()->route('admin.drinks.index')->with('success', 'Напиток удалён');
    }
}
