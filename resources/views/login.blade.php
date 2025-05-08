<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4">Вход по номеру телефона</h2>

    <form action="{{ route('doLogin') }}" method="POST" class="w-50">
        @csrf
        <div class="mb-3">
            <label for="phone" class="form-label">Номер телефона:</label>
            <input type="text" name="phone" id="phone" class="form-control" required placeholder="+7...">
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>
</div>

</body>
</html>
