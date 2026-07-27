<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // 🟢 ឆែកការពារកុំឱ្យជាន់គ្នា មុននឹងបន្ថែម carton_size
            if (!Schema::hasColumn('products', 'carton_size')) {
                // បន្ថែមកូឡោន carton_size ជាប្រភេទលេខ (integer) និងកំណត់តម្លៃដើមស្មើ ១
                $table->integer('carton_size')->default(1);
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // លុបវាវិញនៅពេលយើងចង់ថយក្រោយ (Rollback) តែត្រូវឆែកសិនការពារ Error
            if (Schema::hasColumn('products', 'carton_size')) {
                $table->dropColumn('carton_size');
            }
        });
    }
};
