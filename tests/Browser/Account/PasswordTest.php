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
class PasswordTest extends DuskTestCase
{
    use DatabaseMigrations;
    
    public function test_user_can_update_password()
    {
        $new_password = 'myNewLongPassword1234%';

        $user = User::factory()->create();

        $this->browse(function ($browser) use ($user, $new_password) {
            $browser->loginAs($user)
                    ->visitRoute('account.password.edit')
                    ->type('current_password', 'password')
                    ->type('password', $new_password)
                    ->type('password_confirmation', $new_password)
                    ->press(__('account/password.button_change_password'))
                    ->assertSee(__('account/password.password_changed_successfully'));
        });
    }
}
