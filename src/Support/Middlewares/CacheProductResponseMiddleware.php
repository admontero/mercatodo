<?php

namespace Support\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheProductResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Cache::has($this->cacheKey($request))) {
            return response(Cache::get($this->cacheKey($request)));
        }

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        if (! Cache::has($this->cacheKey($request))) {
            Cache::put($this->cacheKey($request), $response->getContent(), now()->addDay());
        }
    }

    private function cacheKey(Request $request): string
    {
        $url = $request->url();
        /** @var array<string, string> */
        $queryParams = $request->query();

        ksort($queryParams);

        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";

        $rememberKey = md5($fullUrl);

        return $rememberKey;
    }
}
