<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function create()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('catalog.index')->with('error', 'Ваш кошик порожній');
        }

        $total = $cartItems->sum(fn($item) => $item->subtotal);
        return view('client.checkout.create', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('catalog.index')->with('error', 'Кошик порожній');
        }

        $unavailable = $cartItems->filter(function ($item) {
            return !$item->product || $item->product->is_archived || $item->product->stock < $item->quantity;
        });

        if ($unavailable->isNotEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Деякі товари недоступні або закінчилися. Оновіть кошик перед оформленням.');
        }

        try {
            DB::transaction(function () use ($user, $request) {
                $cartItems = $user->cartItems()->with(['product' => function ($query) {
                    $query->lockForUpdate();
                }])->get();

                $total = $cartItems->sum(fn($item) => $item->subtotal);
                
                $orderNumber = 'ORD-' . date('YmdHis') . '-' . Auth::id();

                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => $orderNumber,
                    'status' => 'pending',
                    'total_amount' => $total,
                    'shipping_address' => $request->shipping_address,
                    'notes' => $request->notes,
                ]);

                foreach ($cartItems as $item) {
                    if ($item->product->stock < $item->quantity) {
                        throw new \Exception('Недостатньо товару на складі для: ' . $item->product->name);
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_price' => $item->product->price,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->subtotal,
                    ]);

                    $item->product->decrement('stock', $item->quantity);
                }

                $user->cartItems()->delete();
            });
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        return redirect()->route('my-orders.index')->with('success', 'Замовлення успішно створено! Дякуємо за ваш заказ.');
    }
}