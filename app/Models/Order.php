<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{

    use HasFactory;

    protected $fillable = [
        'status',
        'phone',
        'delivery',
        'contact',
        'payment',
        'comment',
        'total_price',
        'price_data',
    ];

    const fieldAliases = [
        'id'          => 'ID',
        'phone'       => 'Телефон',
        'status_name' => 'Статус',
        'total_price' => 'Цена',
    ];

    const fieldTypes = [
        'id'          => 'readonly',
        'phone'       => 'readonly',
        'status_name' => 'readonly',
        'total_price' => 'readonly',
    ];

    function getHash()
    {
        return substr(md5($this->id.$this->phone), -6);
    }

    static function calcProductSum(&$products)
    {
        $totalSum = 0;

        $dbItems = Product::select('id', 'price')
                          ->get()
                          ->keyBy('id')
                          ->toArray();

        $dbAddition = ProductAddition::select('id', 'price')
                                     ->get()
                                     ->keyBy('id')
                                     ->toArray();

        $dbOption = ProductOption::select('id', 'price')
                                 ->get()
                                 ->keyBy('id')
                                 ->toArray();

        $products = array_map(function ($v) use (
            &$totalSum,
            $dbItems,
            $dbAddition,
            $dbOption
        ) {
            $addPrice = 0;
            if ($v['additions'] != null) {
                foreach ($v['additions'] as $el) {
                    $price    = (int) @$dbAddition[$el['id']]['price'];
                    $addPrice += $price * (int) $el['quantity'];
                }
            }

            $optionPrice = 0;
            if ($v['options'] != null) {
                foreach ($v['options'] as $el) {
                    $price       = (int) @$dbOption[$el['value']]['price'];
                    $optionPrice += $price;
                }
            }

            $v['price'] = (int) @$dbItems[$v['id']]['price'] + $addPrice + $optionPrice;
            $v['sum']   = $v['price'] * (int) $v['quantity'];

            $totalSum += $v['sum'];

            return $v;
        }, $products);

        return $totalSum;
    }

    static function calcDeliverySum($deliveryData, $productSum)
    {
        $geo = DaData::getCoorsByAddress($deliveryData['streetAndNumber']);

        return DeliveryRegion::getDeliveryPriceByCoordinates($geo[1], $geo[0], $productSum);
    }

    public function getStatusNameAttribute()
    {
        return @DB::table('order_statuses')->where('id', $this->status)->first()->name;
    }

    public function deliveryToString($data)
    {
        $str = 'ул. ' . $data['streetAndNumber'];

//        if (isset($data['number']) && $data['number'] != null)
//            $str .= ', д. ' . $data['number'];
        if (isset($data['flat']) && $data['flat'] != null)
            $str .= ', кв. ' . $data['flat'];
        if (isset($data['entrance']) && $data['entrance'] != null)
            $str .= ', подьезд ' . $data['entrance'];
        if (isset($data['doorphone']) && $data['doorphone'] != null)
            $str .= ', домофон ' . $data['doorphone'];
        if (isset($data['floor']) && $data['floor'] != null)
            $str .= ', этаж ' . $data['floor'];

        return $str;
    }


    static public function sendAdminNotif($text)
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            [
                CURLOPT_URL => 'https://api.telegram.org/bot6053805718:AAEiaSRSZ53WyCgdEMNn-KDHXCVqbOrNeHA/sendMessage',
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_POSTFIELDS => [
                    'chat_id' => "-1001934871472",
                    'text' => $text,
                    'parse_mode' => 'html'
                ],
            ]
        );

        $res = curl_exec($ch);
    }

    public function scopeFilter($query, array $filters)
    {
        if (in_array('search', $filters) && request('search')) {
            $data = '%'.request('search').'%';
            $query->where('phone', 'like', $data)
                  ->orWhere('contact', 'like', $data);
        }
        if (in_array('search2', $filters) && request('search2')) {
            $data = '%'.request('search2').'%';
            $query->Where('comment', 'like', $data)
                  ->orWhere('comment_manager', 'like', $data);
        }

        $equal = [
            'status'     => 'status',
            'number' => 'id'
        ];

        $like = [
            //            'comment' => 'COMMENT',
        ];

        $range = [
            'total_price'   => 'total_price',
            'created_at' => 'created_at'
        ];

        // LIKE
        foreach ($like as $key => $field) {
            if (in_array($key, $filters) && request($key)) {
                $query->where($field, 'like', '%'.request($key).'%');
            }
        }

        // Equal
        foreach ($equal as $key => $field) {
            if (in_array($key, $filters) && request($key)) {
                $query->where($field, '=', request($key));
            }
        }

        // Range
        foreach ($range as $key => $field) {
            if (in_array($key, $filters)) {
                if (request($key.'_from')) {
                    $query->where($field, '>=', request($key.'_from'));
                }
                if (request($key.'_to')) {
                    $query->where($field, '<=', request($key.'_to'));
                }
            }
        }
    }

}
