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
        Schema::table('deliveries', function (Blueprint $table) {
            if (! Schema::hasColumn('deliveries', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('status');
            }

            if (! Schema::hasColumn('deliveries', 'failed_reason')) {
                $table->string('failed_reason', 255)->nullable()->after('delivered_at');
            }

            if (! Schema::hasColumn('deliveries', 'amount_expected')) {
                $table->unsignedInteger('amount_expected')->nullable()->after('failed_reason');
            }

            if (! Schema::hasColumn('deliveries', 'amount_received')) {
                $table->unsignedInteger('amount_received')->nullable()->after('amount_expected');
            }

            if (! Schema::hasColumn('deliveries', 'cod_received_at')) {
                $table->timestamp('cod_received_at')->nullable()->after('amount_received');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            if (Schema::hasColumn('deliveries', 'cod_received_at')) {
                $table->dropColumn('cod_received_at');
            }

            if (Schema::hasColumn('deliveries', 'amount_received')) {
                $table->dropColumn('amount_received');
            }

            if (Schema::hasColumn('deliveries', 'amount_expected')) {
                $table->dropColumn('amount_expected');
            }

            if (Schema::hasColumn('deliveries', 'failed_reason')) {
                $table->dropColumn('failed_reason');
            }

            if (Schema::hasColumn('deliveries', 'started_at')) {
                $table->dropColumn('started_at');
            }
        });
    }
};
