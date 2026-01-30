<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * 配送ステータス更新画面の表示
     * @param int $item_id
     * @return \Illuminate\View\View
     */
    public function index($item_id)
    {
        try {
            $order = Order::where('item_id', $item_id)->firstOrFail();

            return view('update-status', compact('order'));
        } catch (Exception $e) {
            return view('error');
        }
    }

    /**
     * 注文の配送ステータスを更新する
     * @param Request $request
     * @param int $order_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $order_id)
    {
        try {
            $order = Order::findOrFail($order_id);
            $order->update([
                'status' => $request->status,
            ]);
            return redirect('/mypage?page=sell');
        } catch (Exception $e) {
            return view('error');
        }
    }

    /**
     * 配送ステータス確認画面の表示
     * @param int $item_id
     * @return \Illuminate\View\View
     */
    public function detail($item_id)
    {
        try {
            $order = Order::where('item_id', $item_id)->firstOrFail();

            return view('check-status', compact('order'));
        } catch (Exception $e) {
            return view('error');
        }
    }
}
