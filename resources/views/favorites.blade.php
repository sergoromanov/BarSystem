@extends('layouts.app')

@section('title', 'Мои рецепты')

@section('content')
    <h2 class="mb-4">Мои рецепты</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse ($favorites as $favorite)
        <div class="card shadow-sm border-0 rounded-4 mb-4" style="background-color: #f9f9f9;">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start">
                <div>
                    <h5 class="card-title mb-2">{{ $favorite->name }}</h5>
                    <p class="mb-1 text-muted small">Состав:</p>
                    <ul class="mb-2 ps-3">
                        @foreach ($favorite->ingredients as $ingredient)
                            <li>{{ $ingredient }}</li>
                        @endforeach
                    </ul>
                    @if ($favorite->drink)
                        <p class="text-muted small">Основан на: <strong>{{ $favorite->drink->name }}</strong></p>
                    @endif
                </div>

                <div class="mt-3 mt-md-0 d-flex flex-column align-items-end gap-2">
                    <a href="{{ route('favorites.edit', $favorite->id) }}"
                       class="btn btn-outline-primary btn-sm">Редактировать</a>

                    <form action="{{ route('favorites.delete', $favorite->id) }}" method="POST"
                          onsubmit="return confirm('Удалить этот рецепт?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">Удалить</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">У вас пока нет сохранённых рецептов.</p>
    @endforelse
@endsection
