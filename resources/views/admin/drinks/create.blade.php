@extends('layouts.app')

@section('title', 'Добавить напиток')

@section('content')
    <h2 class="mb-4">Добавление напитка</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ошибки:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.drinks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Название напитка</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Категория</label>
            <input type="text" name="category" class="form-control" value="{{ old('category') }}">
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Цена (₽)</label>
            <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price') }}" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Изображение (JPG/PNG)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <div class="mb-4">
            <label class="form-label">Ингредиенты</label>
            <div class="row g-3">
                @foreach ($ingredients as $ingredient)
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-3 shadow-sm h-100">
                            <div class="form-check mb-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="ingredients[]"
                                       value="{{ $ingredient->id }}"
                                       id="ingredient_{{ $ingredient->id }}"
                                    {{ is_array(old('ingredients')) && in_array($ingredient->id, old('ingredients', [])) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="ingredient_{{ $ingredient->id }}">
                                    {{ $ingredient->name }}
                                </label>
                            </div>
                            <div>
                                <label class="form-label small mb-1">Количество (мл / г):</label>
                                <input type="text"
                                       class="form-control form-control-sm"
                                       name="amounts[{{ $ingredient->id }}]"
                                       value="{{ old('amounts.' . $ingredient->id) }}"
                                       placeholder="например, 50">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{ route('admin.drinks.index') }}" class="btn btn-outline-secondary ms-2">Отмена</a>
    </form>
@endsection
