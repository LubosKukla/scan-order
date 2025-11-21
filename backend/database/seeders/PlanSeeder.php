<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name'          => 'Menu',
                'price_monthly' => 8.49,
                'description'   => 'Základné predplatné',
            ],
            [
                'name'          => 'Objednávka',
                'price_monthly' => 38.98,
                'description'   => 'Dobrá voľba',
            ],
            [
                'name'          => 'Platba',
                'price_monthly' => 47.97,
                'description'   => 'Najlepšia voľba',
            ],
            [
                'name'          => 'Exclusive',
                'price_monthly' => 69.96,
                'description'   => 'Prémiové',
            ],
        ];

        Plan::query()->insert($plans);
    }
}
