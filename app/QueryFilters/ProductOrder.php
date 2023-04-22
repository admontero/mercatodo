<?php

namespace App\QueryFilters;

use Closure;

class ProductOrder
{
    public function handle($request, Closure $next)
    {
        if (! request()->has('order')) {
            return $next($request)->latest();
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

        return $next($request)->orderBy($field, $sort);
    }
}
