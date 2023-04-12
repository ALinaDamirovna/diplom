<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{

    use HasFactory;

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $fillable = [
        'code',
        'type',
        'value',
        'created_at',
        'deadline_at',
        'cnt',
        'max_cnt',
        'min_sum',
    ];

    const fieldAliases = [
        'code'        => 'Код',
        'type'        => 'Тип',
        'value'       => 'Значение',
        'created_at'  => 'Дата создания',
        'deadline_at' => 'Дата окончания',
        'cnt'         => 'Кол-во применений',
        'max_cnt'     => 'Макс кол-во применений',
        'min_sum'     => 'Минимальная сумма корзины',
    ];

    const fieldTypes = [
        'code'        => 'string',
        'type'        => 'select',
        'value'       => 'number',
        'created_at'  => 'date',
        'deadline_at' => 'date',
        'cnt'         => 'readonly',
        'max_cnt'     => 'number',
        'min_sum'     => 'number',
    ];

    public static function getPromoDiscount($use, $code, $productSum)
    {
        $discount = 0;

        if ($code != null) {
            $promo = self::find($code);

            if ($promo) {
                if (self::checkAllow($promo, $productSum)) {
                    switch ($promo->type) {
                        case 0:
                            $discount = $promo->value;
                            break;
                        case 1:
                            $discount = $productSum * (min(99, $promo->value) / 100);
                            break;
                    }

                    if ($use) {
                        $promo->increment('cnt');
                    }
                }
            }
        }

        return (int) $discount;
    }

    private static function checkAllow($promo, $productSum)
    {
        $allow = true;

        if ($promo->min_sum != 0 && $productSum < $promo->min_sum) {
            $allow = false;
        }

        if ($promo->max_cnt != 0 && $promo->cnt >= $promo->max_cnt) {
            $allow = false;
        }

        return $allow;
    }

}
