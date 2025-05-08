<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('drink_ingredient', function (Blueprint $table) {
            $table->string('amount')->nullable()->after('ingredient_id'); // например: "50 мл", "15 г"
        });
    }

    public function down(): void
    {

    }
};
