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
            // 🟢 ឆែកការពារកុំឱ្យលោត Error បើមានប្រឡោះនេះរួចហើយ 🟢
            if (!Schema::hasColumn('orders', 'customer_type')) {
                $table->string('customer_type')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'customer_type')) {
                $table->dropColumn('customer_type');
            }
        });
    }
};
