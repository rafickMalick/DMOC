<?php

namespace Database\Seeders;

use App\Models\Locale;
use Illuminate\Database\Seeder;

class LocaleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $locales = [
            ['code' => 'fr', 'name' => 'Francais', 'is_active' => true, 'is_default' => true],
            ['code' => 'en', 'name' => 'English', 'is_active' => true, 'is_default' => false],
            ['code' => 'pt', 'name' => 'Portugues', 'is_active' => true, 'is_default' => false],
        ];

        foreach ($locales as $locale) {
            Locale::query()->updateOrCreate(
                ['code' => $locale['code']],
                $locale
            );
        }
    }
}
