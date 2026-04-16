<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;

class VendorOrdersController extends Controller
{
    public function index(Request $request)
    {
        if (!session('LoggedVendor')) {
            return redirect()->route('vendors.loginForm');
        }

        $vendorId = session('LoggedVendor');
        $vendor = \App\Models\Vendor::find($vendorId);

        // Fetch orders where the billboard belongs to the logged-in vendor
        $orders = Order::with(['billboard', 'buyer'])
            ->whereHas('billboard', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->get();

        return view('vendor.orders.index', compact('orders', 'vendor'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $status = $request->input('status');

        // Validate status
        if (!in_array($status, [
            Order::STATUS_PENDING,
            Order::STATUS_ACCEPTED,
            Order::STATUS_PAID,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELLED,
        ])) {
            return back()->with('fail', 'Invalid status');
        }

        // Validate transitions
        if ($status == Order::STATUS_ACCEPTED && $order->status != Order::STATUS_PENDING) {
            return back()->with('fail', 'Can only accept pending orders');
        }

        $order->status = $status;
        $order->save();

        // Send notification to buyer when bid is accepted
        if ($status == Order::STATUS_ACCEPTED) {
            Notification::create([
                'user_id' => $order->buyer_id,
                'message' => 'Your bid has been accepted. Please complete payment.'
            ]);
        }

        return back()->with('success', 'Order status updated successfully');
    }
}