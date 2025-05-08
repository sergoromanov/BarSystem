@extends('layouts.app')

@section('title', 'Каталог напитков')

@section('content')
    <div class="row">
        {{-- Левая колонка: категории (будет сверху на маленьких экранах) --}}
        <div class="col-12 col-md-3 mb-4">
            <h5>Категории</h5>
            <ul class="list-group">
                <li class="list-group-item {{ request('category') == null ? 'active' : '' }}">
                    <a href="{{ route('catalog') }}" class="text-decoration-none">Все</a>
                </li>
                @php
                    $categories = $drinks->pluck('category')->unique()->filter();
                @endphp
                @foreach ($categories as $cat)
                    <li class="list-group-item {{ request('category') == $cat ? 'active' : '' }}">
                        <a href="{{ route('catalog', ['category' => $cat]) }}" class="text-decoration-none">{{ $cat }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Правая колонка: напитки --}}
        <div class="col-12 col-md-9">
            <h3 class="mb-4">Напитки</h3>

            <div class="row g-4">
                @php
                    $filteredDrinks = request('category')
                        ? $drinks->where('category', request('category'))
                        : $drinks;
                @endphp

                @forelse ($filteredDrinks as $drink)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ asset('images/' . $drink->image_url) }}"
                                 class="card-img-top"
                                 style="height: 200px; object-fit: cover;"
                                 alt="{{ $drink->name }}">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title">{{ $drink->name }}</h5>
                                    <p class="card-text">
                                        <strong>Состав:</strong><br>
                                        @foreach ($drink->ingredients as $ing)
                                            {{ $ing->name }} — {{ $ing->pivot->amount }}<br>
                                        @endforeach
                                    </p>
                                    <p class="card-text mb-1">
                                        <strong>Цена:</strong> {{ $drink->price }} ₽
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <a href="{{ route('drink', $drink->id) }}" class="btn btn-sm btn-outline-primary w-100 me-2">Конструктор</a>
                                    <form action="{{ route('order.add') }}" method="POST" class="w-100">
                                        @csrf
                                        <input type="hidden" name="drink_id" value="{{ $drink->id }}">
                                        <button type="submit" class="btn btn-sm btn-success w-100">Добавить</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Нет напитков в этой категории.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
