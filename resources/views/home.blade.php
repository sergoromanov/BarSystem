@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <h2>Добро пожаловать!</h2>
    <p>Вы вошли как: <strong>{{ $user->phone }}</strong></p>
    <p>Ваши бонусы: <span class="badge bg-success">{{ $user->bonus }}</span></p>

    <a href="{{ route('catalog') }}" class="btn btn-outline-primary">Перейти в каталог напитков</a>
@endsection
