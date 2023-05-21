<?php

namespace Domain\Product\QueryBuilders;

use Closure;
use Illuminate\Contracts\Database\Query\Builder;

class NameQueryBuilder
{
    public function handle(Builder $builder, Closure $next): Builder
    {
        if (! request()->has('name')) {
            return $next($builder);
        }

        return $next($builder)->where('name', 'like', '%'.request()->input('name').'%');
    }
}
