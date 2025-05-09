<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5" style="max-width: 420px;">
    <h3 class="mb-4">Вход по номеру телефона</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('auth.sendCode') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="phone" class="form-label">Номер телефона</label>
            <input type="text"
                   name="phone"
                   id="phone"
                   class="form-control"
                   required
                   placeholder="+7..."
                   value="{{ old('phone') }}"
                   oninput="checkAdmin(this.value)">
        </div>

        {{-- Скрытое поле для админ-пароля --}}
        <div class="mb-3 d-none" id="admin-pass-wrapper">
            <label for="admin_password" class="form-label">Пароль администратора</label>
            <input type="password" name="admin_password" id="admin_password" class="form-control" placeholder="••••••">
        </div>

        <button type="submit" class="btn btn-primary w-100" id="submit-button">Получить код</button>
    </form>
</div>

<script>
    function checkAdmin(value) {
        const field = document.getElementById('admin-pass-wrapper');
        const button = document.getElementById('submit-button');

        if (value.trim() === '0000') {
            field.classList.remove('d-none');
            button.textContent = 'Войти как администратор';
        } else {
            field.classList.add('d-none');
            button.textContent = 'Получить код';
        }
    }
</script>

</body>
</html>
