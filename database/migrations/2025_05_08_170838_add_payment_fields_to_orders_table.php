<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->after('is_paid'); // pending / paid / failed
            $table->string('payment_id')->nullable()->after('payment_status');      // ID от платёжки
            $table->timestamp('paid_at')->nullable()->after('payment_id');          // время оплаты
        });
    }

    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_id', 'paid_at']);
        });
    }
};

