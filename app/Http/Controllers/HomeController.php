<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index ()
    {
        $products = Product::active()->price(200, 500)->latest()->where('status', '=', 'active')->limit(10)->get();
        return view('home', [
            'products' => $products,
        ]);
    }

    public function getUser()
    {
        $users = User::with('profile')->get();
        foreach ($users as $user) {
            echo $user->profile->address . '<br>';
        }
    }
}
