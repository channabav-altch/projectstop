<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 🟢 ឆែកមើលបើគ្មាន user_type ទេ ទើបបង្កើតថ្មី
            if (!Schema::hasColumn('users', 'user_type')) {
                $table->string('user_type')->nullable()->after('email');
            }

            // 🟢 ឆែកមើលបើគ្មាន sales_count ទេ ទើបបង្កើតថ្មី
            if (!Schema::hasColumn('users', 'sales_count')) {
                $table->integer('sales_count')->default(0)->after('user_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'user_type')) {
                $table->dropColumn('user_type');
            }
            if (Schema::hasColumn('users', 'sales_count')) {
                $table->dropColumn('sales_count');
            }
        });
    }
};
