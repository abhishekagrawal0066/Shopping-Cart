<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Order;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add($id)
    {
        $product = Product::find($id);
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                // "image" => $product->images->first()->path
            ];
        }
        session()->put('cart', $cart);
        return redirect()->route('cart.index');
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart');
        $id = $request->input('id');
        $quantity = $request->input('quantity');
        
        if(isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart');
        $id = $request->input('id');
        
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Item removed!');
    }

    // public function checkout(Request $request)
    // {
    //     if (!auth()->check()) {
    //         return redirect()->route('login')->with('error', 'You must be logged in to place an order.');
    //     }

    //     $cart = session()->get('cart', []);
    //     if (empty($cart)) {
    //         return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
    //     }

    //     $subtotal = array_sum(array_map(function($item) {
    //         return $item['price'] * $item['quantity'];
    //     }, $cart));

    //     $gst = $subtotal * 0.18;
    //     $totalWithGst = $subtotal + $gst;

    //     // Create the order
    //     $order = new Order();
    //     $order->user_id = auth()->id();
    //     $order->subtotal = $subtotal;
    //     $order->gst = $gst;
    //     $order->total_price = $totalWithGst;
    //     $order->payment_method = 'COD';
    //     $order->status = 'Pending';
    //     $order->save();

    //     // Save order items
    //     foreach ($cart as $id => $details) {
    //         $order->items()->create([
    //             'product_id' => $id,
    //             'quantity' => $details['quantity'],
    //             'price' => $details['price'],
    //             'total' => $details['quantity'] * $details['price']
    //         ]);
    //     }

    //     // Clear the cart
    //     session()->forget('cart');

    //     return redirect()->route('cart.index')->with('success', 'Order placed successfully! Your order will be processed soon.');
    // }

    public function checkout(Request $request)
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to place an order.');
        }
    
        // Get cart from session
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
    
        // Calculate subtotal, GST, and total
        $subtotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
    
        $gst = $subtotal * 0.18;
        $totalWithGst = $subtotal + $gst;
    
        // Validate payment method
        $validatedData = $request->validate([
            'payment_method' => 'required|string|in:COD', // Add other payment methods if needed
        ]);
    
        // Create the order
        $order = new Order();
        $order->user_id = auth()->id();
        $order->subtotal = $subtotal;
        $order->gst = $gst;
        $order->total_price = $totalWithGst;
        $order->payment_method = $validatedData['payment_method']; // Use the selected payment method
        $order->status = 'Pending';
        $order->save();
    
        // Save order items
        foreach ($cart as $id => $details) {
            $order->items()->create([
                'product_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
                'total' => $details['quantity'] * $details['price']
            ]);
        }
    
        // Clear the cart
        session()->forget('cart');
    
        return redirect()->route('cart.index')->with('success', 'Order placed successfully! Your order will be processed soon.');
    }
    
    // public function showCheckoutPage()
    // {
    //     $cart = session()->get('cart', []);
    //     $subtotal = array_sum(array_map(function($item) {
    //         return $item['price'] * $item['quantity'];
    //     }, $cart));

    //     $gst = $subtotal * 0.18;
    //     $totalWithGst = $subtotal + $gst;

    //     return view('cart.checkout', compact('cart', 'subtotal', 'gst', 'totalWithGst'));
    // }

    public function showCheckoutPage()
    {
        // Get cart from session
        $cart = session()->get('cart', []);

        // Calculate subtotal, GST, and total
        $subtotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $gst = $subtotal * 0.18;
        $totalWithGst = $subtotal + $gst;

        // Return view with necessary data
        return view('cart.checkout', compact('cart', 'subtotal', 'gst', 'totalWithGst'));
    }
}