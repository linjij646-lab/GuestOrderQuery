<?php

namespace Plugin\GuestOrderQuery\Controllers;

use Beike\Models\Order;
use Beike\Shop\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class GuestOrderController extends Controller
{
    public function index()
    {
        return view('GuestOrderQuery::shop.query_form');
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keyword' => 'required|string|min:2|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => '请输入至少2个字符'], 422);
        }

        $kw = trim($request->input('keyword'));

        try {
            // 重点：使用 with 预加载 orderHistories 关联
            $orders = Order::with(['orderHistories' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->where(function($q) use ($kw) {
                $q->where('shipping_customer_name', 'like', "%{$kw}%")
                  ->orWhere('number', $kw)
                  ->orWhere('email', $kw)
                  ->orWhere('shipping_telephone', $kw);
            })
            ->whereNull('deleted_at') 
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

            if ($orders->isEmpty()) {
                return response()->json(['success' => false, 'message' => '未找到匹配订单']);
            }

            $result = $orders->map(function ($o) {
                // 格式化历史记录（订单动态）
                $histories = $o->orderHistories->map(function($h) {
                    return [
                        'time'    => $h->created_at ? $h->created_at->format('Y-m-d H:i') : '',
                        'status'  => $this->getStatusLabel($h->status),
                        'comment' => $h->comment ?: '无备注' 
                    ];
                });

                return [
                    'number'       => $o->number,
                    'status_label' => $this->getStatusLabel($o->status),
                    'total'        => '¥' . number_format($o->total, 2),
                    'created_at'   => $o->created_at ? $o->created_at->format('Y-m-d H:i') : '',
                    'name'         => $this->maskName($o->shipping_customer_name),
                    'phone'        => $this->maskPhone($o->shipping_telephone),
                    'address'      => $this->maskAddress($o),
                    'comment'      => $o->comment ?: '无', // 这里是订单主表的备注
                    'histories'    => $histories,        // 这里是 order_histories 表的动态
                ];
            });

            return response()->json(['success' => true, 'data' => $result]);

        } catch (\Throwable $e) {
            Log::error('OrderQuery Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => '查询异常'], 500);
        }
    }

    private function maskName($name) {
        if (!$name) return '***';
        return mb_substr($name, 0, 1) . str_repeat('*', mb_strlen($name) - 1);
    }

    private function maskPhone($phone) {
        if (strlen($phone) >= 11) {
            return substr($phone, 0, 3) . '****' . substr($phone, -4);
        }
        return $phone ?: '未填';
    }

    private function maskAddress($o) {
        return ($o->shipping_zone ?: '') . ($o->shipping_city ?: '') . '****';
    }

    private function getStatusLabel($status) {
        $map = [
            'unpaid' => '待支付', 'paid' => '已支付', 'processing' => '处理中',
            'shipped' => '已发货', 'complete' => '已完成', 'completed' => '已完成', 
            'cancel' => '已取消', 'refund' => '已退款'
        ];
        return $map[$status] ?? $status;
    }
}