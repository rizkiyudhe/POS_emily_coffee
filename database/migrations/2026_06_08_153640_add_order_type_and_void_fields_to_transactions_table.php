<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('order_type', ['dine_in', 'takeaway'])->default('dine_in')->after('table_id');
            $table->text('notes')->nullable()->after('change_amount');
            $table->enum('status', ['completed', 'void'])->default('completed')->change();
            $table->text('void_reason')->nullable()->after('status');
            $table->foreignId('voided_by')->nullable()->constrained('users')->after('void_reason');
            $table->timestamp('voided_at')->nullable()->after('voided_by');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['order_type', 'notes', 'void_reason', 'voided_by', 'voided_at']);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed')->change();
        });
    }
};
