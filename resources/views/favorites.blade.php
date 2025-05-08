@extends('layouts.app')

@section('title', 'Мои рецепты')

@section('content')
    <h2 class="mb-4">Мои рецепты</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse ($favorites as $favorite)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">{{ $favorite->name }}</h5>
                <p class="card-text mb-2">
                    <strong>Состав:</strong><br>
                    @foreach ($favorite->ingredients as $ingredient)
                        — {{ $ingredient }}<br>
                    @endforeach
                </p>
                <div class="d-flex gap-2">
                    <a href="{{ route('favorites.edit', $favorite->id) }}" class="btn btn-outline-primary btn-sm">Редактировать</a>

                    <form action="{{ route('favorites.destroy', $favorite->id) }}" method="POST" onsubmit="return confirm('Удалить рецепт?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">Удалить</button>
                    </form>
                </div>

            </div>
        </div>
    @empty
        <p>У вас пока нет сохранённых рецептов.</p>
    @endforelse
@endsection
