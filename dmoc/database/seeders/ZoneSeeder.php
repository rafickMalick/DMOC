<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $zones = [
            ['name' => 'Cotonou', 'country' => 'BJ', 'base_tariff_xof' => 500, 'per_kg_xof' => 150],
            ['name' => 'Porto-Novo', 'country' => 'BJ', 'base_tariff_xof' => 700, 'per_kg_xof' => 180],
            ['name' => 'Parakou', 'country' => 'BJ', 'base_tariff_xof' => 1200, 'per_kg_xof' => 250],
        ];

        foreach ($zones as $zone) {
            Zone::query()->updateOrCreate(
                ['name' => $zone['name'], 'country' => $zone['country']],
                $zone
            );
        }
    }
}
