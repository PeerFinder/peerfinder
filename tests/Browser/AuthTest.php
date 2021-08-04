<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Str;
use App\Providers\RouteServiceProvider;

/**
 * @group auth
 */
class AuthTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'taylor@laravel.com',
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit(route('login'))
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press(__('auth.button_login'))
                    ->assertPathIs(RouteServiceProvider::HOME);
        });
    }

    /** @test */
    public function a_guest_can_register()
    {
        $password = 'myLongPassword555&&';

        $this->browse(function ($browser) use ($password) {
            $browser->visit(route('register'))
                    ->type('name', Str::random(10))
                    ->type('email', Str::random(10).'@gmail.com')
                    ->type('password', $password)
                    ->type('password_confirmation', $password)
                    ->press(__('auth.button_register'))
                    ->assertPathIs(RouteServiceProvider::HOME);
        });
    }    
}
