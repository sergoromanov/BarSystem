@extends('layouts.app')

@section('title', 'Редактировать пользователя')

@section('content')
    <h2 class="mb-4">Редактирование пользователя: {{ $user->phone }}</h2>

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

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="bonus" class="form-label">Бонусы</label>
            <input type="number" name="bonus" class="form-control" value="{{ old('bonus', $user->bonus) }}" min="0" required>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="is_admin" id="is_admin" {{ $user->is_admin ? 'checked' : '' }}>
            <label class="form-check-label" for="is_admin">
                Администратор
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary ms-2">Отмена</a>
    </form>
@endsection
