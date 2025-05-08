@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="card shadow-sm border-0 rounded-4 p-4 text-center" style="background-color: #f9f9f9;">
        <h2 class="mb-3">Добро пожаловать в ParaBokalov</h2>

        <p class="lead">Вы вошли как: <strong>{{ $user->phone }}</strong></p>
        <p>Ваши бонусы: <span class="badge bg-success fs-6">{{ $user->bonus }}</span></p>

        <hr class="my-4">

        <div class="d-grid gap-3 d-sm-flex justify-content-center">
            <a href="{{ route('catalog') }}" class="btn btn-outline-secondary px-4 py-2 fw-bold">Перейти в каталог</a>
            <a href="{{ route('order') }}" class="btn btn-outline-secondary px-4 py-2 fw-bold" >Мои заказы</a>
            <a href="{{ route('favorites') }}" class="btn btn-outline-secondary px-4 py-2 fw-bold">Избранные рецепты</a>
        </div>
    </div>
@endsection
