<?php

namespace App\Http\Controllers;

use App\Enums\DeliveryStatus;
use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Courier;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderStatusLog;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function kpi()
    {
        $period = request()->query('period', '7d');
        $startDate = request()->date('start_date');
        $endDate = request()->date('end_date');

        [$from, $to] = $this->resolvePeriodRange($period, $startDate, $endDate);
        $cacheKey = "admin.kpi.{$period}.{$from->toDateString()}.{$to->toDateString()}";

        $data = Cache::remember($cacheKey, 300, function () use ($from, $to) {
            $orders = Order::query()->whereBetween('created_at', [$from, $to]);
            $total = (clone $orders)->count();
            $delivered = (clone $orders)->where('status', OrderStatus::Delivered->value)->count();
            $failed = (clone $orders)->where('status', OrderStatus::Failed->value)->count();
            $pendingCount = (clone $orders)->whereIn('status', [OrderStatus::Pending->value, OrderStatus::Confirmed->value])->count();
            $activeCount = (clone $orders)->whereIn('status', [OrderStatus::Assigned->value, OrderStatus::InDelivery->value])->count();
            $expectedCod = (clone $orders)->where('status', OrderStatus::Delivered->value)->sum('total_xof');
            $totalSales = (clone $orders)->sum('total_xof');
            $collectedCod = Delivery::query()
                ->whereBetween('updated_at', [$from, $to])
                ->where('status', DeliveryStatus::Delivered->value)
                ->sum('amount_received');
            $usersCount = User::query()->count();
            $couriersCount = Courier::query()->count();
            $deliveriesCount = Delivery::query()->whereBetween('created_at', [$from, $to])->count();

            $ordersTrend = Order::query()
                ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
                ->whereBetween('created_at', [$from, $to])
                ->groupBy('day')
                ->orderBy('day')
                ->get();

            $statusDistribution = Order::query()
                ->selectRaw('status, COUNT(*) as total')
                ->whereBetween('created_at', [$from, $to])
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $courierPerformance = Courier::query()
                ->selectRaw('couriers.id, users.name as courier_name, COUNT(deliveries.id) as success_count')
                ->join('users', 'users.id', '=', 'couriers.user_id')
                ->leftJoin('deliveries', function ($join) use ($from, $to) {
                    $join->on('deliveries.courier_id', '=', 'couriers.id')
                        ->where('deliveries.status', DeliveryStatus::Delivered->value)
                        ->whereBetween('deliveries.updated_at', [$from, $to]);
                })
                ->groupBy('couriers.id', 'users.name')
                ->orderByDesc('success_count')
                ->limit(8)
                ->get();

            $topProducts = DB::table('order_items')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'products.id', '=', 'order_items.product_id')
                ->selectRaw('products.name, SUM(order_items.quantity) as total_qty')
                ->whereBetween('orders.created_at', [$from, $to])
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_qty')
                ->limit(5)
                ->get();

            $recentOrders = Order::query()
                ->with(['user', 'delivery.courier.user'])
                ->latest()
                ->limit(10)
                ->get();

            $pending24h = Order::query()
                ->where('status', OrderStatus::Pending->value)
                ->where('created_at', '<=', now()->subDay())
                ->count();
            $idleCouriers = Courier::query()
                ->where('status', 'active')
                ->whereDoesntHave('deliveries', fn ($query) => $query->whereIn('status', [DeliveryStatus::Assigned->value, DeliveryStatus::InDelivery->value]))
                ->count();
            $failedUnprocessed = Order::query()->where('status', OrderStatus::Failed->value)->count();

            return compact(
                'total',
                'delivered',
                'failed',
                'pendingCount',
                'activeCount',
                'expectedCod',
                'totalSales',
                'collectedCod',
                'usersCount',
                'couriersCount',
                'deliveriesCount',
                'ordersTrend',
                'statusDistribution',
                'courierPerformance',
                'topProducts',
                'recentOrders',
                'pending24h',
                'idleCouriers',
                'failedUnprocessed'
            );
        });

        return view('admin.kpi', compact('data', 'period', 'from', 'to'));
    }

    public function products(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $categoryId = $request->query('category_id');
        $active = $request->query('active');
        $supportsActiveStatus = Schema::hasColumn('products', 'is_active');

        $products = Product::query()
            ->with('category')
            ->when($q !== '', fn ($query) => $query->where(fn ($sub) => $sub
                ->where('name', 'like', "%{$q}%")
                ->orWhere('slug', 'like', "%{$q}%")))
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($supportsActiveStatus && $active !== null && $active !== '', fn ($query) => $query->where('is_active', $active))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return view('admin.products', compact('products', 'categories', 'q', 'categoryId', 'active', 'supportsActiveStatus'));
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_xof' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_digital' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
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

        $payload = [
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price_xof' => (int) round((float) $validated['price_xof']),
            'category_id' => $validated['category_id'],
            'stock' => $validated['stock'],
            'is_digital' => $request->boolean('is_digital'),
            'image_path' => $imagePath,
            'rating' => 0,
        ];
        if (Schema::hasColumn('products', 'is_active')) {
            $payload['is_active'] = $request->boolean('is_active', true);
        }

        Product::query()->create($payload);

        return redirect()
            ->route('admin.products')
            ->with('success', 'Product added successfully.');
    }

    public function updateProduct(Request $request, int $productId): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_xof' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_digital' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
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

        $payload = [
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price_xof' => (int) round((float) $validated['price_xof']),
            'category_id' => $validated['category_id'],
            'stock' => $validated['stock'],
            'is_digital' => $request->boolean('is_digital'),
            'image_path' => $imagePath,
        ];
        if (Schema::hasColumn('products', 'is_active')) {
            $payload['is_active'] = $request->boolean('is_active');
        }

        $product->update($payload);

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
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $orders = Order::query()
            ->with(['user', 'zone', 'delivery.courier.user'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('id', $q)
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('email', 'like', "%{$q}%"))
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', "%{$q}%"));
                });
            })
            ->when($fromDate, fn ($query) => $query->whereDate('created_at', '>=', $fromDate))
            ->when($toDate, fn ($query) => $query->whereDate('created_at', '<=', $toDate))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $statuses = OrderStatus::cases();
        $activeCouriers = Courier::query()
            ->with('user')
            ->where('status', 'active')
            ->orderBy('id')
            ->get();

        return view('admin.orders', compact('orders', 'status', 'q', 'fromDate', 'toDate', 'statuses', 'activeCouriers'));
    }

    public function orderShow(int $orderId)
    {
        $order = Order::query()
            ->with(['user', 'items.product', 'zone', 'payments', 'delivery.courier.user', 'statusLogs.actor'])
            ->findOrFail($orderId);

        $allowedStatuses = array_map(
            static fn (OrderStatus $status) => $status->value,
            OrderStatus::tryFrom($order->status)?->transitions() ?? []
        );
        $couriers = Courier::query()
            ->with('user')
            ->where('status', 'active')
            ->withCount([
                'deliveries as missions_in_progress' => fn ($query) => $query->whereIn('status', [DeliveryStatus::Assigned->value, DeliveryStatus::InDelivery->value]),
            ])
            ->orderByDesc('rating')
            ->get();

        return view('admin.order-show', compact('order', 'allowedStatuses', 'couriers'));
    }

    public function updateOrderStatus(Request $request, int $orderId): RedirectResponse
    {
        $order = Order::query()->findOrFail($orderId);
        $allowedTransitions = array_map(
            static fn (OrderStatus $status) => $status->value,
            OrderStatus::tryFrom($order->status)?->transitions() ?? []
        );

        if ($allowedTransitions === []) {
            return back()->with('error', 'Aucune transition de statut autorisee depuis cet etat.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', $allowedTransitions)],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($order, $validated, $request): void {
            $fromStatus = $order->status;
            $order->update([
                'status' => $validated['status'],
            ]);

            OrderStatusLog::query()->create([
                'order_id' => $order->id,
                'from_status' => $fromStatus,
                'to_status' => $validated['status'],
                'changed_by' => $request->user()?->id,
                'note' => $validated['note'] ?? null,
            ]);
        });

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Statut de commande mis a jour.');
    }

    public function assignCourier(Request $request, int $orderId): RedirectResponse
    {
        $validated = $request->validate([
            'courier_id' => ['required', 'integer', 'exists:couriers,id'],
        ]);

        $order = Order::query()->with('delivery')->findOrFail($orderId);
        $courier = Courier::query()->findOrFail($validated['courier_id']);

        if (! $order->delivery_zone_id) {
            return back()->with('error', 'La commande doit avoir une zone de livraison avant assignation.');
        }

        if (! in_array(OrderStatus::Assigned->value, $this->allowedOrderTransitions($order->status), true) && $order->status !== OrderStatus::Assigned->value) {
            return back()->with('error', 'Assignation autorisee uniquement depuis une commande confirmee/assignee.');
        }

        if ($courier->status !== 'active') {
            return back()->with('error', 'Ce livreur est inactif et ne peut pas recevoir de mission.');
        }

        DB::transaction(function () use ($order, $courier, $request): void {
            $delivery = Delivery::query()->updateOrCreate(
                ['order_id' => $order->id],
                [
                    'courier_id' => $courier->id,
                    'zone_id' => $order->delivery_zone_id,
                    'status' => DeliveryStatus::Assigned->value,
                    'assigned_at' => now(),
                    'delivery_fee_xof' => (int) ($order->delivery_fee_xof ?? 0),
                ]
            );

            $fromStatus = $order->status;
            $order->update(['status' => OrderStatus::Assigned->value]);

            OrderStatusLog::query()->create([
                'order_id' => $order->id,
                'from_status' => $fromStatus,
                'to_status' => OrderStatus::Assigned->value,
                'changed_by' => $request->user()?->id,
                'note' => $delivery->wasRecentlyCreated ? 'Livreur assigne.' : 'Livreur reassigne.',
            ]);
        });

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Livreur assigne avec succes.');
    }

    public function cancelOrder(Request $request, int $orderId): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);
        $order = Order::query()->findOrFail($orderId);
        $allowed = $this->allowedOrderTransitions($order->status);
        if (! in_array(OrderStatus::Cancelled->value, $allowed, true)) {
            return back()->with('error', 'Annulation impossible depuis ce statut.');
        }

        DB::transaction(function () use ($order, $validated, $request): void {
            $fromStatus = $order->status;
            $order->update([
                'status' => OrderStatus::Cancelled->value,
                'cancelled_reason' => $validated['reason'],
            ]);
            OrderStatusLog::query()->create([
                'order_id' => $order->id,
                'from_status' => $fromStatus,
                'to_status' => OrderStatus::Cancelled->value,
                'changed_by' => $request->user()?->id,
                'note' => $validated['reason'],
            ]);
        });

        return back()->with('success', 'Commande annulee avec succes.');
    }

    public function categories()
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'slug' => ['nullable', 'string', 'max:255'],
        ]);

        Category::query()->create([
            'name' => $validated['name'],
            'slug' => $this->uniqueCategorySlug($validated['slug'] ?: $validated['name']),
        ]);

        return back()->with('success', 'Categorie creee avec succes.');
    }

    public function updateCategory(Request $request, int $categoryId): RedirectResponse
    {
        $category = Category::query()->findOrFail($categoryId);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,'.$category->id],
            'slug' => ['nullable', 'string', 'max:255'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => $this->uniqueCategorySlug($validated['slug'] ?: $validated['name'], $category->id),
        ]);

        return back()->with('success', 'Categorie mise a jour.');
    }

    public function destroyCategory(int $categoryId): RedirectResponse
    {
        $category = Category::query()->withCount('products')->findOrFail($categoryId);
        if ($category->products_count > 0) {
            return back()->with('error', 'Suppression impossible: des produits sont lies a cette categorie.');
        }

        $category->delete();

        return back()->with('success', 'Categorie supprimee.');
    }

    public function couriers(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $status = $request->query('status');

        // Ensure every "livreur" user has a courier profile for admin visibility.
        User::query()
            ->where('role', User::ROLE_COURIER)
            ->whereDoesntHave('courier')
            ->orderBy('id')
            ->get()
            ->each(function (User $user): void {
                Courier::query()->create([
                    'user_id' => $user->id,
                    'license_number' => 'AUTO-LIC-'.Str::upper(Str::random(8)),
                    'vehicle_type' => 'moto',
                    'vehicle_plate' => 'AUTO-'.Str::upper(Str::random(6)),
                    'delivery_zone' => null,
                    'status' => 'active',
                    'rating' => 5,
                ]);
            });

        $couriers = Courier::query()
            ->with('user')
            ->withCount([
                'deliveries as missions_in_progress' => fn ($query) => $query->whereIn('status', [DeliveryStatus::Assigned->value, DeliveryStatus::InDelivery->value]),
                'deliveries as missions_completed' => fn ($query) => $query->where('status', DeliveryStatus::Delivered->value),
                'deliveries as missions_failed' => fn ($query) => $query->where('status', DeliveryStatus::Failed->value),
            ])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($q !== '', fn ($query) => $query->whereHas('user', fn ($sub) => $sub->where('name', 'like', "%{$q}%")))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.couriers', compact('couriers', 'q', 'status'));
    }

    public function storeCourier(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8'],
            'status' => ['required', 'in:active,inactive'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('couriers', 'public');
        }

        $courierId = DB::transaction(function () use ($validated, $photoPath): int {
            $user = User::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'role' => User::ROLE_COURIER,
                'password' => Hash::make($validated['password']),
            ]);

            $courierPayload = [
                'user_id' => $user->id,
                'license_number' => 'LIC-'.Str::upper(Str::random(8)),
                'vehicle_type' => 'moto',
                'vehicle_plate' => 'TMP-'.Str::upper(Str::random(6)),
                'delivery_zone' => null,
                'status' => $validated['status'],
                'rating' => 5,
            ];
            if (Schema::hasColumn('couriers', 'profile_photo_path')) {
                $courierPayload['profile_photo_path'] = $photoPath;
            }

            $courier = Courier::query()->create($courierPayload);

            return $courier->id;
        });

        return redirect()
            ->route('admin.couriers')
            ->with('success', 'Livreur cree avec succes et visible dans la liste admin.')
            ->with('new_courier_id', $courierId);
    }

    public function updateCourier(Request $request, int $courierId): RedirectResponse
    {
        $courier = Courier::query()->with('user')->findOrFail($courierId);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$courier->user_id],
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8'],
            'delivery_zone' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $photoPath = $courier->profile_photo_path;
        if ($request->hasFile('profile_photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('profile_photo')->store('couriers', 'public');
        }

        DB::transaction(function () use ($courier, $validated): void {
            $courier->user?->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => $validated['password'] ? Hash::make($validated['password']) : $courier->user->password,
            ]);

            $courier->update([
                'delivery_zone' => $validated['delivery_zone'] ?? null,
                'status' => $validated['status'],
            ]);
        });

        if (Schema::hasColumn('couriers', 'profile_photo_path')) {
            $courier->update(['profile_photo_path' => $photoPath]);
        }

        return back()->with('success', 'Livreur mis a jour.');
    }

    public function toggleCourierStatus(int $courierId): RedirectResponse
    {
        $courier = Courier::query()->findOrFail($courierId);
        $nextStatus = $courier->status === 'active' ? 'inactive' : 'active';
        $courier->update(['status' => $nextStatus]);

        $warning = $nextStatus === 'inactive'
            ? 'Le livreur est desactive. Les missions en cours ne sont pas reaffectees automatiquement.'
            : 'Livreur reactive.';

        return back()->with('success', $warning);
    }

    public function courierShow(int $courierId)
    {
        $courier = Courier::query()
            ->with(['user', 'deliveries.order.user'])
            ->findOrFail($courierId);

        $missions = Delivery::query()
            ->with('order.user')
            ->where('courier_id', $courier->id)
            ->latest()
            ->paginate(15);

        $stats = [
            'delivered' => Delivery::query()->where('courier_id', $courier->id)->where('status', DeliveryStatus::Delivered->value)->count(),
            'failed' => Delivery::query()->where('courier_id', $courier->id)->where('status', DeliveryStatus::Failed->value)->count(),
            'cod' => Delivery::query()->where('courier_id', $courier->id)->where('status', DeliveryStatus::Delivered->value)->sum('amount_received'),
        ];

        return view('admin.courier-show', compact('courier', 'missions', 'stats'));
    }

    public function zones()
    {
        $zones = Zone::query()->withCount('orders')->orderBy('name')->paginate(15);

        return view('admin.zones', compact('zones'));
    }

    public function storeZone(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:zones,name'],
            'base_tariff_xof' => ['required', 'integer', 'min:0'],
            'per_kg_xof' => ['required', 'integer', 'min:0'],
        ]);

        Zone::query()->create([
            'name' => $validated['name'],
            'base_tariff_xof' => $validated['base_tariff_xof'],
            'per_kg_xof' => $validated['per_kg_xof'],
            'country' => 'BJ',
        ]);

        return back()->with('success', 'Zone creee.');
    }

    public function updateZone(Request $request, int $zoneId): RedirectResponse
    {
        $zone = Zone::query()->findOrFail($zoneId);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:zones,name,'.$zone->id],
            'base_tariff_xof' => ['required', 'integer', 'min:0'],
            'per_kg_xof' => ['required', 'integer', 'min:0'],
        ]);

        $zone->update($validated);

        return back()->with('success', 'Zone mise a jour.');
    }

    public function destroyZone(int $zoneId): RedirectResponse
    {
        $zone = Zone::query()->withCount('orders')->findOrFail($zoneId);
        if ($zone->orders_count > 0) {
            return back()->with('error', 'Suppression impossible: cette zone est utilisee dans des commandes.');
        }
        $zone->delete();

        return back()->with('success', 'Zone supprimee.');
    }

    public function settings()
    {
        $settings = Setting::query()->pluck('value', 'key');
        $zones = Zone::query()->orderBy('name')->get();

        return view('admin.settings', compact('settings', 'zones'));
    }

    public function saveSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_email' => ['required', 'email', 'max:255'],
            'shop_phone' => ['required', 'string', 'max:30'],
            'shop_address' => ['required', 'string', 'max:1000'],
            'default_delivery_fee_xof' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }

        return back()->with('success', 'Parametres sauvegardes.');
    }

    private function uniqueCategorySlug(string $rawSlug, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($rawSlug);
        $slug = $baseSlug;
        $index = 1;
        while (Category::query()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$baseSlug}-{$index}";
            $index++;
        }

        return $slug;
    }

    private function allowedOrderTransitions(string $status): array
    {
        return array_map(
            static fn (OrderStatus $next) => $next->value,
            OrderStatus::tryFrom($status)?->transitions() ?? []
        );
    }

    private function resolvePeriodRange(string $period, $startDate, $endDate): array
    {
        $now = now();
        return match ($period) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            '30d' => [$now->copy()->subDays(29)->startOfDay(), $now->copy()->endOfDay()],
            'month' => [$now->copy()->startOfMonth(), $now->copy()->endOfDay()],
            'custom' => [($startDate ?? $now->copy()->startOfDay()), ($endDate ?? $now->copy()->endOfDay())],
            default => [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()],
        };
    }
}
