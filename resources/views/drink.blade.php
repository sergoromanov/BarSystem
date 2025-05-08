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
                    <div class="ingredient-card {{ $selected }} border rounded p-3 shadow-sm h-100 text-center"
                         data-id="{{ $ingredient->id }}"
                         style="cursor: pointer; user-select: none;">
                        <input type="hidden" name="ingredients[]" value="{{ $ingredient->id }}" {{ $selected ? '' : 'disabled' }}>
                        <div class="fw-bold mb-2">{{ $ingredient->name }}</div>

                        <div class="input-group input-group-sm justify-content-center">
                            <button type="button" class="btn btn-outline-secondary btn-decrease" data-id="{{ $ingredient->id }}">–</button>
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
                            <button type="button" class="btn btn-outline-secondary btn-increase" data-id="{{ $ingredient->id }}">+</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Заказать с выбранным составом</button>
            <button type="submit" name="save_favorite" value="1" class="btn btn-outline-warning ms-2">
                Сохранить в избранное
            </button>
            <a href="{{ route('catalog') }}" class="btn btn-outline-secondary ms-2">Назад в каталог</a>
        </div>
    </form>

    {{-- CSS --}}
    <style>
        .ingredient-card.selected {
            background-color: #198754 !important;
            color: #fff;
            border-color: #146c43;
        }
        .ingredient-card.selected .btn {
            border-color: #fff;
            color: #fff;
        }
        .ingredient-card.selected .input-group-text {
            background-color: transparent;
            border-color: #fff;
            color: #fff;
        }
        .ingredient-card.selected .form-control {
            background-color: transparent;
            border-color: #fff;
            color: #fff;
        }
    </style>

    {{-- JS --}}
    <script>
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
            });
        });
    </script>
@endsection
