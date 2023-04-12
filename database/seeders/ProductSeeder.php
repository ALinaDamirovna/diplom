<?php

namespace Database\Seeders;

use App\Models\ProductAddition;
use App\Models\ProductOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
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
                'name'        => 'Шаурма Любительская с курицей',
                'composition' => 'Лаваш, фирменный белый и красный соус, собственного производства, сочное мясо птицы Халяль, свежие помидоры, огурцы, картофель фри.',
                'desc'        => 'Описание 1 ложно сказать, почему непосредственные участники технического прогрессаизации.',
                'price'       => 200.00,
                'weight'      => 380,
                'category'    => 2,
            ],
            [
                'name'        => 'Шаурма Любительская с говядиной',
                'composition' => 'Лаваш, фирменный белый и красный соус, собственного производства, сочное мясо говядины Халяль, свежие помидоры, огурцы, картофель фри.',
                'desc'        => 'Описание 2 ложно сказать, почему непосредственные участники технического прогрессаизации.',
                'price'       => 250.00,
                'weight'      => 400,
                'category'    => 2,
            ],
        ];

        DB::table('products')->insert($dataSeed);

        // OP Start Опции
        // OP1 Соус
        $sauceFirm = ProductOption::where('name', 'Фирменный')->where(
            'group_id',
            1
        )->first()->id;

        $sauceWo = ProductOption::where('name', 'Без добавок')->where(
            'group_id',
            1
        )->first()->id;

        // OP2 Лаваш
        $breadClassic = ProductOption::where('name', 'Классический')->where(
            'group_id',
            2
        )->first()->id;

//        $breadRye = ProductOption::where('name', 'Ржаной')->where(
//            'group_id',
//            2
//        )->first()->id;

        $dataSeed = [
            [
                'product_id' => 1,
                'option_id'  => $sauceFirm,
            ],
            [
                'product_id' => 1,
                'option_id'  => $sauceWo,
            ],
            [
                'product_id' => 1,
                'option_id'  => $breadClassic,
            ],
            [
                'product_id' => 2,
                'option_id'  => $sauceFirm,
            ],
            [
                'product_id' => 2,
                'option_id'  => $sauceWo,
            ],
            [
                'product_id' => 2,
                'option_id'  => $breadClassic,
            ],
        ];

        // OP End
        DB::table('product_has_option')->insert($dataSeed);

        // AP Start Допы
        $friedPotato = ProductAddition::where('name', 'Картофель фри')->first(
        )->id;
        $cheese      = ProductAddition::where('name', 'Сыр')->first()->id;

        $dataSeed = [
            [
                'product_id' => 1,
                'addition_id'  => $friedPotato,
            ],
            [
                'product_id' => 1,
                'addition_id'  => $cheese,
            ],
            [
                'product_id' => 2,
                'addition_id'  => $friedPotato,
            ],
            [
                'product_id' => 2,
                'addition_id'  => $cheese,
            ],
        ];

        // AP End
        DB::table('product_has_addition')->insert($dataSeed);
    }

}
