<?php

namespace App\WebAdmin\Category\Controllers;

use App\Controller;
use Domain\Category\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('access-category-views');

        return view('backoffice.categories.index');
    }

    public function create(): View
    {
        $this->authorize('access-category-views');

        return view('backoffice.categories.create');
    }

    public function edit(Category $category): View
    {
        $this->authorize('access-category-views');

        return view('backoffice.categories.edit', compact('category'));
    }
}
