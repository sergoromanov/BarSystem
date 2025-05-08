<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    // Показ формы входа
    public function showLogin()
    {
        return view('login');
    }

    // Обработка входа
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:20'
        ]);

        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            ['bonus' => 0]
        );

        session([
            'user_id' => $user->id,
            'user_phone' => $user->phone, // ← добавляем это
        ]);

        return redirect()->route('home');
    }

    // Выход
    public function logout()
    {
        session()->flush(); // очищает всю сессию
        return redirect()->route('login');
    }
}
