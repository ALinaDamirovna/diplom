<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class ProductOption extends Model
{

    use HasFactory;

    const fieldAliases = [
        'id'          => 'ID',
        'name'        => 'Наименование',
        'composition' => 'Состав',
        'price'       => 'Цена',
        'desc'        => 'Описание',
        'file'        => 'Фотография',
        'sort'        => 'Сортировка',
    ];

    const fieldTypes = [
        'id'          => 'readonly',
        'name'        => 'string',
        'composition' => 'text',
        'price'       => 'number',
        'desc'        => 'text',
        'file'        => 'file',
        'sort'        => 'number',
    ];

    public function getCatNameAttribute()
    {
        return @ProductOptionGroup::find($this->group_id)->name;
    }

    public function getPhotoAttribute()
    {
        if (Storage::exists('options/'.$this->id)) {
            return Config::get('app.url').'/storage/options/'.$this->id;
        } else {
            return null;
        }
    }

}
