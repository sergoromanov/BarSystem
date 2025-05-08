<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('drinks', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->default(0)->after('image_url');
        });
    }

    public function down(): void {
        Schema::table('drinks', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
