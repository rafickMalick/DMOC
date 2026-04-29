<?php

namespace App\Http\Controllers;

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

    public function orders()
    {
        return view('client.orders');
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
