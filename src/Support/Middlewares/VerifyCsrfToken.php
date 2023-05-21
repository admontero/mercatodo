<?php

namespace Support\Middlewares;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    public function handle($request, Closure $next)
    {
        if (! auth()->check() && $request->route()?->named('logout')) {
            $this->except[] = route('logout');
        }

        return parent::handle($request, $next);
    }
}
