<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Подтверждение входа</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5" style="max-width: 420px;">
    <h3 class="mb-4">Введите код</h3>
    @if (app()->environment('local') && isset($code))
        <div class="alert alert-info">
            <strong>Тестовый код:</strong> {{ $code }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('auth.checkCode') }}" method="POST">
        @csrf
        <input type="hidden" name="phone" value="{{ session('phone') }}">
        <div class="mb-3">
            <label for="code" class="form-label">Код из SMS</label>
            <input type="text" name="code" id="code" class="form-control" required placeholder="1234" maxlength="4">
        </div>
        <button type="submit" class="btn btn-success w-100">Войти</button>
    </form>
</div>

</body>
</html>
