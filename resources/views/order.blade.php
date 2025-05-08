@extends('layouts.app')

@section('title', 'Ваш заказ')

@section('content')
    <h2 class="mb-4">Ваши заказы</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse ($orders as $order)
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Заказ №{{ $order->id }} — {{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y H:i') }}</span>

                @if ($order->is_paid)
                    <span class="badge bg-success">Оплачен</span>
                @else
                    <form action="{{ route('order.pay', $order->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-outline-primary">Оплатить</button>
                    </form>
                @endif
            </div>
            <div class="card-body">
                @foreach ($order->items as $item)
                    <div class="mb-3">
                        <h5>{{ $item->drink_name }}</h5>
                        <p class="text-muted small">Цена: {{ $item->price }} ₽</p>
                        <ul class="mb-0">
                            @foreach (json_decode($item->ingredients, true) as $ingredient)
                                <li>{{ $ingredient }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach

                @php
                    $total = $order->items->sum('price');
                @endphp
                <p class="fw-bold">Итого: {{ $total }} ₽</p>
            </div>
        </div>
    @empty
        <p>У вас пока нет заказов.</p>
    @endforelse
@endsection
