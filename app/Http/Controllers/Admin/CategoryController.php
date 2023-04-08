<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('backoffice.categories.index');
    }

    public function create(): View
    {
        return view('backoffice.categories.create');
    }

    public function edit(Category $category): View
    {
        return view('backoffice.categories.edit', compact('category'));
    }
}
