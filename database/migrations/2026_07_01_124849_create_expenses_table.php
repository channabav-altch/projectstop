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
        // 🟢 បន្ថែមការឆែកត្រង់នេះ ដើម្បីសុវត្ថិភាព មិនឱ្យបាត់ទិន្នន័យ និងមិនលោត Error
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->string('description'); // បរិយាយ
                $table->decimal('amount', 10, 2); // ចំនួនទឹកប្រាក់
                $table->string('requester_name')->default('TSM'); // អ្នកស្នើ
                $table->boolean('is_global')->default(false); // ចំណាយរួម
                $table->string('specific_admin')->nullable(); // អ្នកគ្រប់គ្រងជាក់លាក់
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
