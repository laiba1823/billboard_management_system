<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PerfectDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PerfectAdminSeeder::class,
            PerfectCategorySeeder::class,
            PerfectBuyerSeeder::class,
            PerfectVendorSeeder::class,
            PerfectOrderSeeder::class,
            PerfectTransactionsSeeder::class,
        ]);
    }
}