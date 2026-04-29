<?php

namespace App\Http\Controllers;

class CourierController extends Controller
{
    public function list()
    {
        return view('courier.list');
    }

    public function detail()
    {
        return view('courier.detail');
    }
}
