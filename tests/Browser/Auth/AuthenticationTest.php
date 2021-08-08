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
    
    private $mailhog_address = 'http://localhost:8025';

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
            
            # Visit the registration page, register and check for email verification page
            $browser->visitRoute('register')
                    ->type('name', Str::random(10))
                    ->type('email', Str::random(10).'@gmail.com')
                    ->type('password', $password)
                    ->type('password_confirmation', $password)
                    ->press(__('auth.button_register'))
                    ->assertRouteIs('verification.notice')
                    ->assertSee(__('auth.button_resend_verification_email'));

            # Visit MailHog, check for the last mail and verify the mail
            $browser->visit($this->mailhog_address)
                    ->waitForLocation('/')
                    ->click('.messages > .msglist-message')
                    ->withinFrame('#preview-html', function($browser) {
                        $browser->waitForLink('Verify Email Address')
                                ->clickLink('Verify Email Address');
                    });

            # Check the home router for access, if the verification linked worked, the route is accessible
            $browser->visit(RouteServiceProvider::HOME)
                    ->assertPathIs(RouteServiceProvider::HOME);

            $browser->visit(route('logout'));
        });
    }

    public function test_guest_can_change_unverified_email()
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
                    ->assertSee(__('auth.button_resend_verification_email'))
                    ->clickLink(__('auth.change_your_email'))
                    ->assertRouteIs('account.email.edit');

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

    public function test_user_can_reset_password()
    {
        $password = 'myLongPassword555&&';

        $user = User::factory()->create();

        $this->browse(function ($browser) use ($password, $user) {
            
            # Visit the login page, click on "Forgot Password?" and reset password
            $browser->visitRoute('login')
                    ->clickLink(__('auth.forgot_password'))
                    ->waitForRoute('password.request')
                    ->type('email', $user->email)
                    ->press(__('auth.button_request_password'))
                    ->assertRouteIs('password.request')
                    ->assertSee('We have emailed your password reset link');

            # Visit MailHog, check for the last mail and copy the password reset link
            $browser->visit($this->mailhog_address)
                    ->waitForLocation('/')
                    ->click('.messages > .msglist-message')
                    ->withinFrame('#preview-html', function($frame) use ($browser, $password) {
                        $link = $frame->waitForLink('Reset Password')->attribute('.button', 'href');

                        $browser->visit($link)
                                ->type('password', $password)
                                ->type('password_confirmation', $password)
                                ->press(__('auth.button_reset_password'))
                                ->waitForRoute('login')
                                ->assertSee('Your password has been reset');
                    });

            $browser->visitRoute('login')
                    ->type('email', $user->email)
                    ->type('password', $password)
                    ->press(__('auth.button_login'))
                    ->assertPathIs(RouteServiceProvider::HOME);

            $browser->visit(route('logout'));
        });
    }
}
