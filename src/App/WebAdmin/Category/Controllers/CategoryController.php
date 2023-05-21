<?php

namespace App\WebAdmin\Category\Controllers;

use App\Controller;
use Domain\Category\Models\Category;
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
