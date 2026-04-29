<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function kpi()
    {
        return view('admin.kpi');
    }

    public function products()
    {
        return view('admin.products');
    }

    public function orders()
    {
        return view('admin.orders');
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
