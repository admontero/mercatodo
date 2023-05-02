<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backoffice.products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backoffice.products.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('backoffice.products.edit', compact('product'));
    }
}
