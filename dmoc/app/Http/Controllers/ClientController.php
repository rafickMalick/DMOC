<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Zone;
use App\Services\OrderStatusService;
use App\Services\Payments\PaymentProviderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
    public function __construct(
        private readonly PaymentProviderService $paymentProviderService,
        private readonly OrderStatusService $orderStatusService
    ) {
    }

    public function home(): View
    {
        $featuredProducts = Product::query()
            ->with('category')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $categories = Category::query()
            ->withCount('products')
            ->orderByDesc('products_count')
            ->limit(6)
            ->get();

        return view('client.home', compact('featuredProducts', 'categories'));
    }

    public function catalog(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'category' => ['nullable', 'array'],
            'category.*' => ['integer', 'exists:categories,id'],
            'min_price' => ['nullable', 'integer', 'min:0'],
            'max_price' => ['nullable', 'integer', 'min:0'],
            'sort' => ['nullable', 'in:relevance,price_asc,price_desc,newest,rating'],
        ]);

        $search = $validated['search'] ?? null;
        $selectedCategories = array_map('intval', $validated['category'] ?? []);
        $sort = $validated['sort'] ?? 'relevance';

        $productsQuery = Product::query()->with('category');

        if ($search) {
            $productsQuery->where(function ($query) use ($search): void {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        if ($selectedCategories !== []) {
            $productsQuery->whereIn('category_id', $selectedCategories);
        }

        if (isset($validated['min_price'])) {
            $productsQuery->where('price_xof', '>=', (int) $validated['min_price']);
        }

        if (isset($validated['max_price'])) {
            $productsQuery->where('price_xof', '<=', (int) $validated['max_price']);
        }

        match ($sort) {
            'price_asc' => $productsQuery->orderBy('price_xof'),
            'price_desc' => $productsQuery->orderByDesc('price_xof'),
            'newest' => $productsQuery->orderByDesc('created_at'),
            'rating' => $productsQuery->orderByDesc('rating'),
            default => $productsQuery->orderByDesc('created_at'),
        };

        $products = $productsQuery->paginate(12)->withQueryString();
        $categories = Category::query()->withCount('products')->orderBy('name')->get();
        $maxPrice = (int) Product::query()->max('price_xof');

        return view('client.catalog', compact('products', 'categories', 'selectedCategories', 'sort', 'search', 'maxPrice'));
    }

    public function product(Request $request, ?string $slug = null): View
    {
        $product = Product::query()
            ->with(['category', 'variants', 'reviews.user'])
            ->when(
                $slug,
                fn ($query) => $query->where('slug', $slug),
                fn ($query) => $query->whereKey($request->integer('id'))
            )
            ->firstOrFail();

        $relatedProducts = Product::query()
            ->with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderByDesc('rating')
            ->limit(4)
            ->get();

        return view('client.product', compact('product', 'relatedProducts'));
    }

    public function cart(): View
    {
        $cart = Cart::query()
            ->with(['items.product'])
            ->where('user_id', auth()->id())
            ->first();

        $subtotal = (int) ($cart?->items->sum(fn ($item) => $item->quantity * $item->unit_price_xof) ?? 0);
        $itemsCount = (int) ($cart?->items->sum('quantity') ?? 0);
        $promo = session('cart_promo');
        $discount = 0;

        if ($promo && $subtotal > 0) {
            $discount = $promo['type'] === 'percent'
                ? (int) floor($subtotal * ($promo['value'] / 100))
                : (int) $promo['value'];
        }

        $total = max($subtotal - $discount, 0);

        return view('client.cart', compact('cart', 'subtotal', 'itemsCount', 'discount', 'total', 'promo'));
    }

    public function checkout()
    {
        $requestedOrderId = request()->integer('order');

        $cart = Cart::query()
            ->with(['items.product'])
            ->where('user_id', auth()->id())
            ->first();

        $zones = Zone::query()->orderBy('name')->get();

        $orderQuery = Order::query()
            ->with(['items.product', 'zone', 'payments'])
            ->where('user_id', auth()->id())
            ->where('status', Order::STATUS_PENDING);

        if ($requestedOrderId) {
            $orderQuery->where('id', $requestedOrderId);
        }

        $order = $orderQuery->latest()->first();

        $subtotal = (int) ($cart?->items->sum(fn ($item) => $item->quantity * $item->unit_price_xof) ?? 0);
        $hasStepOne = (bool) $order;
        $hasStepTwo = $hasStepOne && ! empty($order->payment_method);
        $canConfirm = $hasStepTwo;

        return view('client.checkout', compact('cart', 'zones', 'order', 'subtotal', 'hasStepOne', 'hasStepTwo', 'canConfirm'));
    }

    public function addToCart(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $product = Product::query()->findOrFail($validated['product_id']);
        $quantity = (int) ($validated['quantity'] ?? 1);
        $cart = Cart::query()->firstOrCreate(['user_id' => $request->user()->id]);
        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->update([
                'quantity' => min($item->quantity + $quantity, 99),
                'unit_price_xof' => $product->price_xof,
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price_xof' => $product->price_xof,
            ]);
        }

        return back()->with('success', 'Produit ajoute au panier.');
    }

    public function updateCartItem(Request $request, int $itemId): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = Cart::query()
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $item = $cart->items()->whereKey($itemId)->firstOrFail();
        $item->update(['quantity' => (int) $validated['quantity']]);

        return back()->with('success', 'Quantite mise a jour.');
    }

    public function removeCartItem(Request $request, int $itemId): RedirectResponse
    {
        $cart = Cart::query()
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $cart->items()->whereKey($itemId)->delete();

        return back()->with('success', 'Article retire du panier.');
    }

    public function applyPromoCode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'promo_code' => ['required', 'string', 'max:30'],
        ]);

        $code = Str::upper(trim($validated['promo_code']));
        $promos = [
            'dmoc5' => ['type' => 'percent', 'value' => 5],
            'WELCOME10' => ['type' => 'percent', 'value' => 10],
            'SHIP1000' => ['type' => 'fixed', 'value' => 1000],
        ];

        if (! isset($promos[$code])) {
            return back()->withErrors(['promo_code' => 'Code promo invalide.']);
        }

        session(['cart_promo' => ['code' => $code] + $promos[$code]]);

        return back()->with('success', 'Code promo applique.');
    }

    public function removePromoCode(): RedirectResponse
    {
        session()->forget('cart_promo');

        return back()->with('success', 'Code promo retire.');
    }

    public function searchSuggestions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:120'],
        ]);

        $results = Product::query()
            ->where('name', 'like', '%'.$validated['q'].'%')
            ->orderByDesc('rating')
            ->limit(6)
            ->get(['id', 'name', 'slug']);

        return response()->json([
            'status' => 'success',
            'data' => $results,
        ]);
    }

    public function checkoutStepOne(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'zone_id' => ['required', 'integer', 'exists:zones,id'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'shipping_phone' => ['required', 'string', 'max:30'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'weight_kg' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $cart = Cart::query()
            ->with('items.product')
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        $zone = Zone::query()->findOrFail($validated['zone_id']);
        $weightKg = (float) ($validated['weight_kg'] ?? 1);
        $subtotal = (int) $cart->items->sum(fn ($item) => $item->quantity * $item->unit_price_xof);
        $deliveryFee = (int) ($zone->base_tariff_xof + ($zone->per_kg_xof * $weightKg));
        $total = $subtotal + $deliveryFee;

        try {
            $order = DB::transaction(function () use ($request, $cart, $validated, $zone, $deliveryFee, $total) {
                foreach ($cart->items as $cartItem) {
                    if (! $cartItem->product || $cartItem->product->stock < $cartItem->quantity) {
                        throw new \RuntimeException('Stock insuffisant pour un ou plusieurs produits.');
                    }
                }

                $order = Order::query()
                    ->where('user_id', $request->user()->id)
                    ->where('status', Order::STATUS_PENDING)
                    ->latest()
                    ->first();

                if ($order) {
                    $order->items()->delete();
                    $order->update([
                        'total_xof' => $total,
                        'delivery_fee_xof' => $deliveryFee,
                        'delivery_zone_id' => $zone->id,
                        'estimated_delivery' => now()->addDays(2),
                        'shipping_address' => $validated['shipping_address'],
                        'shipping_phone' => $validated['shipping_phone'],
                        'recipient_name' => $validated['recipient_name'],
                        'notes' => $validated['notes'] ?? null,
                    ]);
                } else {
                    $order = Order::query()->create([
                        'user_id' => $request->user()->id,
                        'total_xof' => $total,
                        'delivery_fee_xof' => $deliveryFee,
                        'status' => Order::STATUS_PENDING,
                        'delivery_zone_id' => $zone->id,
                        'estimated_delivery' => now()->addDays(2),
                        'shipping_address' => $validated['shipping_address'],
                        'shipping_phone' => $validated['shipping_phone'],
                        'recipient_name' => $validated['recipient_name'],
                        'notes' => $validated['notes'] ?? null,
                    ]);
                }

                foreach ($cart->items as $cartItem) {
                    $order->items()->create([
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'price_xof' => $cartItem->unit_price_xof,
                    ]);
                }

                return $order;
            });
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', $e->getMessage() ?: 'Erreur lors de l etape 1.');
        }

        return redirect()
            ->route('client.checkout', ['order' => $order->id])
            ->with('success', 'Step 1 completed. Continue with payment method.');
    }

    public function checkoutStepTwo(Request $request, int $orderId): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:cinet,stripe,paypal,cod'],
        ]);

        $order = Order::query()
            ->where('id', $orderId)
            ->where('user_id', $request->user()->id)
            ->where('status', Order::STATUS_PENDING)
            ->firstOrFail();

        $payment = Payment::query()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'method' => $validated['payment_method'],
                'amount_xof' => $order->total_xof,
                'status' => 'pending',
                'reference' => 'dmoc-PAY-'.Str::upper(Str::random(12)),
                'response_data' => ['source' => 'web_checkout_step_two'],
            ]
        );
        $order->update(['payment_method' => $validated['payment_method']]);

        $providerData = $this->paymentProviderService->initiate($payment);
        if (($providerData['transaction_id'] ?? null) !== null) {
            $payment->update([
                'transaction_id' => $providerData['transaction_id'],
                'status' => $providerData['status'] ?? 'processing',
                'response_data' => array_merge($payment->response_data ?? [], ['provider' => $providerData]),
            ]);
        }

        return redirect()
            ->route('client.checkout', ['order' => $order->id])
            ->with('success', 'Step 2 completed. You can now confirm your order.');
    }

    public function checkoutConfirm(Request $request, int $orderId): RedirectResponse
    {
        $order = Order::query()
            ->with('payments')
            ->where('id', $orderId)
            ->where('user_id', $request->user()->id)
            ->where('status', Order::STATUS_PENDING)
            ->firstOrFail();

        $payment = $order->payments->sortByDesc('id')->first();
        if (! $payment) {
            return back()->with('error', 'Select a payment method first.');
        }

        DB::transaction(function () use ($request, $order, $payment) {
            if ($payment->method === 'cod') {
                $payment->update([
                    'status' => 'success',
                    'response_data' => ['source' => 'web_checkout_confirm', 'message' => 'COD confirmed'],
                ]);
                $this->orderStatusService->transition(
                    $order,
                    Order::STATUS_CONFIRMED,
                    $request->user()->id,
                    'checkout_web',
                    'Commande confirmee en COD.'
                );
            } else {
                $payment->update([
                    'status' => 'processing',
                    'response_data' => ['source' => 'web_checkout_confirm', 'message' => 'Waiting provider confirmation'],
                ]);
            }

            $cart = Cart::query()->where('user_id', $request->user()->id)->first();
            if ($cart) {
                $cart->items()->delete();
            }
        });

        return redirect()
            ->route('client.confirmation')
            ->with('success', 'Order confirmed successfully.')
            ->with('last_order_id', $order->id);
    }

    public function tracking()
    {
        return view('client.tracking');
    }

    public function support(): View
    {
        return view('client.support');
    }

    public function confirmation()
    {
        $orderId = session('last_order_id');
        $order = null;

        if ($orderId && auth()->check()) {
            $order = Order::query()
                ->with('payments')
                ->where('id', $orderId)
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('client.confirmation', compact('order'));
    }

    public function auth()
    {
        return view('client.auth');
    }

    public function dashboard()
    {
        return view('client.dashboard');
    }

    public function orders(Request $request)
    {
        $orders = Order::query()
            ->with('zone')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('client.orders', compact('orders'));
    }

    public function orderShow(Request $request, int $orderId)
    {
        $order = Order::query()
            ->with(['items.product', 'zone', 'payments', 'statusHistory.actor'])
            ->where('id', $orderId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return view('client.order-show', compact('order'));
    }

    public function wishlist()
    {
        return view('client.wishlist');
    }

    public function profile()
    {
        return view('client.profile');
    }
}
