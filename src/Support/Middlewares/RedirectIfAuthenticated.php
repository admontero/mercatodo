<?php

namespace Support\Middlewares;

use Closure;
use Domain\Role\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                /** @var \Domain\User\Models\User $user */
                $user = Auth::user();

                if ($user->hasRole(RoleEnum::ADMIN->value)) {
                    return redirect(route('admin.dashboard'));
                }

                return redirect('/');
            }
        }

        return $next($request);
    }
}
