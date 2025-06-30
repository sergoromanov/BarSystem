@extends('layouts.app')

@section('title', 'Управление ингредиентами')

@section('content')
    <h2 class="mb-4">Список ингредиентов</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3 text-end">
        <a href="{{ route('admin.ingredients.create') }}" class="btn btn-success">➕ Добавить ингредиент</a>
    </div>

    @if($ingredients->isEmpty())
        <p class="text-muted">Ингредиенты не найдены.</p>
    @else
        <div class="table-responsive shadow-sm rounded-3 overflow-hidden">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Цена (₽)</th>
                    <th>Остаток</th>
                    <th>Ед. изм.</th>
                    <th>Порог (min)</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($ingredients as $ingredient)
                    <tr @if($ingredient->stock <= $ingredient->threshold) class="table-danger" @endif>
                        <td>{{ $ingredient->id }}</td>
                        <td>{{ $ingredient->name }}</td>
                        <td>{{ $ingredient->price }}</td>
                        <td>{{ $ingredient->stock }}</td>
                        <td>{{ $ingredient->unit }}</td>
                        <td>{{ $ingredient->threshold }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.ingredients.edit', $ingredient->id) }}" class="btn btn-sm btn-outline-primary me-2">
                                Редактировать
                            </a>
                            <form action="{{ route('admin.ingredients.destroy', $ingredient->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить ингредиент?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">← Назад в админ-панель</a>
    </div>
@endsection
