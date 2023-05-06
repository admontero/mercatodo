<?php

namespace App\Filters;

use Closure;
use Illuminate\Contracts\Database\Query\Builder;

class NameFilter
{
    public function handle(Builder $builder, Closure $next): Builder
    {
        if (! request()->has('name')) {
            return $next($builder);
        }

        return $next($builder)->where('name', 'like', '%'.request()->input('name').'%');
    }
}
