<?php

namespace App\Filters;

use Closure;
use Illuminate\Contracts\Database\Query\Builder;

class ProductOrder
{
    public function handle(Builder $query, Closure $next): Builder
    {
        if (! request()->has('order')) {
            return $next($query)->latest();
        }

        [$field, $sort] = match(request()->input('order')) {
            'orderByOldest' => ['created_at', 'ASC'],
            'orderByPriceDESC' => ['price', 'DESC'],
            'orderByPriceASC' => ['price', 'ASC'],
            default => ['created_at', 'DESC'],
        };

        return $next($query)->orderBy($field, $sort);
    }
}
