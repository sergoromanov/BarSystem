<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoginCode;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function sendCode(Request $request)
    {
        $phone = $request->phone;

        // Вход как админ
        if ($phone === env('ADMIN_PHONE') && $request->filled('admin_password')) {
            if ($request->admin_password === env('ADMIN_PASSWORD')) {
                $user = User::firstOrCreate(['phone' => $phone], ['bonus' => 0]);
                $user->is_admin = true;
                $user->save();

                session([
                    'user_id' => $user->id,
                    'user_phone' => $user->phone,
                ]);

                return redirect()->route('admin.dashboard');
            } else {
                return back()->withErrors(['admin_password' => 'Неверный пароль администратора'])->withInput();
            }
        }

        // Вход как бармен
        if ($phone === env('BARMAN_PHONE') && $request->filled('barman_password')) {
            if ($request->barman_password === env('BARMAN_PASSWORD')) {
                $user = User::firstOrCreate(['phone' => $phone], ['bonus' => 0]);
                $user->is_barman = true;
                $user->save();

                session([
                    'user_id' => $user->id,
                    'user_phone' => $user->phone,
                ]);

                return redirect()->route('barman.dashboard');
            } else {
                return back()->withErrors(['barman_password' => 'Неверный пароль бармена'])->withInput();
            }
        }

        // Валидация обычного телефона
        if (!in_array($phone, [env('ADMIN_PHONE'), env('BARMAN_PHONE')])) {
            $request->validate([
                'phone' => 'required|string|min:10|max:20'
            ]);
        }

        // Генерация 4-значного кода
        $code = rand(1000, 9999);

        // Сохраняем код
        LoginCode::create([
            'phone' => $phone,
            'code' => $code,
        ]);

        session(['phone' => $phone]);

        if (app()->environment('local')) {
            return view('auth.verify', [
                'phone' => $phone,
                'code' => $code
            ]);
        }

        return redirect()->route('auth.verify')->with('phone', $phone);
    }



    public function showVerificationForm()
    {
        $phone = session('phone');
        return view('auth.verify', compact('phone'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string|size:4'
        ]);

        $code = LoginCode::where('phone', $request->phone)
            ->where('code', $request->code)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->latest()
            ->first();

        if (!$code) {
            return back()->withErrors(['code' => 'Неверный или просроченный код']);
        }

        // Найти или создать пользователя
        $user = User::firstOrCreate(['phone' => $request->phone], ['bonus' => 0]);

        // Логин через session
        session(['user_id' => $user->id, 'user_phone' => $user->phone]);

        return redirect()->route('catalog');

        if ($request->filled('admin_password')) {
            if ($request->admin_password === env('ADMIN_PASSWORD')) {
                $user->is_admin = true;
                $user->save();
            } else {
                return back()->withErrors(['admin_password' => 'Неверный пароль администратора']);
            }
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('auth.login');
    }
}
