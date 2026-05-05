<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','confirmed','assigned','in_delivery','delivered','failed','cancelled') NOT NULL DEFAULT 'pending'");
            DB::statement("ALTER TABLE deliveries MODIFY status ENUM('pending','assigned','in_delivery','delivered','failed') NOT NULL DEFAULT 'pending'");
        }

        DB::table('orders')
            ->whereIn('status', ['preparing', 'shipped', 'in_transit'])
            ->update(['status' => 'in_delivery']);

        DB::table('deliveries')
            ->whereIn('status', ['picked_up', 'in_transit'])
            ->update(['status' => 'in_delivery']);
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        DB::table('orders')
            ->where('status', 'in_delivery')
            ->update(['status' => 'shipped']);

        DB::table('deliveries')
            ->where('status', 'in_delivery')
            ->update(['status' => 'in_transit']);

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','confirmed','preparing','shipped','delivered') NOT NULL DEFAULT 'pending'");
            DB::statement("ALTER TABLE deliveries MODIFY status ENUM('pending','assigned','picked_up','in_transit','delivered','failed') NOT NULL DEFAULT 'pending'");
        }
    }
};
