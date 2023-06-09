<?php

namespace App\WebAdmin\Product\Controllers;

use App\Controller;
use Domain\Product\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('access-product-views');

        return view('backoffice.products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('access-product-views');

        return view('backoffice.products.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $this->authorize('access-product-views');

        return view('backoffice.products.edit', compact('product'));
    }
}
