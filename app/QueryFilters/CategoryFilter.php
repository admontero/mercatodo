<?php

namespace App\QueryFilters;

use Closure;

class CategoryFilter
{
    public function handle($request, Closure $next)
    {
        if (! request()->has('categoryId')) {
            return $next($request);
        }

        return $next($request)->where('category_id', request()->input('categoryId'));
    }
}
