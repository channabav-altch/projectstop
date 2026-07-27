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
    Schema::table('products', function (Blueprint $table) {
        $table->string('image')->nullable(); // បន្ថែមប្រអប់ image អាចទុកទទេបាន
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('image');
    });
}
};
