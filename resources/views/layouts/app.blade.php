<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Бар')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('catalog') }}">ParaBokalov</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('catalog') ? 'active' : '' }}" href="{{ route('catalog') }}">Каталог</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('order') ? 'active' : '' }}" href="{{ route('order') }}">Заказ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('favorites') ? 'active' : '' }}" href="{{ route('favorites') }}">Мои рецепты</a>
                </li>
            </ul>

            {{-- Бонусы и номер пользователя --}}
            @if (session('user_id') && session('user_phone'))
                @php
                    $user = \App\Models\User::find(session('user_id'));
                @endphp
                @if ($user)
                    <span class="navbar-text text-white me-3">
            {{ $user->phone }} | Бонусы: {{ $user->bonus }}
        </span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-outline-light btn-sm">Выход</button>
                    </form>
                @endif
            @endif
        </div>
    </div>
</nav>

<div class="container">
    @yield('content')
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
