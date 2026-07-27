<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            // 🟢 ឆែកមើលបើគ្មាន expense_date ទេ ទើបវាបង្កើតថ្មី
            if (!Schema::hasColumn('expenses', 'expense_date')) {
                // បន្ថែម Column សម្រាប់ផ្ទុកថ្ងៃខែនៃការចំណាយ
                $table->date('expense_date')->nullable()->after('amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            // លុប Column នេះវិញប្រសិនបើយើងថយក្រោយ (Rollback)
            if (Schema::hasColumn('expenses', 'expense_date')) {
                $table->dropColumn('expense_date');
            }
        });
    }
};
