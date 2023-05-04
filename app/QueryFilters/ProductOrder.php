<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Contracts\Database\Query\Builder;

class ProductOrder
{
    public function handle(Builder $query, Closure $next): Builder
    {
        if (! request()->has('order')) {
            return $next($query)->latest();
        }

        switch (request()->input('order')) {
            case 'orderByOldest':
                $field = 'created_at';
                $sort = 'ASC';
                break;

            case 'orderByPriceDESC':
                $field = 'price';
                $sort = 'DESC';
                break;

            case 'orderByPriceASC':
                $field = 'price';
                $sort = 'ASC';
                break;

            default:
                $field = 'created_at';
                $sort = 'DESC';
                break;
        }

        return $next($query)->orderBy($field, $sort);
    }
}
