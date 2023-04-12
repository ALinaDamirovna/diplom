<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{

    use HasFactory;

    const fieldAliases = [
        'id'          => 'ID',
        'name'        => 'Наименование',
        'sort'        => 'Сортировка',
    ];

    const fieldTypes = [
        'id'          => 'readonly',
        'name'        => 'string',
        'sort'        => 'number',
    ];

}
