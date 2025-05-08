@extends('layouts.app')

@section('title', 'Имитация оплаты')

@section('content')
    <h2 class="mb-4">Оплата заказа №{{ $order->id }}</h2>

    <p>Сумма к оплате: <strong>{{ $order->items->sum('price') }} ₽</strong></p>
    <p class="text-muted">Это тестовая платёжная страница. Никакие деньги не списываются 🙂</p>

    <form action="{{ route('payment.fake.confirm', $order->id) }}" method="POST">
        @csrf
        <button class="btn btn-success">Оплатить</button>
        <a href="{{ route('order') }}" class="btn btn-outline-secondary ms-2">Отмена</a>
    </form>
@endsection
