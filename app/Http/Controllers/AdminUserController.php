<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::withCount('orders')->orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'bonus' => 'required|integer|min:0',
            'is_admin' => 'boolean'
        ]);

        $user->bonus = $request->bonus;
        $user->is_admin = $request->has('is_admin');
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Пользователь обновлён');
    }
}
