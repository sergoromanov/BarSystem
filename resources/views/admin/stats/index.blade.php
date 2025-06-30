@extends('layouts.app')

@section('title', 'Статистика')

@section('content')
    <h2 class="mb-4">Статистика заказов</h2>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card text-bg-light shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Всего заказов</h5>
                    <p class="display-6">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-light shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">За последнюю неделю</h5>
                    <p class="display-6">{{ $ordersLast7Days }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-light shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Общая выручка</h5>
                    <p class="display-6 text-success">{{ $totalRevenue }} ₽</p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-5 mb-3">Топ-5 популярных напитков</h4>
    <ul class="list-group mb-5">
        @forelse ($topDrinks as $drink)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $drink->drink_name }}
                <span class="badge bg-primary rounded-pill">{{ $drink->count }}</span>
            </li>
        @empty
            <li class="list-group-item text-muted">Нет данных</li>
        @endforelse
    </ul>

    <h4 class="mb-3">График заказов за 7 дней</h4>
    <canvas id="ordersChart" height="100"></canvas>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('ordersChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($ordersByDay->pluck('date')) !!},
                datasets: [{
                    label: 'Заказы',
                    data: {!! json_encode($ordersByDay->pluck('count')) !!},
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.2,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endpush
