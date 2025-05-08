@extends('layouts.app')

@section('title', 'Каталог напитков')

@section('content')
    {{-- Категории — горизонтально на мобильных --}}
    <div class="d-block d-md-none mb-4">
        <div class="d-flex overflow-auto gap-2 pb-2">
            <a href="{{ route('catalog') }}"
               class="btn btn-outline-dark btn-sm {{ request('category') == null ? 'active' : '' }}">
                Все
            </a>
            @foreach ($drinks->pluck('category')->unique()->filter() as $cat)
                <a href="{{ route('catalog', ['category' => $cat]) }}"
                   class="btn btn-outline-dark btn-sm {{ request('category') == $cat ? 'active' : '' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="row">
        {{-- Категории — вертикально на десктопе --}}
        <div class="col-md-3 d-none d-md-block mb-4">
            <h5 class="mb-3">Категории</h5>
            <ul class="list-group shadow-sm">
                <li class="list-group-item {{ request('category') == null ? 'active' : '' }}">
                    <a href="{{ route('catalog') }}" class="text-decoration-none text-dark d-block">Все</a>
                </li>
                @foreach ($drinks->pluck('category')->unique()->filter() as $cat)
                    <li class="list-group-item {{ request('category') == $cat ? 'active' : '' }}">
                        <a href="{{ route('catalog', ['category' => $cat]) }}" class="text-decoration-none text-dark d-block">{{ $cat }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Напитки --}}
        <div class="col-md-9">
            <h3 class="mb-4">Напитки</h3>

            @php
                $filteredDrinks = request('category')
                    ? $drinks->where('category', request('category'))
                    : $drinks;
            @endphp

            {{-- Десктоп: 3 карточки в ряд с hover-кнопкой --}}
            <div class="row row-cols-1 row-cols-md-3 g-4 d-none d-md-flex">
                @forelse ($filteredDrinks as $drink)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden position-relative">
                            <div class="position-relative">
                                <img src="{{ asset('images/' . $drink->image_url) }}"
                                     class="card-img-top"
                                     style="height: 200px; object-fit: cover;"
                                     alt="{{ $drink->name }}">

                                <form action="{{ route('order.add') }}" method="POST"
                                      class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center overlay-form">
                                    @csrf
                                    <input type="hidden" name="drink_id" value="{{ $drink->id }}">
                                    <button type="submit" class="btn btn-outline-light px-4 py-2 shadow">Добавить</button>
                                </form>
                            </div>

                            <div class="card-body d-flex flex-column justify-content-between">
                                <h5 class="card-title mb-1">{{ $drink->name }}</h5>
                                <span class="badge bg-light text-muted mb-2">{{ $drink->category }}</span>
                                <p class="card-text small mb-2">
                                    @foreach ($drink->ingredients as $ing)
                                        {{ $ing->name }} — {{ $ing->pivot->amount }}<br>
                                    @endforeach
                                </p>
                                <p class="fw-bold text-success">Цена: {{ $drink->price }} ₽</p>
                                <a href="{{ route('drink', $drink->id) }}" class="btn btn-outline-secondary btn-sm mt-2">Конструктор</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Нет напитков в этой категории.</p>
                @endforelse
            </div>

            {{-- Мобильный: лента карточек --}}
            <div class="d-flex flex-column gap-4 d-md-none">
                @forelse ($filteredDrinks as $drink)
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="{{ asset('images/' . $drink->image_url) }}"
                                     class="img-fluid h-100 object-fit-cover"
                                     alt="{{ $drink->name }}">
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <h5 class="card-title mb-1">{{ $drink->name }}</h5>
                                    <span class="badge bg-light text-muted mb-2">{{ $drink->category }}</span>
                                    <p class="card-text small mb-2">
                                        @foreach ($drink->ingredients as $ing)
                                            {{ $ing->name }} — {{ $ing->pivot->amount }}<br>
                                        @endforeach
                                    </p>
                                    <p class="fw-bold">Цена: {{ $drink->price }} ₽</p>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('drink', $drink->id) }}" class="btn btn-outline-secondary btn-sm">Конструктор</a>
                                        <form action="{{ route('order.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="drink_id" value="{{ $drink->id }}">
                                            <button type="submit" class="btn btn-outline-dark btn-sm">➕</button>
                                        </form>
                                    </div>
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

@push('styles')
    <style>
        .overlay-form {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card:hover .overlay-form {
            display: flex !important;
            opacity: 1;
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(3px);
        }

        .overlay-form button {
            font-size: 1rem;
            font-weight: 600;
        }
        .list-group-item.active {
            background-color: #e9ecef !important; /* мягкий серый */
            color: #212529 !important;
            border-color: #dee2e6 !important;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush
