<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class ProductAddition extends Model
{

    use HasFactory;

    const fieldAliases = [
        'id'     => 'ID',
        'name'   => 'Наименование',
        'price'  => 'Цена',
        'weight' => 'Вес',
        'file'   => 'Фотография',
        'sort'   => 'Сортировка',
    ];

    const fieldTypes = [
        'id'     => 'readonly',
        'name'   => 'string',
        'price'  => 'number',
        'weight' => 'number',
        'file'   => 'file',
        'sort'   => 'number',
    ];

    public function getPhotoAttribute()
    {
        if (Storage::exists('additions/'.$this->id)) {
            return Config::get('app.url').'/storage/additions/'.$this->id;
        } else {
            return null;
        }
    }

    static public function getStaticPhotoAttribute($id)
    {
        if (Storage::exists('public/additions/'.$id)) {
            return Config::get('app.url').'/storage/additions/'.$id;
        } else {
            return null;
        }
    }

}
