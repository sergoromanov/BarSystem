@extends('layouts.app')

@section('title', 'Конструктор — ' . $drink->name)

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <h2 class="mb-4">Конструктор напитка: {{ $drink->name }}</h2>

    <form action="{{ route('drink.customOrder', $drink->id) }}" method="POST">
        @csrf

        <div class="row">
            {{-- Левая часть: ингредиенты --}}
            <div class="col-12 col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Выберите ингредиенты:</h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-selection">
                        Очистить выбор
                    </button>
                </div>

                <div class="row g-3">
                    @forelse ($allIngredients as $ingredient)
                        @php
                            $oldIngredients = old('ingredients', []);
                            $oldAmounts = old('amounts.' . $ingredient->id);
                            $pivot = $drink->ingredients->firstWhere('id', $ingredient->id)?->pivot;
                            $amountValue = $oldAmounts ?? preg_replace('/[^\d]/', '', $pivot?->amount ?? '');
                            $selected = in_array($ingredient->id, $oldIngredients) || $amountValue ? 'selected' : '';
                        @endphp

                        <div class="col-12 col-md-6 col-xl-4">
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
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                Ингредиенты для этого напитка временно недоступны.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Правая часть: визуализация напитка --}}
            <div class="col-12 col-lg-3 mt-4 mt-lg-0">
                <h5 class="mb-2">Визуализация</h5>
                <div id="glass" class="glass-container shadow-sm" title="Ваш напиток"></div>
                <div class="mt-2 text-center small">
                    <strong>Объём:</strong> <span id="total-volume">0</span> мл
                </div>
            </div>
        </div>

        @if($allIngredients->count())
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Заказать с выбранным составом</button>
                <button type="submit" name="save_favorite" value="1" class="btn btn-outline-warning ms-2">
                    Сохранить в избранное
                </button>
                <a href="{{ route('catalog') }}" class="btn btn-outline-secondary ms-2">Назад в каталог</a>
            </div>
        @else
            <div class="mt-4">
                <a href="{{ route('catalog') }}" class="btn btn-outline-secondary">← Назад в каталог</a>
            </div>
        @endif
    </form>

    @push('styles')
        <style>
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
            .glass-container {
                width: 100%;
                max-width: 140px;
                height: 300px;
                margin: 0 auto;
                border-radius: 24px 24px 8px 8px;
                background: linear-gradient(to bottom, #f9f9f9, #f1f1f1);
                box-shadow: inset 0 0 6px rgba(0,0,0,0.1), 0 6px 10px rgba(0,0,0,0.08);
                display: flex;
                flex-direction: column-reverse;
                overflow: hidden;
                border: 2px solid #e0e0e0;
                position: relative;
            }
            .ingredient-layer {
                width: 100%;
                text-align: center;
                font-size: 0.7rem;
                color: #fff;
                line-height: 1.3;
                overflow: hidden;
                border-top: 1px solid rgba(255,255,255,0.2);
                animation: pour 0.4s ease-in-out;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }
            .ingredient-layer::after {
                content: attr(data-tooltip);
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                font-size: 0.6rem;
                color: #fff;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .ingredient-layer:hover::after {
                opacity: 1;
                background-color: rgba(0,0,0,0.4);
            }
            @keyframes pour {
                from {
                    transform: scaleY(0);
                    opacity: 0.1;
                }
                to {
                    transform: scaleY(1);
                    opacity: 1;
                }
            }
            #total-volume.text-danger {
                color: #dc3545;
                font-weight: bold;
            }
        </style>
    @endpush

    <script>
        function updateSelectedList() {
            const selected = document.querySelectorAll('.ingredient-card.selected');
            let total = 0;
            const glass = document.getElementById('glass');
            glass.innerHTML = '';

            selected.forEach(card => {
                const id = card.dataset.id;
                const name = card.querySelector('.fw-bold').textContent;
                const amount = parseInt(document.getElementById('amount_' + id).value) || 0;
                total += amount;

                const heightPercent = Math.min((amount / 300) * 100, 100);
                const colors = {
                    'Ром': '#a36c4f',
                    'Мята': '#5cb85c',
                    'Сахар': '#e0c07d',
                    'Лайм': '#b5e07d',
                    'Содовая': '#d0e6f5',
                    'Ананас': '#f5d76e',
                    'Кокосовое молоко': '#fff0e0',
                    'Кофе': '#4b3621',
                    'Виноград': '#a84acb',
                    'Ячмень': '#c3b091',
                    'Хмель': '#98c379'
                };
                const bg = colors[name] || '#888';

                const layer = document.createElement('div');
                layer.className = 'ingredient-layer';
                layer.style.backgroundColor = bg;
                layer.style.height = `${heightPercent}%`;
                layer.setAttribute('data-tooltip', `${name} — ${amount} мл`);
                glass.appendChild(layer);
            });

            document.getElementById('total-volume').textContent = total;
            document.getElementById('total-volume').classList.toggle('text-danger', total > 300);
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
                updateSelectedList();
            });
        });

        document.querySelectorAll('.amount-input').forEach(input => {
            input.addEventListener('input', updateSelectedList);
        });

        document.querySelectorAll('.btn-increase').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const id = btn.dataset.id;
                const input = document.getElementById('amount_' + id);
                let value = parseInt(input.value) || 0;
                if (value < 300) value += 10;
                input.value = value;
                updateSelectedList();
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
                updateSelectedList();
            });
        });

        document.getElementById('clear-selection').addEventListener('click', () => {
            document.querySelectorAll('.ingredient-card.selected').forEach(card => {
                card.classList.remove('selected');
                const id = card.dataset.id;
                card.querySelector('input[name="ingredients[]"]').disabled = true;
                const amountInput = document.getElementById('amount_' + id);
                amountInput.readOnly = true;
                amountInput.value = '';
            });
            updateSelectedList();
        });

        updateSelectedList();
    </script>
@endsection
