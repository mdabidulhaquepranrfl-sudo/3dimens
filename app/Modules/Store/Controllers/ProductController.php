<?php

namespace App\Modules\Store\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::latest('id')->paginate(12);
        return view('welcome', compact('products'));
    }

    public function show(int $id): View
    {
        $product = Product::findOrFail($id);
        return view('welcome', compact('product'));
    }
}
