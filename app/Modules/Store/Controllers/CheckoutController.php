<?php

namespace App\Modules\Store\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View
    {
        return view('welcome');
    }
}
