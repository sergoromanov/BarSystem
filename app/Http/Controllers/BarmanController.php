<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class BarmanController extends Controller
{
    public function index()
    {
        $user = \App\Models\User::find(session('user_id'));
        if (!$user || !$user->is_barman) {
            abort(403);
        }

        $orders = Order::with('items')->orderByDesc('created_at')->get();
        return view('barman.dashboard', compact('orders'));
    }

    public function updateStatus(Request $request, $orderId)
    {
        $user = \App\Models\User::find(session('user_id'));
        if (!$user || !$user->is_barman) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|string|in:готовится,готово,выдано',
        ]);

        $order = Order::findOrFail($orderId);
        $order->status = $request->input('status');
        $order->save();

        return redirect()->back()->with('success', 'Статус обновлён.');
    }
    public function orders()
    {
        $user = \App\Models\User::find(session('user_id'));
        if (!$user || !$user->is_barman) {
            abort(403);
        }

        $orders = \App\Models\Order::with('items')->orderByDesc('created_at')->get();
        return view('barman.orders', compact('orders'));
    }
}
