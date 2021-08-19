<?php

namespace Tests\Browser\Account;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Str;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;

/**
 * @group account
 */
class AccountTest extends DuskTestCase
{
    use RefreshDatabase;
    use WithFaker;
    
    public function test_user_can_delete_account()
    {
        $user = User::factory()->create();

        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                    ->visitRoute('account.account.edit')
                    ->type('password', 'password')
                    ->press(__('account/account.button_delete_account'))
                    ->assertRouteIs('index')
                    ->assertGuest();
        });
    }
}
