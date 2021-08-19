<?php

namespace Tests\Browser\Account;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Str;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;

/**
 * @group account
 */
class EmailTest extends DuskTestCase
{
    use DatabaseMigrations;
    
    public function test_user_can_update_email()
    {
        $user = User::factory()->create();

        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                    ->visitRoute('account.email.edit')
                    ->type('email', 'new@mail.com')
                    ->press(__('account/email.button_change_email'))
                    ->assertRouteIs('verification.notice')
                    ->assertSee(__('account/email.email_changed_successfully'));
        });
    }
}
