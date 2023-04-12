<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
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
                'id' => 1,
                'name' => 'Комбо',
            ],
            [
                'id' => 2,
                'name' => 'Шаурма',
            ],
            [
                'id' => 3,
                'name' => 'Бургеры',
            ],
            [
                'id' => 4,
                'name' => 'Первые блюда',
            ],
        ];

        DB::table('product_categories')->insert($dataSeed);
    }
}
