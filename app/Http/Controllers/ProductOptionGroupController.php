<?php

namespace App\Http\Controllers;

use App\Models\ProductOptionGroup;
use Illuminate\Http\Request;

class ProductOptionGroupController extends Controller
{
    const viewInfo = [
        'listTitle'   => 'Список категорий опций',
        'createTitle' => 'Создать категорию опций',
        'editTitle'   => 'Редактировать категорию опций',
        'mainRoute'   => 'option_cats',
    ];

    public function index()
    {
        $fields = ProductOptionGroup::fieldAliases;

        $list = ProductOptionGroup::paginate(10);

        return view('item.list')
            ->with('list', $list)
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields);
    }

    public function create()
    {
        $fields = ProductOptionGroup::fieldAliases;

        return view('item.edit')
            ->with('item', new ProductOptionGroup)
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields)
            ->with('fieldTypes', ProductOptionGroup::fieldTypes);
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required',
            'alias' => 'string'
        ]);

        $item = new ProductOptionGroup;

        $item->name = $request->name;
        $item->alias = $request->alias;

        $item->save();

        return redirect(Self::viewInfo['mainRoute']);
    }

    public function show(ProductOptionGroup $productCategory)
    {
        //
    }

    public function edit($id)
    {
        $fields = ProductOptionGroup::fieldAliases;

        return view('item.edit')
            ->with('item', ProductOptionGroup::find($id))
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields)
            ->with('fieldTypes', ProductOptionGroup::fieldTypes);
    }

    public function update(Request $request, $id) {
        $valid = $request->validate([
            'name' => 'required',
            'alias' => 'string'
        ]);

        $item = ProductOptionGroup::find($id);

        $item->name = $request->name;
        $item->alias = $request->alias;

        $item->save();

        return redirect(Self::viewInfo['mainRoute']);
    }

    public function destroy($id)
    {
        ProductOptionGroup::find($id)->delete();
        return redirect(Self::viewInfo['mainRoute']);
    }

}

