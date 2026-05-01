<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function home()
    {
        return view('client.home');
    }

    public function catalog()
    {
        return view('client.catalog');
    }

    public function product()
    {
        return view('client.product');
    }

    public function cart()
    {
        return view('client.cart');
    }

    public function checkout()
    {
        return view('client.checkout');
    }

    public function tracking()
    {
        return view('client.tracking');
    }

    public function confirmation()
    {
        return view('client.confirmation');
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
            ->with(['items.product', 'zone', 'payments'])
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
