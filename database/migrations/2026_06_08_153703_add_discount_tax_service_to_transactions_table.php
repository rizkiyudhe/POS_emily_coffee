<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('discount_type', ['nominal', 'percentage'])->nullable()->after('change_amount');
            $table->decimal('discount_value', 15, 0)->default(0)->after('discount_type');
            $table->decimal('discount_amount', 15, 0)->default(0)->after('discount_value');
            $table->decimal('tax_percentage', 5, 2)->default(0)->after('discount_amount');
            $table->decimal('tax_amount', 15, 0)->default(0)->after('tax_percentage');
            $table->decimal('service_percentage', 5, 2)->default(0)->after('tax_amount');
            $table->decimal('service_amount', 15, 0)->default(0)->after('service_percentage');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'discount_type',
                'discount_value',
                'discount_amount',
                'tax_percentage',
                'tax_amount',
                'service_percentage',
                'service_amount'
            ]);
        });
    }
};
