<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function pageDelivery()
    {
        $config = Setting::where('module', 'delivery')->pluck('value', 'setting')->toArray();

        return view('settings')->with('config', $config);
    }

    public function saveDelivery(Request $request)
    {
        $this->saveData($request, 'delivery');

        return $this->pageDelivery();
    }

    public function saveData(Request $request, $module)
    {
        $data = $request->all();

        unset($data['_token']);

        foreach ($data as $k => $v) {
            $data[$k] = ['module' => $module, 'setting' => $k, 'value' => $v];
        }

        Setting::where('module', $module)->delete();

        Setting::upsert(array_values($data),
            ['module', 'setting'],
            ['value']
        );
    }

    function index()
    {
        return view('settings');
    }
}
