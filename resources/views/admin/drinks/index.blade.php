@extends('layouts.app')

@section('title', 'Управление напитками')

@section('content')
    <h2 class="mb-4">Список напитков</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3 text-end">
        <a href="{{ route('admin.drinks.create') }}" class="btn btn-success">➕ Добавить напиток</a>
    </div>

    @if($drinks->isEmpty())
        <p class="text-muted">Нет добавленных напитков.</p>
    @else
        <div class="table-responsive shadow-sm rounded-3 overflow-hidden">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Цена</th>
                    <th>Ингредиенты</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($drinks as $drink)
                    <tr>
                        <td>{{ $drink->id }}</td>
                        <td>{{ $drink->name }}</td>
                        <td>{{ $drink->category }}</td>
                        <td>{{ $drink->price }} ₽</td>
                        <td>
                            <ul class="mb-0">
                                @foreach($drink->ingredients as $ingredient)
                                    <li>{{ $ingredient->name }} — {{ $ingredient->pivot->amount }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.drinks.edit', $drink->id) }}" class="btn btn-sm btn-outline-primary me-2">Редактировать</a>

                            <form action="{{ route('admin.drinks.destroy', $drink->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить напиток?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
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
