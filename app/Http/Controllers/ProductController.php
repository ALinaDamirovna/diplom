<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductAddition;
use App\Models\ProductCategory;
use App\Models\ProductOption;
use App\Models\ProductOptionGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    const viewInfo = [
        'listTitle'   => 'Список товаров',
        'createTitle' => 'Создать товар',
        'editTitle'   => 'Редактировать товар',
        'mainRoute'   => 'products',
    ];

    const filters = [
        'search' => [
            'title' => 'Наименование, Состав',
            'type'  => 'text',
        ],
        'price'    => [
            'title' => 'Цена',
            'type'  => 'range',
        ],
        'in_stop'    => [
            'title'   => 'В стоп листе',
            'type'    => 'select',
            'options' => [
                '0' => 'Не важно',
                '1' => 'Нет',
                '2' => 'Да',
            ],
        ],
        'category'    => [
            'title'   => 'Категория',
            'type'    => 'select',
        ]
    ];

    public function __construct()
    {
        $this->instance = new Product();
    }

    public function getList(Request $request)
    {
        $params = $request->all();

        $query = $this->instance->filter(['category']);

        return $query->active()->select(
            [
                'id',
                'name',
                'desc',
                'photo',
                'price',
                'category',
            ]
        )->orderBy(DB::raw('ABS(sort)'), 'asc')->orderBy('id', 'asc')->get();

        //        "id": 1,
        //        "created_at": null,
        //        "updated_at": null,
        //        "name": "Шаурма Любительская с курицей",
        //        "composition": "Лаваш, фирменный белый и красный соус, собственного производства, сочное мясо птицы Халяль, свежие помидоры, огурцы, картофель фри.",
        //        "photo_id": null,
        //        "price": 200,
        //        "weight": 380,
        //        "calories": null,
        //        "proteins": null,
        //        "fats": null,
        //        "carbohydrates": null,
        //        "category": 2
    }

    public function getById($id)
    {
        $elem = $this->instance->with('additions')->with('options')->find($id)->toArray();

        $groups = ProductOptionGroup::all()->keyBy('id');

        $optionWG = [];

        foreach ($elem['options'] as $option) {
            if (isset($groups[$option['group_id']])) {
                $optionWG[$option['group_id']][] = $option;
            }
        }

        $optionWG = array_map(
            function ($v) use ($groups) {
                $ret           = $groups[$v[0]['group_id']];
                $ret['values'] = $v;
                return $ret;
            },
            $optionWG
        );

        $optionWG = array_values($optionWG);

        $elem['options'] = $optionWG;

        $elem['additions'] = array_map(function ($v) {
            $v['photo'] = ProductAddition::getStaticPhotoAttribute($v['id']);
            return $v;
        }, $elem['additions']);

        return $elem;
    }

    public function saveFile(Request $request)
    {
        $path = $request->file('photo')->storeAs(
            'products', $request->get('id')
        );

        //        $Product = $this->instance->find($request->get('id'));
        //        dump($Product);
        //        dump($path);
        $path = Storage::url('products/'.$request->get('id'));

        return [
            $path,
            Storage::exists('products/'.$request->get('id')),
            Storage::exists('products/2'),
        ];
    }

    public function index()
    {
        $fields = Product::fieldAliases;

        $fields['catName'] = 'Категория';

        unset($fields['composition']);
        unset($fields['desc']);

        $list = Product::filter(array_keys(Self::filters))->paginate(10);

        foreach ($list as $el) {
            $path = 'storage/products/'.$el->id;
            if (file_exists(public_path($path))) {
                $el->file = Config::get('app.url').'/'.$path;
            }
        }

        $filters = Self::filters;

        $filters['category']['options'] = ProductCategory::select(['id', 'name'])->get()->pluck('name', 'id')->toArray();

        array_unshift($filters['category']['options'], 'Не важно');

        foreach ($filters as $k => $v) {
            if (in_array($v['type'], ['daterange', 'range'])) {
                $filters[$k]['value']['from'] = @request($k . '_from');
                $filters[$k]['value']['to'] = @request($k . '_to');
            }
            else {
                $filters[$k]['value'] = @request($k);
            }
        }

        return view('item.list')
            ->with('list', $list)
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields)
            ->with('filter', $filters);
    }

    public function create()
    {
        $item = new Product;

        $optionGroups = ProductOptionGroup::get()->toArray();
        foreach ($optionGroups as &$group) {
            $group['items'] = ProductOption::where('group_id', $group['id'])->select(['id', 'name'])->get()->pluck('name', 'id')->toArray();
        }
        $additions = ProductAddition::select(['id', 'name'])->get()->pluck('name', 'id')->toArray();

        $fields             = Product::fieldAliases;
        $fields['category'] = 'Категория';
        $ftypes             = Product::fieldTypes;
        $ftypes['category'] = 'select';

        $cats = ProductCategory::select(['id', 'name'])->get()->pluck('name', 'id')->toArray();

        return view('item.edit')
            ->with('item', $item)
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields)
            ->with('fieldTypes', $ftypes)
            ->with('cats', $cats)
            ->with('optionGroups', $optionGroups)
            ->with('additions', $additions);
    }

    public function edit($id)
    {
        $item = Product::with('additions')->with('options')->find($id);

        $item->options   = $item->options->pluck('name', 'id')->toArray();
        $item->additions = $item->additions->pluck('name', 'id')->toArray();

        $optionGroups = ProductOptionGroup::get()->toArray();
        foreach ($optionGroups as &$group) {
            $group['items'] = ProductOption::where('group_id', $group['id'])->select(['id', 'name'])->get()->pluck('name', 'id')->toArray();
        }
        $additions = ProductAddition::select(['id', 'name'])->get()->pluck('name', 'id')->toArray();


        $fields             = Product::fieldAliases;
        $fields['category'] = 'Категория';
        $ftypes             = Product::fieldTypes;
        $ftypes['category'] = 'select';

        $cats = ProductCategory::select(['id', 'name'])->get()->pluck('name', 'id')->toArray();

        return view('item.edit')
            ->with('item', $item)
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields)
            ->with('fieldTypes', $ftypes)
            ->with('cats', $cats)
            ->with('optionGroups', $optionGroups)
            ->with('additions', $additions);
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
        ]);

        $item = new Product;

        $item->name        = $request->name;
        $item->composition = $request->composition;
        $item->price       = (int) $request->price;
        $item->desc        = $request->desc;
        $item->weight      = (int) $request->weight;
        $item->category    = $request->category ?? 1;
        $item->in_stop     = 0;
        $item->sort        = (int) $request->sort;

        $item->save();

        if ($request->file()) {
            $request->file('file')->storeAs('products', $item->id, 'public');
        }

        if (@$request->options) {
            $options = ProductOption::whereIn('id', array_keys($request->options))->get();
            $item->options()->saveMany($options);
        } else {
            $item->options()->detach();
        }

        if (@$request->additions) {
            $additions = ProductAddition::whereIn('id', array_keys($request->additions))->get();
            $item->additions()->saveMany($additions);
        } else {
            $item->additions()->detach();
        }

        return redirect(Self::viewInfo['mainRoute']);
    }

    public function update(Request $request, $id)
    {
        $valid = $request->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
        ]);

        $item = Product::find($id);

        $item->name        = $request->name;
        $item->composition = $request->composition;
        $item->price       = (int) $request->price;
        $item->desc        = $request->desc;
        $item->weight      = (int) $request->weight;
        $item->category    = $request->category ?? 1;
        $item->in_stop     = (int) $item->in_stop;
        $item->sort        = (int) $request->sort;

        $item->save();

        if ($request->file()) {
            $request->file('file')->storeAs('products', $item->id, 'public');
        }

        if (@$request->options) {
            $options = ProductOption::whereIn('id', array_keys($request->options))->get();
            $item->options()->detach();
            $item->options()->saveMany($options);
        } else {
            $item->options()->detach();
        }

        if (@$request->additions) {
            $additions = ProductAddition::whereIn('id', array_keys($request->additions))->get();
            $item->additions()->detach();
            $item->additions()->saveMany($additions);
        } else {
            $item->additions()->detach();
        }

        return redirect(Self::viewInfo['mainRoute']);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect(Self::viewInfo['mainRoute']);
    }

    public function stop($id)
    {
        $item = Product::find($id);

        if ($item) {
            $item->in_stop = !(bool) $item->in_stop;
            $item->save();
        }

        return redirect(Self::viewInfo['mainRoute']);
    }

}
