<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{

    use HasFactory;

    const fieldAliases = [
        'id'          => 'ID',
        'name'        => 'Наименование',
        'composition' => 'Состав',
        'price'       => 'Цена',
        'desc'        => 'Описание',
        'weight'      => 'Вес',
        'file'        => 'Фотография',
        'sort'        => 'Сортировка',
    ];

    const fieldTypes = [
        'id'          => 'readonly',
        'name'        => 'string',
        'composition' => 'text',
        'price'       => 'number',
        'desc'        => 'text',
        'weight'      => 'number',
        'file'        => 'file',
        'sort'        => 'number',
    ];

    public function scopeFilter($query, array $filters)
    {
        if (in_array('search', $filters) && request('search')) {
            $data = '%'.request('search').'%';
            $query->where('name', 'like', $data)
                  ->orWhere('composition', 'like', $data)
                  ->orWhere('desc', 'like', $data);
        }

        if (in_array('in_stop', $filters) && request('in_stop')) {
            $data = request('in_stop');
            if ($data == '1') {
                $query->where('in_stop', 0);
            }
            if ($data == '2') {
                $query->where('in_stop', 1);
            }
        }

        $equal = [
            'category'     => 'category',
        ];

        $like = [
//            'comment' => 'COMMENT',
        ];

        $range = [
            'price'   => 'price',
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

    public function scopeActive($query)
    {
        $query->where('in_stop', '=', '0');
    }

    public function getCatNameAttribute()
    {
        return @ProductCategory::find($this->category)->name;
    }

    public function options()
    {
        return $this->belongsToMany(ProductOption::class, 'product_has_option', 'product_id', 'option_id');
    }

    public function additions()
    {
        return $this->belongsToMany(ProductAddition::class, 'product_has_addition', 'product_id', 'addition_id');
    }

    public function getPhotoAttribute()
    {
        if (Storage::exists('products/'.$this->id)) {
            return Config::get('app.url').'/storage/products/'.$this->id;
        } else {
            return null;
        }
    }

}
