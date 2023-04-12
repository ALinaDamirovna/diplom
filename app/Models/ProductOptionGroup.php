<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptionGroup extends Model
{

    use HasFactory;

    const fieldAliases = [
        'id'   => 'ID',
        'name' => 'Наименование',
        'alias' => 'Текст',
    ];

    const fieldTypes = [
        'id'   => 'readonly',
        'name' => 'string',
        'alias' => 'string',
    ];

}
