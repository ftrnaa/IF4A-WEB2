<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(Order $order)
    {
        if ($order->user_id != auth()->id()) {
            abort(403);
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [

            'transaction_details' => [
                'order_id' => $order->kode_order,
                'gross_amount' => (int)$order->total,
            ],

            'customer_details' => [
                'first_name' => $order->nama,
                'email' => $order->email,
                'phone' => $order->telepon,
            ],
        ];
        
        $snapToken = Snap::getSnapToken($params);

        return view(
            'pages.payment',
            compact('order', 'snapToken')
        );
    }


public function notification(Request $request)
{
    
    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = false;

    $notif = new Notification();

    $status = $notif->transaction_status;
    $orderId = $notif->order_id;

    $order = Order::where('kode_order', $orderId)->first();

    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    if ($status == 'settlement') {
        $order->status = 'paid';
    } elseif ($status == 'pending') {
        $order->status = 'pending';
    } elseif ($status == 'expire') {
        $order->status = 'expired';
    } elseif ($status == 'cancel') {
        $order->status = 'cancelled';
    }

    $order->save();

    return response()->json(['message' => 'OK']);
}
public function success(Order $order)
{
    if ($order->user_id != auth()->id()) {
        abort(403);
    }

    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = false;

    try {

        $transaction = Transaction::status($order->kode_order);

        if (
            $transaction->transaction_status == 'settlement' ||
            $transaction->transaction_status == 'capture'
        ) {

            $order->status = 'paid';

            // Simpan jenis pembayaran
            $order->payment_type = $transaction->payment_type ?? null;

            // Simpan channel pembayaran
            if (
                isset($transaction->va_numbers) &&
                !empty($transaction->va_numbers)
            ) {
                $order->payment_channel =
                    $transaction->va_numbers[0]->bank;
            } else {
                $order->payment_channel =
                    $transaction->payment_type ?? null;
            }

            $order->save();
        }

    } catch (\Exception $e) {
        \Log::error($e->getMessage());
    }

    return view('pages.successpayment', compact('order'));
}
}