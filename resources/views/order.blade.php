@extends('layouts.app')

@section('title', 'Ваш заказ')

@section('content')
    <h2 class="mb-4">Ваши заказы</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse ($orders as $order)
        @php
            $rawTotal = $order->items->sum('price');
            $bonusesApplied = $order->bonuses_used ?? 0;
            $finalTotal = $rawTotal - $bonusesApplied;
        @endphp

        <div class="card shadow-sm mb-4 border-0 rounded-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center rounded-top-4">
                <div>
                    <strong>Заказ №{{ $order->id }}</strong><br>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y H:i') }}</small>

                </div>
                <div>
                    @if ($order->payment_status === 'paid')
                        <span class="badge bg-success">Оплачен</span>
                    @else
                        <span class="badge bg-warning text-dark">Ожидает оплату</span>
                    @endif
                </div>
            </div>

            <div class="card-body">
                @foreach ($order->items as $item)
                    <div class="mb-3">
                        <h5 class="mb-2">{{ $item->drink_name }}</h5>

                        @php
                            $ingredients = json_decode($item->ingredients, true);
                        @endphp

                        @if(is_array($ingredients))
                            <ul class="list-unstyled ms-3">
                                @foreach ($ingredients as $ingredient)
                                    @if(is_array($ingredient))
                                        <li>• {{ $ingredient['name'] ?? 'Ингредиент' }} ({{ $ingredient['amount'] ?? '?' }})</li>
                                    @else
                                        <li>• {{ $ingredient }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">Состав недоступен</p>
                        @endif

                        <p class="fw-bold text-success">Цена: {{ $item->price }} ₽</p>
                    </div>
                @endforeach

                <hr>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div>Сумма: <strong>{{ $rawTotal }} ₽</strong></div>
                        @if ($bonusesApplied > 0)
                            <div>Списано бонусов: <strong class="text-primary">–{{ $bonusesApplied }} ₽</strong></div>
                        @endif
                        <div class="fw-bold">Итого к оплате: <span class="text-success">{{ $finalTotal }} ₽</span></div>
                    </div>

                    @if ($order->payment_status !== 'paid')
                        <form action="{{ route('order.pay.start', $order->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-primary">Перейти к оплате</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">У вас пока нет заказов.</p>
    @endforelse
@endsection
