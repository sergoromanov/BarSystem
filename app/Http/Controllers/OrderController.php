<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Drink;
use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Favorite;
class OrderController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $orders = Order::with('items')->where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        return view('order', compact('orders'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'drink_id' => 'required|exists:drinks,id',
        ]);

        $drink = Drink::with('ingredients')->findOrFail($request->drink_id);
        $userId = session('user_id');

        $order = Order::create([
            'user_id' => $userId,
            'created_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'drink_name' => $drink->name,
            'ingredients' => json_encode($drink->ingredients->pluck('name')),
            'price' => $drink->price, // ← добавили
        ]);

        $user = User::find($userId);
        $user->bonus += 1;
        $user->save();

        return redirect()->route('order')->with('success', 'Напиток добавлен в заказ!');
    }

    public function customOrder(Request $request, $id)
    {
        $request->validate([
            'ingredients' => 'required|array|min:1',
            'ingredients.*' => 'exists:ingredients,id',
            'amounts' => 'array',
        ]);

        $drink = Drink::findOrFail($id);
        $userId = session('user_id');

        $ingredientIds = $request->input('ingredients', []);
        $amounts = $request->input('amounts', []);

        $ingredientData = [];
        $totalPrice = 0;

        if ($request->has('save_favorite')) {
            foreach ($ingredientIds as $ingredientId) {
                $ingredient = Ingredient::find($ingredientId);
                $amount = trim($amounts[$ingredientId] ?? '');

                if (is_numeric($amount) && (int)$amount > 100) {
                    return back()->withErrors([
                        'amounts' => "Ингредиент \"{$ingredient->name}\" превышает лимит: максимум 100 мл"
                    ])->withInput();
                }

                if ($ingredient) {
                    $ingredientData[] = $ingredient->name . ($amount ? " — $amount мл" : '');
                }
            }

            Favorite::create([
                'user_id' => $userId,
                'drink_id' => $drink->id,
                'name' => $drink->name . ' (мой рецепт)',
                'ingredients' => $ingredientData,
            ]);

            return back()->with('success', 'Рецепт сохранён в избранное!');
        }

        foreach ($ingredientIds as $ingredientId) {
            $ingredient = Ingredient::find($ingredientId);
            $amount = trim($amounts[$ingredientId] ?? '');

            if (is_numeric($amount) && (int)$amount > 100) {
                return back()->withErrors([
                    'amounts' => "Ингредиент \"{$ingredient->name}\" превышает лимит: максимум 100 мл"
                ])->withInput();
            }

            if ($ingredient) {
                $ingredientData[] = $ingredient->name . ($amount ? " — $amount мл" : '');

                // 🧮 Расчёт стоимости: цена × (объём / 10)
                if (is_numeric($amount)) {
                    $totalPrice += ($amount / 10) * $ingredient->price;
                }
            }
        }

        $order = Order::create([
            'user_id' => $userId,
            'created_at' => now(),
        ]);


        OrderItem::create([
            'order_id' => $order->id,
            'drink_name' => $drink->name . ' (кастом)',
            'ingredients' => json_encode($ingredientData),
            'price' => round($totalPrice, 2), // 💰 сохраняем итог
        ]);

        $user = User::find($userId);
        $user->bonus += 1;
        $user->save();

        return redirect()->route('order')->with('success', 'Кастомный напиток добавлен!');
    }
    public function pay($id)
    {
        $userId = session('user_id');
        $order = Order::where('id', $id)->where('user_id', $userId)->firstOrFail();

        $order->is_paid = true;
        $order->save();

        return redirect()->route('order')->with('success', 'Заказ оплачен!');
    }
    public function startPayment($id)
    {
        $userId = session('user_id');
        $order = Order::where('id', $id)->where('user_id', $userId)->firstOrFail();

        $order->update([
            'payment_status' => 'pending',
            'payment_id' => 'fake_' . uniqid(),
        ]);

        return redirect()->route('payment.fake', $order->id);
    }
    public function showFakePayment($id)
    {
        $userId = session('user_id');
        $order = Order::where('id', $id)->where('user_id', $userId)->firstOrFail();

        return view('payment.fake', compact('order'));
    }
    public function confirmFakePayment($id)
    {
        $userId = session('user_id');
        $order = Order::where('id', $id)->where('user_id', $userId)->firstOrFail();

        $order->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'is_paid' => true,
        ]);

        return redirect()->route('order')->with('success', 'Оплата прошла успешно (имитация).');
    }







}
