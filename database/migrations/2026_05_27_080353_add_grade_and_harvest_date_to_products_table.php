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
        Schema::table('products', function (Blueprint $table) {
            $table->string('grade')->default('premium')->after('category_id'); 
            $table->date('harvest_date')->nullable()->after('stock_quantity_kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Schema $table) {
            $table->dropColumn(['grade', 'harvest_date']);
        });
    }
};
