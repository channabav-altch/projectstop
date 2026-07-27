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
            // 🟢 ឆែក និងបន្ថែម Columns ម្ដងមួយៗ ដើម្បីកុំឱ្យជាន់គ្នា 🟢
            if (!Schema::hasColumn('orders', 'customer_type')) {
                $table->string('customer_type')->nullable();    // ប្រភេទអតិថិជន
            }

            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable();    // ឈ្មោះអតិថិជន
            }

            if (!Schema::hasColumn('orders', 'phone')) {
                $table->string('phone')->nullable();            // លេខទូរស័ព្ទ
            }

            if (!Schema::hasColumn('orders', 'province')) {
                $table->string('province')->nullable();         // ខេត្ត/ក្រុង
            }

            // $table->text('address_detail')->nullable();      // ទីតាំងលម្អិត

            if (!Schema::hasColumn('orders', 'delivery_method')) {
                $table->string('delivery_method')->nullable();  // សេវាដឹក
            }

            if (!Schema::hasColumn('orders', 'delivery_fee')) {
                $table->decimal('delivery_fee', 10, 2)->default(0); // ថ្លៃដឹក
            }

            if (!Schema::hasColumn('orders', 'note')) {
                $table->text('note')->nullable();               // ចំណាំផ្សេងៗ
            }

            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('paid');      // ស្ថានភាព
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // បង្កើត Array ឈ្មោះ Columns សម្រាប់ឆែកមុននឹងលុបការពារ Error ពេល Rollback
            $columns = [
                'customer_type', 'customer_name', 'phone', 'province',
                'delivery_method', 'delivery_fee', 'note', 'status'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
