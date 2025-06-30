<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->unsignedInteger('stock')->default(0)->after('name');      // остаток в мл
            $table->unsignedInteger('threshold')->default(100)->after('stock'); // минимальный остаток
        });
    }

    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropColumn(['stock', 'threshold']);
        });
    }
};
