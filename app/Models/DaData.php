<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaData extends Model
{

    use HasFactory;

    const APIKEY = '44b590bdd3208aec02077f59db32191d76849d51';

    const URL = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/';

    static function getAddressInfoByValue()
    {
        //        $res = self::send('findById/address', [
        //            'query' => 'a6df811e-d036-43de-bfae-e09366ce89fa',
        //        ]);
        $res = self::send('suggest/address', [
            'query'     => 'Удмуртская 235',
            "locations" => [
                ["kladr_id" => "18000001"],
            ],
        ]);

        //        dump($res);

        //        $longitude_x = $res['suggestions'][0]['data']['geo_lat'];
        //        $latitude_y = $res['suggestions'][0]['data']['geo_lon'];

        return $res;
    }

    static function getCoorsByAddress($address)
    {
        $res = self::send('suggest/address', [
            'query'     => $address,
            "locations" => [
                ["kladr_id" => "18000001"],
            ],
        ]);

        return [
            @$res['suggestions'][0]['data']['geo_lat'],
            @$res['suggestions'][0]['data']['geo_lon'],
        ];
    }

    static function send($method, $data)
    {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Token '.self::APIKEY,
        ];

        $ch = curl_init(self::URL.$method);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        $response = json_decode($response, true);

        $res['Error'] = curl_error($ch);
        $res['Info']  = curl_getinfo($ch);

        curl_close($ch);

        if (@$res['Error']) {
        }

        return $response;
    }

}
