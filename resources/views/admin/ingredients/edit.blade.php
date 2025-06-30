@extends('layouts.app')

@section('title', 'Редактировать ингредиент')

@section('content')
    <h2 class="mb-4">Редактировать ингредиент</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Ошибки:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.ingredients.update', $ingredient->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" name="name" id="name" class="form-control"
                   value="{{ old('name', $ingredient->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Цена (за 10 мл/г)</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control"
                   value="{{ old('price', $ingredient->price) }}" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Остаток</label>
            <input type="number" name="stock" id="stock" class="form-control"
                   value="{{ old('stock', $ingredient->stock) }}" required>
        </div>

        <div class="mb-3">
            <label for="threshold" class="form-label">Минимальный остаток (threshold)</label>
            <input type="number" name="threshold" id="threshold" class="form-control"
                   value="{{ old('threshold', $ingredient->threshold) }}" required>
        </div>

        <div class="mb-3">
            <label for="unit" class="form-label">Единица измерения</label>
            <select name="unit" id="unit" class="form-select" required>
                <option value="мл" {{ old('unit', $ingredient->unit) === 'мл' ? 'selected' : '' }}>мл</option>
                <option value="г" {{ old('unit', $ingredient->unit) === 'г' ? 'selected' : '' }}>г</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        <a href="{{ route('admin.ingredients.index') }}" class="btn btn-outline-secondary ms-2">Отмена</a>
    </form>
@endsection
