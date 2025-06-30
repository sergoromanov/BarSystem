<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Бар')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('catalog') }}">ParaBokalov</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('catalog') ? 'active' : '' }}" href="{{ route('catalog') }}">Каталог</a>
                </li>

                @php
                    $unpaidCount = 0;
                    $user = null;
                    if (session('user_id')) {
                        $user = \App\Models\User::find(session('user_id'));
                        $unpaidCount = $user?->orders()->where('payment_status', '!=', 'paid')->count() ?? 0;
                    }
                @endphp

                <li class="nav-item position-relative">
                    <a class="nav-link {{ request()->routeIs('order') ? 'active' : '' }}" href="{{ route('order') }}">
                        Заказ
                        @if ($unpaidCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unpaidCount }}
                            </span>
                        @endif
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('favorites') ? 'active' : '' }}" href="{{ route('favorites') }}">Мои рецепты</a>
                </li>

                {{-- Панель бармена (если barista = true) --}}
                @if ($user && $user->is_barista)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barman.orders') ? 'active' : '' }}" href="{{ route('barman.orders') }}">Панель бармена</a>
                    </li>
                @endif

                {{-- Панель администратора --}}
                @if ($user && $user->is_admin)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Админка</a>
                    </li>
                @endif
            </ul>

            {{-- Бонусы и номер пользователя --}}
            @if ($user)
                <div class="d-flex align-items-center gap-3 text-white">
                    <div class="text-end small">
                        <div><strong>{{ $user->phone }}</strong></div>
                        <div>Бонусы: <span class="badge bg-success">{{ $user->bonus }}</span></div>
                    </div>

                    <form action="{{ route('logout') }}" method="GET">
                        <button class="btn btn-sm btn-outline-light">Выход</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</nav>

<div class="container py-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
