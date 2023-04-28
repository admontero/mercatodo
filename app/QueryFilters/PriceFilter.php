<?php

namespace App\QueryFilters;

use Closure;

class PriceFilter
{
    public function handle($request, Closure $next)
    {
        if (! request()->has('minPrice') || !request()->has('maxPrice')) {
            return $next($request);
        }

        if (request()->input('minPrice') > request()->input('maxPrice')) {
            return $next($request);
        }

        return $next($request)->whereBetween('price', [request()->input('minPrice'), request()->input('maxPrice')]);
    }
}
