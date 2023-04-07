<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('backoffice.categories.index');
    }

    public function create()
    {
        return view('backoffice.categories.create');
    }

    public function edit(Category $category)
    {
        return view('backoffice.categories.edit', compact('category'));
    }
}
