<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Str;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Mail;

/**
 * @group auth
 */
class AuthenticationTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'taylor@laravel.com',
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visitRoute('login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press(__('auth.button_login'))
                    ->assertPathIs(RouteServiceProvider::HOME);
        });
    }

    public function test_guest_can_register()
    {
        $password = 'myLongPassword555&&';
        
        $this->browse(function ($browser) use ($password) {
            
            $browser->visitRoute('register')
                    ->type('name', Str::random(10))
                    ->type('email', Str::random(10).'@gmail.com')
                    ->type('password', $password)
                    ->type('password_confirmation', $password)
                    ->press(__('auth.button_register'))
                    ->assertRouteIs('verification.notice')
                    ->assertSee(__('auth.button_resend_verification_email'));

            $browser->visit('http://localhost:8025')
                    ->waitForLocation('/')
                    ->click('.messages > .msglist-message')
                    ->withinFrame('#preview-html', function($browser) {
                        $browser->waitForLink('Verify Email Address')
                                ->clickLink('Verify Email Address');
                    });

            $browser->visit(RouteServiceProvider::HOME)
                    ->assertPathIs(RouteServiceProvider::HOME);

            $browser->visit(route('logout'));
        });
    }

    public function a_guest_cannot_register_with_short_password()
    {
        $this->browse(function ($browser) {
            $browser->visitRoute('register')
                    ->type('name', Str::random(10))
                    ->type('email', Str::random(10).'@gmail.com')
                    ->type('password', "shorty")
                    ->type('password_confirmation', "shorty")
                    ->press(__('auth.button_register'))
                    ->assertRouteIs('register')
                    ->assertSee('The password must be at least');
            
            $browser->visit(route('logout'));
        });
    }

}
