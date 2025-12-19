<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product.photos')->get();
        $total = $cartItems->sum(fn($item) => $item->subtotal);

        return view('client.cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        if ($product->is_archived || $product->stock <= 0) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Товар недоступний для додавання до кошика.'], 422);
                }
                return redirect()->back()->with('error', 'Товар недоступний для додавання до кошика.');
        }

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        $incomingQty = max(1, (int) $request->input('quantity', 1));
        $newQuantity = ($cartItem?->quantity ?? 0) + $incomingQty;
        $clampedQuantity = min($newQuantity, $product->stock);

        if ($cartItem) {
            $cartItem->quantity = $clampedQuantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $clampedQuantity,
            ]);
        }

        $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
        $limited = $newQuantity > $product->stock;

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $limited ? 'Додано доступну кількість (обмежено складом).' : 'Товар додано до кошика',
                'cartCount' => $cartCount,
                'itemQuantity' => $cartItem?->quantity ?? $clampedQuantity,
            ]);
        }

        if ($limited) {
            return redirect()->back()->with('warning', 'Додано доступну кількість товару (обмежено складом).');
        }
        return redirect()->back()->with('success', 'Товар додано до кошика');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) abort(403);

        $request->validate(['quantity' => 'required|integer|min:1']);

        $product = $cartItem->product()->lockForUpdate()->first();

        if (!$product || $product->is_archived || $product->stock <= 0) {
            $cartItem->delete();
            return redirect()->route('cart.index')->with('error', 'Товар недоступний і був видалений з кошика.');
        }

        $desiredQuantity = min($request->quantity, $product->stock);

        if ($desiredQuantity < 1) {
            $cartItem->delete();
            return redirect()->route('cart.index')->with('error', 'Товар недоступний і був видалений з кошика.');
        }

        $cartItem->quantity = $desiredQuantity;
        $cartItem->save();

        $message = $desiredQuantity < $request->quantity
            ? 'Кількість обмежена залишком на складі.'
            : 'Кількість оновлено.';

            if ($request->expectsJson()) {
                $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
                return response()->json([
                    'message' => $message,
                    'cartCount' => $cartCount,
                    'itemQuantity' => $cartItem->quantity,
                ]);
            }

        return redirect()->route('cart.index')->with('success', $message);
    }

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) abort(403);
        $cartItem->delete();

        $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Товар видалено',
                'cartCount' => $cartCount,
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Товар видалено');
    }
}