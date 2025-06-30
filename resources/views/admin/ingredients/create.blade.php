@extends('layouts.app')

@section('title', 'Добавить ингредиент')

@section('content')
    <h2 class="mb-4">Добавить новый ингредиент</h2>

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

    <form method="POST" action="{{ route('admin.ingredients.store') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" name="name" id="name" class="form-control"
                   value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Цена (за 10 мл/г)</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control"
                   value="{{ old('price') }}" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Остаток</label>
            <input type="number" name="stock" id="stock" class="form-control"
                   value="{{ old('stock') }}" required>
        </div>

        <div class="mb-3">
            <label for="threshold" class="form-label">Минимальный остаток (threshold)</label>
            <input type="number" name="threshold" id="threshold" class="form-control"
                   value="{{ old('threshold') }}" required>
        </div>

        <div class="mb-3">
            <label for="unit" class="form-label">Единица измерения</label>
            <select name="unit" id="unit" class="form-select" required>
                <option value="">Выберите</option>
                <option value="мл" {{ old('unit') === 'мл' ? 'selected' : '' }}>мл</option>
                <option value="г" {{ old('unit') === 'г' ? 'selected' : '' }}>г</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Создать ингредиент</button>
        <a href="{{ route('admin.ingredients.index') }}" class="btn btn-outline-secondary ms-2">Отмена</a>
    </form>
@endsection
