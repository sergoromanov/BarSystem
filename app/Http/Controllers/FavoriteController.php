<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $favorites = Favorite::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        return view('favorites', compact('favorites'));
    }
    public function edit($id)
    {
        $favorite = Favorite::where('id', $id)->where('user_id', session('user_id'))->firstOrFail();
        $ingredients = \App\Models\Ingredient::all();

        return view('favorites.edit', compact('favorite', 'ingredients'));
    }
    public function update(Request $request, $id)
    {
        $favorite = Favorite::where('id', $id)->where('user_id', session('user_id'))->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'ingredients' => 'required|array|min:1',
            'ingredients.*' => 'exists:ingredients,id',
            'amounts' => 'array',
        ]);

        $ingredientData = [];

        foreach ($request->ingredients as $ingredientId) {
            $ingredient = \App\Models\Ingredient::find($ingredientId);
            $amount = trim($request->amounts[$ingredientId] ?? '');
            if ($ingredient) {
                $ingredientData[] = $ingredient->name . ($amount ? " — $amount мл" : '');
            }
        }

        $favorite->update([
            'name' => $request->name,
            'ingredients' => $ingredientData,
        ]);

        return redirect()->route('favorites')->with('success', 'Рецепт обновлён!');
    }
    public function destroy($id)
    {
        $favorite = Favorite::where('id', $id)->where('user_id', session('user_id'))->firstOrFail();
        $favorite->delete();

        return redirect()->route('favorites')->with('success', 'Рецепт удалён.');
    }


}
