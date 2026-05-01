<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cart = $this->getOrCreateCart($request->user()->id)->load('items.product');

        return response()->json([
            'status' => 'success',
            'data' => [
                'cart' => $cart,
                'summary' => $this->buildSummary($cart),
            ],
        ]);
    }

    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::query()->findOrFail($validated['product_id']);
        $quantity = $validated['quantity'] ?? 1;

        $cart = $this->getOrCreateCart($request->user()->id);
        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->update([
                'quantity' => $item->quantity + $quantity,
                'unit_price_xof' => $product->price_xof,
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price_xof' => $product->price_xof,
            ]);
        }

        return $this->index($request);
    }

    public function updateItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->getOrCreateCart($request->user()->id);
        $item = $cart->items()->where('product_id', $validated['product_id'])->firstOrFail();

        $item->update([
            'quantity' => $validated['quantity'],
        ]);

        return $this->index($request);
    }

    public function remove(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $cart = $this->getOrCreateCart($request->user()->id);
        $cart->items()->where('product_id', $validated['product_id'])->delete();

        return $this->index($request);
    }

    private function getOrCreateCart(int $userId): Cart
    {
        return Cart::query()->firstOrCreate(['user_id' => $userId]);
    }

    private function buildSummary(Cart $cart): array
    {
        $subtotal = $cart->items->sum(fn ($item) => $item->quantity * $item->unit_price_xof);
        $itemsCount = (int) $cart->items->sum('quantity');

        return [
            'items_count' => $itemsCount,
            'subtotal_xof' => $subtotal,
        ];
    }
}
