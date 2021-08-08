<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group auth
 */
class VerifiedRoutesTest extends TestCase
{
    use RefreshDatabase;

    private $routes = [
        'account.password.edit' => 'get',
        'account.password.update' => 'put',
        'account.account.edit' => 'get',
        'account.account.update' => 'put',
        'account.profile.edit' => 'get',
        'account.profile.update' => 'put',
        'dashboard.index' => 'get',
    ];

    public function test_check_routes_accessible_only_if_verified()
    {
        $user = User::factory()->create([
            'email_verified_at' => null
        ]);

        $this->assertFalse($user->hasVerifiedEmail());

        foreach ($this->routes as $route => $method) {
            $response = $this->actingAs($user)->$method(route($route));

            if(!$response->isRedirect() || app('url')->to($response->headers->get('Location')) != route('verification.notice')) {
                $this->fail('Route is accessible by unverified users: ' . $route . ', method: ' . $method);
            }
        }
    }
}
