@extends('layouts.app')

@section('title', 'Админ-панель')

@section('content')
    <h2 class="mb-4">Добро пожаловать в админ-панель</h2>

    <p>Здесь будет управление напитками, ингредиентами, заказами и пользователями.</p>

    <a href="{{ route('catalog') }}" class="btn btn-outline-primary">Вернуться в каталог</a>
@endsection
