<?php

namespace Tests\Unit\Http\Middlewares;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Support\Middlewares\RedirectIfAuthenticated;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RedirectIfAuthenticatedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function redirect_to_admin_dashboard_if_user_is_admin(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('12345678')
        ]);

        Auth::shouldReceive('guard')->andReturnSelf();
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($admin);

        $request = Request::create('/');
        $next = function ($request) {
            return new Response('');
        };

        $middleware = new RedirectIfAuthenticated();

        $response = $middleware->handle($request, $next);

        $this->assertTrue($response->isRedirect(route('admin.dashboard')));
    }

    /**
     * @test
     */
    public function redirect_to_principal_page_if_user_is_not_an_admin(): void
    {
        $customer = User::factory()->customer()->create([
            'email' => 'customer@test.com',
            'password' => bcrypt('12345678')
        ]);

        Auth::shouldReceive('guard')->andReturnSelf();
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($customer);

        $request = Request::create('/');
        $next = function ($request) {
            return new Response('');
        };

        $middleware = new RedirectIfAuthenticated();

        $response = $middleware->handle($request, $next);

        $this->assertTrue($response->isRedirect(route('products.index')));
    }
}
