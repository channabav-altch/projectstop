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
    Schema::create('product_bundles', function (Blueprint $table) {
        $table->id();
        $table->string('sku')->unique();
        $table->string('name'); // ឧ. ឈុតចូលឆ្នាំ (ស្រា២ + កូកា១)
        $table->decimal('bundle_price', 12, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_bundles');
    }
};
