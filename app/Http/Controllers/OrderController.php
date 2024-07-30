<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;


class OrderController extends Controller
{
    public function index()
    {
        return view('admin.orders.index');
    }
    public function getOrders()
    {
        $orders = Order::query()
        ->select(['id', 'user_id', 'subtotal', 'gst', 'total_price', 'payment_method', 'status', 'created_at', 'updated_at'])
        ->get();

        return response()->json(['data' => $orders]);
    }
    public function getOrdersData(Request $request)
    {
        $orders = Order::query()
        ->select(['id', 'user_id', 'subtotal', 'gst', 'total_price', 'payment_method', 'status', 'created_at', 'updated_at'])
        ->get();

        return Datatables::of($orders)
            ->addColumn('action', function ($order) {
                return '<button class="btn btn-danger btn-sm delete" data-id="' . $order->id . '">Delete</button>';
            })
            ->make(true);
    }
    public function placeOrder(Request $request)
    {
        $user = Auth::user();
        $cart = session()->get('cart', []);
        $subtotal = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
        $gst = $subtotal * 0.18;
        $totalPrice = $subtotal + $gst;

        $order = Order::create([
            'user_id' => $user->id,
            'subtotal' => $subtotal,
            'gst' => $gst,
            'total' => $totalPrice,
            'total_price' => $totalPrice,
            'payment_method' => 'COD',
            'status' => 'Pending',
        ]);

        // Clear the cart
        session()->forget('cart');

        return redirect()->route('home')->with('success', 'Order placed successfully!');
    }
    public function delete($id)
    {
        $order = Order::findOrFail($id);

        if ($request->input('soft')) {
            $order->delete(); // Soft delete
        } else {
            $order->forceDelete(); // Hard delete
        }

        return response()->json(['success' => true]);
    }
}
