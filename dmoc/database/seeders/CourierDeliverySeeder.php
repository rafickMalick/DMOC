<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Courier;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Seeder;

class CourierDeliverySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $livreur = User::query()->where('email', 'livreur@dmoc.test')->first();
        $client = User::query()->where('email', 'client@dmoc.test')->first();
        $zone = Zone::query()->first();

        $category = Category::query()->firstOrCreate(
            ['slug' => 'demo-category'],
            ['name' => 'Demo Category', 'description' => 'Categorie de demonstration']
        );

        $product = Product::query()->firstOrCreate(
            ['slug' => 'produit-demo-livreur'],
            [
                'name' => 'Produit Demo Livreur',
                'description' => 'Produit de demo pour test livraison',
                'price_xof' => 11000,
                'category_id' => $category->id,
                'stock' => 100,
                'is_digital' => false,
            ]
        );

        if (! $livreur || ! $client || ! $zone || ! $product) {
            return;
        }

        $courier = Courier::query()->firstOrCreate(
            ['user_id' => $livreur->id],
            [
                'license_number' => 'LIC-DMOC-001',
                'vehicle_type' => 'Moto',
                'vehicle_plate' => 'BJ-TR-0001',
                'status' => 'active',
                'rating' => 5.0,
                'completed_deliveries' => 0,
            ]
        );

        $order = Order::query()->firstOrCreate(
            [
                'user_id' => $client->id,
                'total_xof' => 12500,
                'status' => 'confirmed',
                'payment_method' => 'cod',
                'delivery_zone_id' => $zone->id,
            ],
            [
                'delivery_fee_xof' => 1500,
                'shipping_address' => 'Cotonou, quartier Ganhi',
                'shipping_phone' => '+22997000000',
                'recipient_name' => 'Client Demo',
                'estimated_delivery' => now()->addDay(),
            ]
        );

        OrderItem::query()->firstOrCreate(
            [
                'order_id' => $order->id,
                'product_id' => $product->id,
            ],
            [
                'quantity' => 1,
                'price_xof' => 11000,
            ]
        );

        Delivery::query()->firstOrCreate(
            ['order_id' => $order->id],
            [
                'courier_id' => $courier->id,
                'zone_id' => $zone->id,
                'status' => 'assigned',
                'delivery_fee_xof' => 1500,
                'assigned_at' => now(),
            ]
        );

        Cart::query()->firstOrCreate(['user_id' => $client->id]);
    }
}
