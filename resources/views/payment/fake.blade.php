@extends('layouts.app')

@section('title', 'Оплата заказа')

@section('content')
    <div class="container-sm" style="max-width: 500px;">
        <h2 class="mb-4 text-center">Оплата заказа №{{ $order->id }}</h2>

        @php
            $rawTotal = $order->items->sum('price');
            $bonusesUsed = $order->bonuses_used ?? 0;
            $finalTotal = $rawTotal - $bonusesUsed;
        @endphp

        <div id="payment-box" class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-end mb-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa" height="24" class="me-2">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="MasterCard" height="24">
                </div>

                <p><strong>Сумма заказа:</strong> {{ $rawTotal }} ₽</p>
                @if($bonusesUsed > 0)
                    <p class="text-primary mb-1">Списано бонусов: –{{ $bonusesUsed }} ₽</p>
                @endif
                <p class="fw-bold fs-5">К оплате: <span class="text-success">{{ $finalTotal }} ₽</span></p>

                <hr>

                <form id="fake-payment-form">
                    <div class="mb-3">
                        <label for="card_number" class="form-label">Номер карты</label>
                        <input type="text" class="form-control" id="card_number" placeholder="0000 0000 0000 0000">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="expiry" class="form-label">Срок действия</label>
                            <input type="text" class="form-control" id="expiry" placeholder="MM/YY">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="password" class="form-control" id="cvv" placeholder="123">
                        </div>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" id="pay-button" class="btn btn-success btn-lg">
                            Оплатить {{ $finalTotal }} ₽
                        </button>
                    </div>

                    <div class="text-center text-muted mt-3" style="font-size: 0.9rem;">
                        Это тестовая платёжная форма. Никакие реальные деньги не списываются.
                    </div>
                </form>
            </div>
        </div>

        <div id="payment-success" class="alert alert-success text-center d-none">
            <h4 class="mb-3">✅ Оплата прошла успешно!</h4>
            <p>Спасибо за ваш заказ №{{ $order->id }}.</p>
            <a href="{{ route('order') }}" class="btn btn-outline-primary mt-2">Перейти к заказам</a>
        </div>
    </div>

    <script>
        document.getElementById('fake-payment-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const payButton = document.getElementById('pay-button');
            payButton.disabled = true;
            payButton.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Обработка...
            `;

            setTimeout(() => {
                document.getElementById('payment-box').classList.add('d-none');
                document.getElementById('payment-success').classList.remove('d-none');

                // Автоматически можно отправить POST-запрос на Laravel-роут:
                fetch("{{ route('payment.fake.confirm', $order->id) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({})
                });
            }, 2000);
        });
    </script>
@endsection
