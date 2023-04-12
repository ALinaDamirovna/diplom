<?php

namespace App\Http\Controllers;

use App\Models\ProductOption;
use App\Models\ProductOptionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ProductOptionController extends Controller
{

    const viewInfo = [
        'listTitle'   => 'Список опций',
        'createTitle' => 'Создать опцию',
        'editTitle'   => 'Редактировать опцию',
        'mainRoute'   => 'options',
    ];


    public function index()
    {
        $fields = ProductOption::fieldAliases;

        $fields['catName'] = 'Категория';

        unset($fields['composition']);
        unset($fields['desc']);

        $list = ProductOption::paginate(20);

        foreach ($list as $el) {
            $path = 'storage/options/'.$el->id;
            if (file_exists(public_path($path))) {
                $el->file = Config::get('app.url').'/'.$path;
            }
        }

        return view('item.list')
            ->with('list', $list)
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields);
    }

    public function create()
    {
        $fields = ProductOption::fieldAliases;
        $fields['category'] = 'Категория';
        $ftypes = ProductOption::fieldTypes;
        $ftypes['category'] = 'select';

        $cats = ProductOptionGroup::select(['id', 'name'])->get()->pluck('name', 'id')->toArray();

        return view('item.edit')
            ->with('item', new ProductOption)
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields)
            ->with('fieldTypes', $ftypes)
            ->with('cats', $cats);
    }

    public function edit($id)
    {
        $fields = ProductOption::fieldAliases;
        $fields['category'] = 'Категория';
        $ftypes = ProductOption::fieldTypes;
        $ftypes['category'] = 'select';

        $cats = ProductOptionGroup::select(['id', 'name'])->get()->pluck('name', 'id')->toArray();

        return view('item.edit')
            ->with('item', ProductOption::find($id))
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields)
            ->with('fieldTypes', $ftypes)
            ->with('cats', $cats);
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
        ]);

        $item = new ProductOption;

        $item->name        = $request->name;
        $item->composition = $request->composition;
        $item->price       = (int) $request->price;
        $item->desc        = $request->desc;
        $item->group_id    = $request->category ?? 1;
        $item->sort        = (int) $request->sort;

        $item->save();

        if ($request->file()) {
            $request->file('file')->storeAs('options', $item->id, 'public');
        }

        return redirect(Self::viewInfo['mainRoute']);
    }

    public function update(Request $request, $id)
    {
        $valid = $request->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
        ]);

        $item = ProductOption::find($id);

        $item->name        = $request->name;
        $item->composition = $request->composition;
        $item->price       = (int) $request->price;
        $item->desc        = $request->desc;
        $item->group_id    = $request->group_id ?? 1;
        $item->sort        = (int) $request->sort;

        $item->save();

        if ($request->file()) {
            $request->file('file')->storeAs('options', $item->id, 'public');
        }

        return redirect(Self::viewInfo['mainRoute']);
    }

    public function destroy($id)
    {
        ProductOption::find($id)->delete();
        return redirect(Self::viewInfo['mainRoute']);
    }
}
