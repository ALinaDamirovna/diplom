<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductOptionSeeder extends Seeder
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
                'name'  => 'Соус',
                'alias' => 'Выберите соус',
            ],
            [
                'name'  => 'Лаваш',
                'alias' => 'Выберите лаваш',
            ],
        ];

        DB::table('product_option_groups')->insert($dataSeed);

        $dataSeed = [
            [
                'name'        => 'Фирменный',
                'composition' => 'Мягкий творог, сметана, базилик, чеснок, оливковое масло, лимон, специи',
                'desc'        => 'От души, брат!',
                'price'       => 15,
                'group_id'    => 1,
            ],
            [
                'name'        => 'Без добавок',
                'composition' => '',
                'desc'        => 'Без соуса тоже збс, брат!',
                'price'       => 0,
                'group_id'    => 1,
            ],
            [
                'name'        => 'Классический',
                'composition' => '',
                'desc'        => '',
                'price'       => 0,
                'group_id'    => 2,
            ],
            [
                'name'        => 'Ржаной',
                'composition' => '',
                'desc'        => '',
                'price'       => 0,
                'group_id'    => 2,
            ],
        ];

        DB::table('product_options')->insert($dataSeed);
    }

}
