<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Contracts\Database\Query\Builder;

class CategoryFilter
{
    public function handle(Builder $query, Closure $next): Builder
    {
        if (! request()->has('categoryId')) {
            return $next($query);
        }

        return $next($query)->where('category_id', request()->input('categoryId'));
    }
}
