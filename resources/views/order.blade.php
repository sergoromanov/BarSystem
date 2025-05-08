@extends('layouts.app')

@section('title', 'Ваш заказ')

@section('content')
    <h2 class="mb-4">Ваши заказы</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse ($orders as $order)
        @php
            $total = $order->items->sum('price');
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
                        <span class="badge bg-secondary">Ожидает оплату</span>
                    @endif
                </div>
            </div>

            <div class="card-body">
                @foreach ($order->items as $item)
                    <div class="mb-3">
                        <h5 class="mb-2">{{ $item->drink_name }}</h5>
                        <ul class="list-unstyled ms-3">
                            @foreach (json_decode($item->ingredients, true) as $ingredient)
                                <li>• {{ $ingredient }}</li>
                            @endforeach
                        </ul>
                        <p class="fw-bold text-success">Цена: {{ $item->price }} ₽</p>
                    </div>
                @endforeach

                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="fw-bold">Итого: <span class="text-success">{{ $total }} ₽</span></div>

                    @if ($order->payment_status !== 'paid')
                        <form action="{{ route('order.pay.start', $order->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-primary"> Перейти к оплате</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">У вас пока нет заказов.</p>
    @endforelse
@endsection
