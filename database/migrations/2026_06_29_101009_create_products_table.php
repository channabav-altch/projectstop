<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('product_name');
        $table->string('product_code')->unique();
        $table->string('category')->nullable();
        $table->decimal('cost_price', 10, 2);
        $table->decimal('sale_price', 10, 2);
        $table->integer('qty')->default(0);
        $table->string('unit')->nullable();
        $table->integer('qty_cases')->default(0);
        $table->integer('qty_pieces')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
