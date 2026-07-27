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
        // 🟢 ឆែកមើល៖ បើគ្មានតារាង suppliers ទេ ទើបវាបង្កើតថ្មី 🟢
        if (!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // ឈ្មោះ
                $table->string('phone'); // លេខទូរស័ព្ទ
                $table->string('address')->nullable(); // អាសយដ្ឋាន (អាចទុកទទេបាន)
                $table->text('note')->nullable(); // ចំណាំ (អាចទុកទទេបាន)
                $table->string('status')->default('active'); // ស្ថានភាព (Defualt លោតសកម្ម)
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
