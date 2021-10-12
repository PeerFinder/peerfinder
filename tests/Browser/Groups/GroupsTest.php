<?php

namespace Tests\Browser\Groups;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Str;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Matcher\Models\Peergroup;

/**
 * @group groups
 */
class GroupsTest extends DuskTestCase
{
    use DatabaseMigrations;
    use WithFaker;

    private $mailhog_address = 'http://localhost:8025';
    
    public function test_guest_visits_group_preview()
    {
        $password = 'myLongPassword555&&';

        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $this->browse(function (Browser $browser) use ($user, $pg, $password) {
            $browser->visitRoute('matcher.preview', ['groupname' => $pg->groupname])
                    ->clickLink('Sign up')
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
                    ->withinFrame('#preview-html', function($browser) use ($pg) {
                        $verify_url = $browser->waitForLink('Verify Email Address')->attribute('.button', 'href');
                        $browser->visit($verify_url)->assertSee($pg->user->name);
                    });
        });
    }
}
