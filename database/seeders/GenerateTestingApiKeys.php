<?php

namespace Database\Seeders;

use App\Models\ApiKey;
use Illuminate\Database\Seeder;

class GenerateTestingApiKeys extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApiKey::firstOrCreate(
            ['name' => 'testing-server'],
            [
                'key' => 'LPO67QIcaluOQVfUVjMBbF7HAm7EGTqgmou5yT3k50BEEfXpJbjW9FpWjJl2DRiI'
            ]
        );
    }
}
