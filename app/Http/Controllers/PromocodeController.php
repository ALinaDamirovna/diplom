<?php

namespace App\Http\Controllers;

use App\Models\Promocode;
use Illuminate\Http\Request;

class PromocodeController extends Controller
{

    const viewInfo = [
        'listTitle'   => 'Список промокодов',
        'createTitle' => 'Создать промокод',
        'editTitle'   => 'Редактировать промокод',
        'mainRoute'   => 'promocodes',
    ];

    public function index()
    {
        $fields = Promocode::fieldAliases;

        $list = Promocode::paginate(10);

        return view('item.listp')
            ->with('list', $list)
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields);
    }

    public function create()
    {
        $fields = Promocode::fieldAliases;
        $ftypes = Promocode::fieldTypes;

        $types = [
            0 => 'Сумма',
            1 => 'Процент',
        ];

        return view('item.edit')
            ->with('item', new Promocode)
            ->with('viewInfo', self::viewInfo)
            ->with('types', $types)
            ->with('fields', $fields)
            ->with('fieldTypes', $ftypes);
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'code'        => 'required',
            'type'        => 'required',
            'value'       => 'required',
            'deadline_at' => 'required',
            'max_cnt'     => 'required',
            'min_sum'     => 'required',
        ]);

        $item = new Promocode;

        $deadline = null;
        if (strtotime($request->deadline_at) > 1) {
            $deadline = date('Y-m-d', strtotime($request->deadline_at));
        }

        $item->code        = $request->code;
        $item->type        = (int) $request->type;
        $item->value       = (int) $request->value;
        $item->deadline_at = $deadline;
        $item->cnt         = 0;
        $item->max_cnt     = (int) $request->max_cnt;
        $item->min_sum     = (int) $request->min_sum;

        $item->save();

        return redirect(Self::viewInfo['mainRoute']);
    }

    public function show($productCategory)
    {
        //
    }

    public function edit($id)
    {
        $fields = Promocode::fieldAliases;

        $types = [
            0 => 'Сумма',
            1 => 'Процент',
        ];

        $item = Promocode::find($id);
        $item->id = $item->code;

        return view('item.edit')
            ->with('item', $item)
            ->with('viewInfo', self::viewInfo)
            ->with('types', $types)
            ->with('fields', $fields)
            ->with('fieldTypes', Promocode::fieldTypes);
    }

    public function update(Request $request, $id)
    {
        $valid = $request->validate([
            'code'        => 'required',
            'type'        => 'required',
            'value'       => 'required',
            'deadline_at' => 'required',
            'max_cnt'     => 'required',
            'min_sum'     => 'required',
        ]);

        $item = Promocode::find($id);

        $deadline = null;
        if (strtotime($request->deadline_at) > 1) {
            $deadline = date('Y-m-d', strtotime($request->deadline_at));
        }

        $item->type        = (int) $request->type;
        $item->value       = (int) $request->value;
        $item->deadline_at = $deadline;
        $item->max_cnt     = (int) $request->max_cnt;
        $item->min_sum     = (int) $request->min_sum;

        $item->save();

        return redirect(Self::viewInfo['mainRoute']);
    }

    public function destroy($id)
    {
        Promocode::find($id)->delete();
        return redirect(Self::viewInfo['mainRoute']);
    }

}
