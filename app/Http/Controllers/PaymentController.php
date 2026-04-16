<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transactions;
use App\Models\Notification;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function showPaymentForm($order_id)
    {
        $order = Order::find($order_id);

        if (!$order) {
            return redirect()->route('buyers.orders.index')->with('error', 'Order not found');
        }

        return view('buyer.payment', compact('order'));
    }

    public function makePayment($order_id)
    {
        $order = Order::find($order_id);

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found');
        }

        $order->payment_status = 'paid';
        $order->status = 'completed';
        $order->save();

        Transactions::create([
            'order_id' => $order_id,
            'amount' => $order->amount,
            'status' => 'paid'
        ]);

        // Send notification to buyer
        Notification::create([
            'user_id' => $order->buyer_id,
            'message' => 'Payment successful.'
        ]);

        // Send notification to vendor
        Notification::create([
            'user_id' => $order->billboard->vendor_id,
            'message' => 'Buyer has completed payment.'
        ]);

        return redirect()->route('buyers.orders.index')->with('success', 'Payment successful');
    }
}