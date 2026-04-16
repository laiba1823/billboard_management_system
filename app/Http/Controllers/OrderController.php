<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Billboard;
use App\Models\Buyer;
use App\Models\Category;
use App\Models\Company;
use App\Models\Gig;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index(Request $request){
        $buyer = Buyer::find($request->buyer->id);

        return view("buyer.orders.index", [
            "buyer" => $buyer,
            "orders" => $buyer->orders,
        ]);
    }

    public function create($billboardId){
        $categories = Category::all();
        $billboard = Billboard::find($billboardId);

        return view("buyer.orders.create", [
            "categories" => $categories,
            "billboard" => $billboard,
        ]);

    }

    public function store(StoreOrderRequest $request){
        $validatedData = $request->validate([
            'buyer_id' => 'required|exists:buyers,id',
            'vendor_id' => 'required|exists:vendors,id',
            'billboard_id' => 'required|exists:billboards,id',
            'description' => 'string',
            'amount' => 'required|numeric',
            'time' => 'required|integer',
        ]);

        $validatedData['status'] = Order::STATUS_PENDING;
        $validatedData['number'] = rand(10000000, 99999999);

        Order::create($validatedData);

        $billboard = Billboard::findOrFail($request->billboard_id);
        $billboard->update([
            "last_bid" => $request->amount,
        ]);

        return redirect()->route('buyers.orders.index')->with('success', 'Order Placed Successfully');
    }

    public function show(Order $order, $id, Request $request)
    {
        $order = Order::find($id);
        $buyer = Buyer::find($request->buyer->id);

        return view("buyer.orders.show", [
            "buyer" => $buyer,
            "order" => $order
        ]);
    }

    public function edit(Order $order)
    {
        //
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    public function destroy(Order $order, Request $request, $id){
        $order = Order::find($id);
        $order->delete();

        return redirect()->route('companies.orders.index')->with('success', 'Order deleted successfully');
    }

    public function updateStatus(Order $order, $newStatus)
    {
        $allowedStatuses = Order::statuses();
        if (!in_array($newStatus, $allowedStatuses)) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        $order->update(['status' => $newStatus]);

        return response()->json(['message' => 'Status updated successfully']);
    }
}
