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
    Schema::create('bundle_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_bundle_id')->constrained()->cascadeOnDelete();
        $table->foreignId('product_id')->constrained()->cascadeOnDelete();
        $table->integer('quantity'); // ចំនួនប៉ុន្មានឯកតាក្នុង ១ ឈុត
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bundle_items');
    }
};
