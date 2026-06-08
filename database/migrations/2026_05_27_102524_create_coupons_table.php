<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., 'MANGO10', 'MULTAN500'
            $table->string('type'); // 'percentage' ya 'fixed'
            $table->decimal('value', 8, 2); // discount ki value (e.g., 10% ya 200 Rs)
            $table->decimal('min_order_amount', 8, 2)->default(0); // kam se kam kitne ka order ho
            $table->date('expiry_date'); // kab expire hoga
            $table->boolean('is_active')->default(true); // coupon chal raha hai ya band hai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
