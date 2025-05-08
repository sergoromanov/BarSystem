@extends('layouts.app')

@section('title', 'Конструктор — ' . $drink->name)

@section('content')
    <h2 class="mb-4">Конструктор напитка: {{ $drink->name }}</h2>

    <form action="{{ route('drink.customOrder', $drink->id) }}" method="POST">
        @csrf

        <div class="row g-3">
            @foreach ($allIngredients as $ingredient)
                @php
                    $pivot = $drink->ingredients->firstWhere('id', $ingredient->id)?->pivot;
                    $amountValue = preg_replace('/[^\d]/', '', $pivot?->amount ?? '');
                    $selected = $amountValue ? 'selected' : '';
                @endphp

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="ingredient-card border p-3 shadow-sm rounded-4 h-100 text-center {{ $selected ? 'selected' : '' }}"
                         data-id="{{ $ingredient->id }}"
                         data-price="{{ $ingredient->price }}"
                         style="cursor: pointer; transition: all 0.3s ease;">
                        <input type="hidden" name="ingredients[]" value="{{ $ingredient->id }}" {{ $selected ? '' : 'disabled' }}>
                        <div class="fw-bold mb-2">{{ $ingredient->name }}</div>

                        <div class="input-group input-group-sm justify-content-center">
                            <button type="button" class="btn btn-outline-secondary btn-decrease px-3" data-id="{{ $ingredient->id }}">–</button>
                            <input type="text"
                                   class="form-control text-center amount-input"
                                   name="amounts[{{ $ingredient->id }}]"
                                   id="amount_{{ $ingredient->id }}"
                                   value="{{ $amountValue }}"
                                   placeholder="0"
                                   inputmode="numeric"
                                   pattern="\d*"
                                    {{ $selected ? '' : 'readonly' }}>
                            <span class="input-group-text">мл</span>
                            <button type="button" class="btn btn-outline-secondary btn-increase px-3" data-id="{{ $ingredient->id }}">+</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex flex-wrap gap-3">
            <button type="submit" class="btn btn-success btn-lg px-4 d-flex align-items-center gap-2">
                ✓
            </button>
            <button type="submit" name="save_favorite" value="1" class="btn btn-outline-warning btn-lg px-4 d-flex align-items-center gap-2">
                ⭐
            </button>
            <a href="{{ route('catalog') }}" class="btn btn-outline-secondary btn-lg px-4">← </a>
        </div>

        <div class="mt-4">
            <h5>Итоговая цена: <span id="total-price" class="text-success fw-bold">0.00</span> ₽</h5>
        </div>
    </form>

    {{-- CSS --}}
    <style>
        .ingredient-card {
            transition: all 0.3s ease;
        }

        .ingredient-card.selected {
            background-color: #198754 !important;
            color: #fff;
            border-color: #146c43;
        }

        .ingredient-card.selected .btn,
        .ingredient-card.selected .input-group-text,
        .ingredient-card.selected .form-control {
            border-color: #fff;
            color: #fff;
            background-color: transparent;
        }

        .ingredient-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>

    {{-- JS --}}
    <script>
        function calculateTotalPrice() {
            let total = 0;

            document.querySelectorAll('.ingredient-card.selected').forEach(card => {
                const pricePer10ml = parseFloat(card.dataset.price);
                const id = card.dataset.id;
                const amountField = document.getElementById('amount_' + id);
                const amount = parseInt(amountField.value) || 0;

                total += (amount / 10) * pricePer10ml;
            });

            document.getElementById('total-price').innerText = total.toFixed(2);
        }

        document.querySelectorAll('.ingredient-card').forEach(card => {
            card.addEventListener('click', () => {
                const id = card.dataset.id;
                const input = card.querySelector('input[name="ingredients[]"]');
                const amountField = document.getElementById('amount_' + id);

                card.classList.toggle('selected');
                const isSelected = card.classList.contains('selected');
                input.disabled = !isSelected;
                amountField.readOnly = !isSelected;

                if (!isSelected) amountField.value = '';

                calculateTotalPrice();
            });
        });

        document.querySelectorAll('.btn-increase').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const id = btn.dataset.id;
                const input = document.getElementById('amount_' + id);
                let value = parseInt(input.value) || 0;
                if (value < 100) value += 10;
                input.value = value;
                calculateTotalPrice();
            });
        });

        document.querySelectorAll('.btn-decrease').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const id = btn.dataset.id;
                const input = document.getElementById('amount_' + id);
                let value = parseInt(input.value) || 0;
                if (value > 0) value -= 10;
                input.value = value;
                calculateTotalPrice();
            });
        });

        document.addEventListener('DOMContentLoaded', calculateTotalPrice);
    </script>
@endsection
