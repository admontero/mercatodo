<?php

namespace App\Web\Product\Controllers;

use App\Controller;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('products.index');
    }
}
