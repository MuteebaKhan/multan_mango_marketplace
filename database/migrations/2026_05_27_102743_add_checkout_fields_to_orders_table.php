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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->after('customer_id'); // Real product price
            $table->decimal('shipping_fee', 8, 2)->default(0)->after('subtotal'); // Delivery charges
            $table->decimal('platform_fee', 8, 2)->default(0)->after('shipping_fee'); // Service charges (Rs. 300)
            $table->decimal('discount_amount', 8, 2)->default(0)->after('platform_fee'); // Voucher discount
            
            // Coupon Link (Foreign Key)
            $table->foreignId('coupon_id')->nullable()->after('customer_id')->constrained('coupons')->nullOnDelete();

            // Google Map Coordinates for Rider
            $table->decimal('latitude', 10, 8)->nullable()->after('shipping_address');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn([
            'subtotal', 
            'shipping_fee', 
            'platform_fee', 
            'discount_amount', 
            'coupon_id', 
            'latitude', 
            'longitude'
        ]);
        });
    }
};
