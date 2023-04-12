<?php

namespace App\Http\Controllers;

use App\Models\ProductAddition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ProductAdditionController extends Controller
{

    const viewInfo = [
        'listTitle'   => 'Список добавок',
        'createTitle' => 'Создать добавку',
        'editTitle'   => 'Редактировать добавку',
        'mainRoute'   => 'additions',
    ];


    public function index()
    {
        $fields = ProductAddition::fieldAliases;

        $list = ProductAddition::paginate(20);

        foreach ($list as $el) {
            $path = 'storage/additions/'.$el->id;
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
        $fields = ProductAddition::fieldAliases;
        $ftypes = ProductAddition::fieldTypes;

        return view('item.edit')
            ->with('item', new ProductAddition)
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields)
            ->with('fieldTypes', $ftypes);
    }

    public function edit($id)
    {
        $fields = ProductAddition::fieldAliases;
        $ftypes = ProductAddition::fieldTypes;

        return view('item.edit')
            ->with('item', ProductAddition::find($id))
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields)
            ->with('fieldTypes', $ftypes);
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
        ]);

        $item = new ProductAddition;

        $item->name   = $request->name;
        $item->price  = (int) $request->price;
        $item->weight = (int) $request->weight;
        $item->sort   = (int) $request->sort;

        $item->save();

        if ($request->file()) {
            $request->file('file')->storeAs('additions', $item->id, 'public');
        }

        return redirect(Self::viewInfo['mainRoute']);
    }

    public function update(Request $request, $id)
    {
        $valid = $request->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
        ]);

        $item = ProductAddition::find($id);

        $item->name   = $request->name;
        $item->price  = (int) $request->price;
        $item->weight = (int) $request->weight;
        $item->sort   = (int) $request->sort;

        $item->save();

        if ($request->file()) {
            $request->file('file')->storeAs('additions', $item->id, 'public');
        }

        return redirect(Self::viewInfo['mainRoute']);
    }

    public function destroy($id)
    {
        ProductAddition::find($id)->delete();
        return redirect(Self::viewInfo['mainRoute']);
    }

}
