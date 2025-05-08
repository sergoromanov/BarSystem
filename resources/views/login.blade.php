<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-card {
            max-width: 400px;
            margin: auto;
            margin-top: 10vh;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            background-color: #fff;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card login-card p-4">
        <h1 class="mb-3 text-center">Вход</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('doLogin') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="phone" class="form-label">Номер телефона</label>
                <input type="text"
                       name="phone"
                       id="phone"
                       class="form-control"
                       required
                       placeholder="+7..."
                       value="{{ old('phone') }}">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-success fw-bold">Войти</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
