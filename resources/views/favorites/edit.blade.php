@extends('layouts.app')

@section('title', 'Редактировать рецепт')

@section('content')
    <h2 class="mb-4">Редактирование рецепта: {{ $favorite->name }}</h2>

    <form action="{{ route('favorites.update', $favorite->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Название рецепта</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $favorite->name) }}" required>
        </div>

        <div class="row g-3">
            @php
                $existing = collect($favorite->ingredients)->mapWithKeys(function ($item) {
                    preg_match('/^(.*?)\s+—\s+(.*)$/u', $item, $matches);
                    return count($matches) === 3 ? [$matches[1] => $matches[2]] : [$item => null];
                });
            @endphp

            @foreach ($ingredients as $ingredient)
                @php
                    $checked = $existing->has($ingredient->name);
                    $amountValue = $existing[$ingredient->name] ?? '';
                @endphp
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="border rounded p-3 shadow-sm h-100">
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   value="{{ $ingredient->id }}"
                                   id="ingredient_{{ $ingredient->id }}"
                                   name="ingredients[]"
                                {{ $checked ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="ingredient_{{ $ingredient->id }}">
                                {{ $ingredient->name }}
                            </label>
                        </div>

                        <div class="mt-2">
                            <label for="amount_{{ $ingredient->id }}" class="form-label small mb-1">Количество:</label>
                            <input type="text"
                                   class="form-control form-control-sm"
                                   name="amounts[{{ $ingredient->id }}]"
                                   id="amount_{{ $ingredient->id }}"
                                   value="{{ $amountValue }}"
                                   placeholder="например, 50">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            <a href="{{ route('favorites') }}" class="btn btn-outline-secondary ms-2">Назад</a>
        </div>
    </form>
@endsection
