<?php

namespace App\Http\Controllers;

use App\Jobs\SendSMS;
use App\Models\DaData;
use App\Models\DeliveryRegion;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductAddition;
use App\Models\ProductOption;
use App\Models\Promocode;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    const viewInfo = [
        'listTitle' => 'Список заказов',
        'editTitle' => 'Редактировать заказ',
        'mainRoute' => 'orders',
    ];

    const filters = [
        'search' => [
            'title' => 'Телефон, Имя, Email',
            'type'  => 'text',
        ],
        'search2' => [
            'title' => 'Комментарий',
            'type'  => 'text',
        ],
        'created_at'    => [
            'title' => 'Дата заказа',
            'type'  => 'daterange',
        ],
        'total_price'    => [
            'title' => 'Итоговая стоимость',
            'type'  => 'range',
        ],
        'status'    => [
            'title'   => 'Статус',
            'type'    => 'select',
        ],
        'number'    => [
            'title'   => 'Номер заказа',
            'type'    => 'string',
        ]
    ];

    public function __construct()
    {
        $this->instance = new Order();
    }

    public function getUserOrders(Request $request)
    {
        $orders = Order::where('phone', $request->user()->phone)->select([
            'id',
            'created_at',
            'status',
            'phone',
            'total_price',
        ])->get();

        if ($orders != null) {
            foreach ($orders as &$order) {
                $order->hash       = $order->getHash();
                $order->statusName = $order->statusName;
            }
        }

        return $orders;
    }

    public function getOrderInfo(Request $request, $oid, $hash)
    {
        $order = Order::find($oid);

        if ($order && $order->getHash() == $hash) {
            $order->statusName = $order->statusName;
            $order->delivery = json_decode($order->delivery, true);
            $order->contact  = json_decode($order->contact, true);

            $order->products = OrderProduct::where('order_id', $order->id)->get();

            foreach ($order->products as &$product) {
                $product['additional'] = $product['additional'] != null ? json_decode($product['additional'], true) : null;
                $product['option']     = $product['option'] != null ? json_decode($product['option'], true) : null;
            }

            return $order;
        } else {
            return null;
        }
    }

    public function calcOrder(Request $request)
    {
        $params = $request->all();

        $productSum  = Order::calcProductSum($params['products']);
        $deliverySum = Order::calcDeliverySum($params['delivery'], $productSum);

        $discount = Promocode::getPromoDiscount(false, @$params['promocode'], $productSum);

        $totalSum = $productSum - $discount + $deliverySum;

        $config = Setting::where('module', 'delivery')->pluck('value', 'setting')->toArray();

        $allowed = $productSum >= (int) $config['min_sum'];

        return [
            'total'    => $totalSum,
            'products' => $productSum,
            'discount' => $discount,
            'delivery' => $deliverySum,
            'allowed'  => $allowed,
            'to_allow' => $allowed ? 0 : (int) $config['min_sum'] - $productSum,
        ];
    }

    public function deliverySettings()
    {
        return Setting::where('module', 'delivery')->pluck('value', 'setting')->toArray();
    }

    static public function formatPhone($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);

        if ((strlen($phone) == 10 && $phone[0] == '9') || strlen($phone) == 11) {
            return '7'.substr($phone, -10);
        } else {
            return null;
        }
    }

    public function createOrder(Request $request)
    {
        $params = $request->json()->all();

        $productSum  = Order::calcProductSum($params['products']);
        $deliverySum = Order::calcDeliverySum($params['delivery'], $productSum);

        $discount = Promocode::getPromoDiscount(true, @$params['promocode'], $productSum);

        $totalSum = $productSum - $discount + $deliverySum;

        $phone = self::formatPhone($params['contact']['phone']);

        $order = Order::create([
            'status'      => 1,
            'phone'       => $phone,
            'delivery'    => json_encode($params['delivery']),
            'contact'     => json_encode($params['contact']),
            'comment'     => $params['comment'],
            'total_price' => $totalSum,
            'price_data'  => json_encode(['prods' => $productSum, 'delivery' => $deliverySum, 'discount' => $discount])
        ]);

        //Сохранение заказа
        //1. Товары с опциями и допами
        foreach ($params['products'] as $product) {
            OrderProduct::create([
                'order_id'    => $order->id,
                'product_id'  => $product['id'],
                'quantity'    => (int) $product['quantity'],
                'additional'  => json_encode($product['additions']),
                'option'      => json_encode($product['options']),
                'price'       => $product['price'],
                'total_price' => $product['sum'],
            ]);
        }

        $orderLink = 'https://papalavash.ru/order/'.$order->id.'/'.$order->getHash();

        if ($phone) {
            dispatch(new SendSMS($phone, 'PL #'.$order->id.' '.$orderLink));
        }

        $notifStr = '🖊 Новый заказ №<b>' . $order->id . '</b>' .
                    PHP_EOL . '📞 Клиент: <b>' . @$params['contact']['name'] . ' ' . $phone . '</b>' .
                    PHP_EOL . '💵 Сумма заказа: <b>' . $totalSum . '</b>' .
                    PHP_EOL . '📝 Комментарий: <b>' . @$params['comment'] . '</b>' .
                    PHP_EOL . '🔗 Управление заказом: https://lavash.endlessmind.space/orders/'.$order->id.'/edit';

        Order::sendAdminNotif($notifStr);


        // TODO: Формирование и возврат ссылки на оплату
        return [
            'id'           => $order->id,
            'hash'         => $order->getHash(),
            'payment_link' => $orderLink,
        ];
    }

    public function index(Request $request)
    {
        $fields = Order::fieldAliases;

        $list = Order::filter(array_keys(Self::filters))->orderBy('id', 'DESC')->paginate(10);

        $filters = Self::filters;

        $filters['status']['options'] = DB::table('order_statuses')->select(['id', 'name'])->get()->pluck('name', 'id')->toArray();

        array_unshift($filters['status']['options'], 'Не важно');

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

    public function edit(Request $request, $id)
    {
        $fields     = Order::fieldAliases;
        $fieldTypes = Order::fieldTypes;

        unset($fields['total_price']);

        $fields['category']     = 'Новый статус';
        $fieldTypes['category'] = 'select';

        $fields['comment_manager']     = 'Комментарий';
        $fieldTypes['comment_manager'] = 'text';

        $fields['delivery']     = 'Доставка';
        $fieldTypes['delivery'] = 'textarea_readonly';

        $fields['comment']     = 'Комментарий клиента';
        $fieldTypes['comment'] = 'textarea_readonly';

        $cats = DB::table('order_statuses')->select(['id', 'name'])->get()->pluck('name', 'id')->toArray();

        $item = Order::find($id);

        if ($item) {
            $item = $this->getOrderInfo($request, $item->id, $item->getHash());

            foreach ($item->products as &$product) {
                $product->model = Product::find($product->product_id);

                $newAdd = [];
                foreach ($product->additional as $x) {
                    $x['model'] = ProductAddition::find($x['id']);
                    $newAdd[]   = $x;
                }
                $product->additional = $newAdd;

                $newAdd = [];
                foreach ($product->option as $x) {
                    $x['model'] = ProductOption::find($x['value']);
                    $newAdd[]   = $x;
                }
                $product->option = $newAdd;
            }

            $item->delivery = $item->deliveryToString($item->delivery);

            $newFields = [
                'sum_prods' => ['Стоимость товаров', 'readonly'],
                'sum_delivery' => ['Стоимость доставки', 'readonly'],
                'sum_discount' => ['Скидка', 'readonly'],
                'sum_total' => ['Итого', 'readonly'],
            ];

            foreach ($newFields as $key => $newField) {
                $fields[$key] = $newField[0];
                $fieldTypes[$key] = $newField[1];
            }

            $buf = @json_decode($item->price_data, 1);

            $item->sum_prods = (int) @$buf['prods'];
            $item->sum_delivery = (int) @$buf['delivery'];
            $item->sum_discount = (int) @$buf['discount'];
            $item->sum_total = (int) $item->total_price;
        }

        return view('item.edit')
            ->with('item', $item)
            ->with('viewInfo', self::viewInfo)
            ->with('fields', $fields)
            ->with('fieldTypes', $fieldTypes)
            ->with('cats', $cats);
    }

    public function update(Request $request, $id)
    {
        $valid = $request->validate([
            'category'        => 'required',
            'comment_manager' => 'nullable',
        ]);


        $item = Order::find($id);

        $item->status          = $request->category ?? 1;
        $item->comment_manager = $request->comment_manager;

        $item->save();

        return redirect(Self::viewInfo['mainRoute'].'/'.$item->id.'/edit');
    }

}
