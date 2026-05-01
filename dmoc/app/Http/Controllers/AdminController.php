<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Courier;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function kpi()
    {
        return view('admin.kpi');
    }

    public function products()
    {
        $products = Product::query()
            ->with('category')
            ->latest()
            ->paginate(12);

        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.products', compact('products', 'categories'));
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_xof' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_digital' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $index = 1;

        while (Product::query()->where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$index}";
            $index++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::query()->create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price_xof' => $validated['price_xof'],
            'category_id' => $validated['category_id'],
            'stock' => $validated['stock'],
            'is_digital' => $request->boolean('is_digital'),
            'image_path' => $imagePath,
            'rating' => 0,
        ]);

        return redirect()
            ->route('admin.products')
            ->with('success', 'Product added successfully.');
    }

    public function updateProduct(Request $request, int $productId): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_xof' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_digital' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $product = Product::query()->findOrFail($productId);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $index = 1;

        while (
            Product::query()
                ->where('slug', $slug)
                ->where('id', '!=', $product->id)
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$index}";
            $index++;
        }

        $imagePath = $product->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price_xof' => $validated['price_xof'],
            'category_id' => $validated['category_id'],
            'stock' => $validated['stock'],
            'is_digital' => $request->boolean('is_digital'),
            'image_path' => $imagePath,
        ]);

        return redirect()
            ->route('admin.products')
            ->with('success', 'Product updated successfully.');
    }

    public function destroyProduct(int $productId): RedirectResponse
    {
        $product = Product::query()->findOrFail($productId);

        if ($product->orderItems()->exists() || $product->cartItems()->exists()) {
            return redirect()
                ->route('admin.products')
                ->with('error', 'Cannot delete this product because it is referenced in orders or carts.');
        }

        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()
            ->route('admin.products')
            ->with('success', 'Product deleted successfully.');
    }

    public function orders(Request $request)
    {
        $status = $request->query('status');
        $q = trim((string) $request->query('q', ''));

        $orders = Order::query()
            ->with(['user', 'zone'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('id', $q)
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('email', 'like', "%{$q}%"))
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', "%{$q}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders', compact('orders', 'status', 'q'));
    }

    public function orderShow(int $orderId)
    {
        $order = Order::query()
            ->with(['user', 'items.product', 'zone', 'payments', 'delivery.courier.user'])
            ->findOrFail($orderId);

        $allowedStatuses = ['pending', 'confirmed', 'preparing', 'shipped', 'delivered'];
        $couriers = Courier::query()
            ->with('user')
            ->where('status', 'active')
            ->orderByDesc('rating')
            ->get();

        return view('admin.order-show', compact('order', 'allowedStatuses', 'couriers'));
    }

    public function updateOrderStatus(Request $request, int $orderId): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,preparing,shipped,delivered'],
        ]);

        $order = Order::query()->findOrFail($orderId);
        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Statut de commande mis a jour.');
    }

    public function assignCourier(Request $request, int $orderId): RedirectResponse
    {
        $validated = $request->validate([
            'courier_id' => ['required', 'integer', 'exists:couriers,id'],
        ]);

        $order = Order::query()->findOrFail($orderId);

        if (! $order->delivery_zone_id) {
            return back()->with('error', 'La commande doit avoir une zone de livraison avant assignation.');
        }

        Delivery::query()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'courier_id' => $validated['courier_id'],
                'zone_id' => $order->delivery_zone_id,
                'status' => 'assigned',
                'assigned_at' => now(),
                'delivery_fee_xof' => (int) ($order->delivery_fee_xof ?? 0),
            ]
        );

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Livreur assigne avec succes.');
    }

    public function couriers()
    {
        return view('admin.couriers');
    }

    public function zones()
    {
        return view('admin.zones');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
