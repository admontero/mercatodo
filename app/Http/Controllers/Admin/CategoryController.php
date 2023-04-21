<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        if (! Gate::allows('manage-category')) {
            abort(403);
        }

        return view('backoffice.categories.index');
    }

    public function create(): View
    {
        if (! Gate::allows('manage-category')) {
            abort(403);
        }

        return view('backoffice.categories.create');
    }

    public function edit(Category $category): View
    {
        if (! Gate::allows('manage-category')) {
            abort(403);
        }

        return view('backoffice.categories.edit', compact('category'));
    }
}
