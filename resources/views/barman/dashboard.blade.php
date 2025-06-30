@extends('layouts.app')

@section('title', 'Панель бармена')

@section('content')
    <h2 class="mb-4">Панель бармена</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row row-cols-1 row-cols-md-2 g-4">
        @foreach($orders as $order)
            @php
                $isReady = $order->status === 'готово';
                $cardClass = $isReady ? 'bg-success-subtle border-success' : 'bg-danger-subtle border-danger';
                $statusText = $isReady ? 'Готово' : 'Готовится';
                $nextStatus = $isReady ? 'готовится' : 'готово';
            @endphp

            <div class="col">
                <form action="{{ route('barman.orders.updateStatus', $order->id) }}" method="POST" class="order-form">
                    @csrf
                    <input type="hidden" name="status" value="{{ $nextStatus }}">
                    <button type="submit" class="card text-start p-3 shadow-sm border-2 rounded-4 w-100 {{ $cardClass }}"
                            style="cursor: pointer;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong class="fs-5">Заказ №{{ $order->id }}</strong>
                            <span class="badge fs-6 bg-{{ $isReady ? 'success' : 'danger' }}">
                                {{ $statusText }}
                            </span>
                        </div>
                        <div class="text-muted small mb-2">
                            {{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y H:i') }}
                        </div>

                        @foreach ($order->items as $item)
                            <div class="mb-2">
                                <strong>{{ $item->drink_name }}</strong>
                                <ul class="list-unstyled ms-3 mb-1">
                                    @foreach (json_decode($item->ingredients, true) as $ingredient)
                                        <li>• {{ $ingredient }}</li>
                                    @endforeach
                                </ul>
                                <p class="text-success fw-bold mb-0">Цена: {{ $item->price }} ₽</p>
                            </div>
                        @endforeach
                    </button>
                </form>
            </div>
        @endforeach
    </div>

    <script>
        setTimeout(() => location.reload(), 30000);
    </script>
@endsection
