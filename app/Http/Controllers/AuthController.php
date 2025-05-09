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

        // Разрешаем номер 0000 для администратора
        if ($phone !== '0000') {
            $request->validate([
                'phone' => 'required|string|min:10|max:20'
            ]);
        }

        // Генерация 4-значного кода
        $code = rand(1000, 9999);

        // Сохраняем код
        \App\Models\LoginCode::create([
            'phone' => $phone,
            'code' => $code,
        ]);

        // Проверка на админский пароль
        if ($phone === '0000' && $request->filled('admin_password')) {
            if ($request->admin_password === env('ADMIN_PASSWORD')) {
                // Админ успешно вошёл
                $user = \App\Models\User::firstOrCreate(['phone' => $phone], ['bonus' => 0]);
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

        // Лог для отладки
        logger("Код $code отправлен на $phone");

        // В режиме разработки — показать код сразу
        if (app()->environment('local')) {
            session(['phone' => $phone]);
            return view('auth.verify', [
                'phone' => $phone,
                'code' => $code
            ]);
        }

        // Переход на ввод кода
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
