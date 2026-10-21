<?php

namespace App\Modules\Store\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $products = Product::latest('id')->take(8)->get();
        return view('welcome', compact('products'));
    }
}
