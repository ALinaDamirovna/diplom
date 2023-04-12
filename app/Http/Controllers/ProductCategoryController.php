<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{

    const viewInfo = [
        'listTitle'   => 'Список категорий',
        'createTitle' => 'Создать категорию',
        'editTitle'   => 'Редактировать категорию',
        'mainRoute'   => 'product_cats',
    ];

    public function getList(Request $request)
    {
        return ProductCategory::select(
            [
                'id',
                'name',
            ]
        )->orderBy('sort', 'ASC')->get();
    }

    public function index()
    {
        $fields = ProductCategory::fieldAliases;

        $list = ProductCategory::orderBy('sort', 'ASC')->paginate(10);

        return view('item.list')
            ->with('list', $list)
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields);
    }

    public function create()
    {
        $fields = ProductCategory::fieldAliases;

        return view('item.edit')
            ->with('item', new ProductCategory)
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields)
            ->with('fieldTypes', ProductCategory::fieldTypes);
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required',
        ]);

        $item = new ProductCategory;

        $item->name = $request->name;
        $item->sort = (int) $request->sort;

        $item->save();

        return redirect(Self::viewInfo['mainRoute']);
    }

    public function show($productCategory)
    {
        //
    }

    public function edit($id)
    {
        $fields = ProductCategory::fieldAliases;

        return view('item.edit')
            ->with('item', ProductCategory::find($id))
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields)
            ->with('fieldTypes', ProductCategory::fieldTypes);
    }

    public function update(Request $request, $id) {
        $valid = $request->validate([
            'name' => 'required',
        ]);

        $item = ProductCategory::find($id);

        $item->name = $request->name;
        $item->sort = (int) $request->sort;

        $item->save();

        return redirect(Self::viewInfo['mainRoute']);
    }

    public function destroy($id)
    {
        ProductCategory::find($id)->delete();
        return redirect(Self::viewInfo['mainRoute']);
    }

}
