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
    Schema::table('customers', function (Blueprint $table) {
        // 🔴 ប្រសិនបើមានបន្ទាត់នេះ សូមលុបវាចោល ព្រោះ name មានក្នុង DB រួចហើយ 🔴
        // $table->string('name');
        if (!Schema::hasColumn('customers', 'address')) {
                $table->string('address', 255)->nullable();
            }

        // ទុកតែ Column ថ្មីផ្សេងទៀតដែលមិនទាន់មាន ឧទាហរណ៍៖
        // $table->string('address')->nullable();
        $table->string('tax_no')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
