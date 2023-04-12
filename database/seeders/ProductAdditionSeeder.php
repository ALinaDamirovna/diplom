<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAdditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataSeed = [
            [
                'name' => 'Картофель фри',
                'price' => 50,
                'weight' => 25
            ],
            [
                'name' => 'Сыр',
                'price' => 45,
                'weight' => 23
            ],
        ];

        DB::table('product_additions')->insert($dataSeed);
    }
}
