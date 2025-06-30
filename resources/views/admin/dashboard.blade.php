@extends('layouts.app')

@section('title', 'Админ-панель')

@section('content')
    <h2 class="mb-4">Админ-панель</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <p class="mb-4">Вы вошли как администратор. Выберите раздел для управления:</p>

    <div class="list-group">
        <a href="{{ route('admin.drinks.index') }}" class="list-group-item list-group-item-action">
            Управление напитками
        </a>
        <a href="{{ route('admin.ingredients.index') }}" class="list-group-item list-group-item-action">
            Ингредиенты
        </a>
        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">
            Пользователи
        </a>
        <a href="{{ route('admin.stats') }}" class="btn btn-outline-dark mt-3">Cтатистика</a>

    </div>

    <div class="mt-4">
        <a href="{{ route('catalog') }}" class="btn btn-outline-secondary">На сайт</a>
    </div>
@endsection
