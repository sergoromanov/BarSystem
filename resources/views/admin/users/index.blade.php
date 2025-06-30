@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
    <h2 class="mb-4">Список пользователей</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($users->isEmpty())
        <p class="text-muted">Нет зарегистрированных пользователей.</p>
    @else
        <div class="table-responsive shadow-sm rounded-3 overflow-hidden">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Телефон</th>
                    <th>Бонусы</th>
                    <th>Заказы</th>
                    <th>Админ</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->bonus }}</td>
                        <td>{{ $user->orders_count }}</td>
                        <td>
                            @if($user->is_admin)
                                <span class="badge bg-success">Да</span>
                            @else
                                <span class="badge bg-secondary">Нет</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">Редактировать</a>
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
