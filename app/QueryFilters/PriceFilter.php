<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Contracts\Database\Query\Builder;

class PriceFilter
{
    public function handle(Builder $query, Closure $next): Builder
    {
        if (! request()->has('minPrice') || !request()->has('maxPrice')) {
            return $next($query);
        }

        if (request()->input('minPrice') > request()->input('maxPrice')) {
            return $next($query);
        }

        return $next($query)->whereBetween('price', [request()->input('minPrice'), request()->input('maxPrice')]);
    }
}
