<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('drink_id')->nullable()->constrained()->onDelete('set null'); // если основано на drink
            $table->string('name'); // название рецепта от пользователя
            $table->json('ingredients'); // массив "Сахар — 10 мл", "Ром — 50 мл"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
