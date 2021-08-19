<?php

namespace Tests\Browser\Account;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Str;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;

/**
 * @group account
 */
class ProfileTest extends DuskTestCase
{
    use RefreshDatabase;
    use WithFaker;
    
    public function test_user_can_update_name()
    {
        $user = User::factory()->create();

        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                    ->visitRoute('account.profile.edit')
                    ->type('name', $this->faker->name())
                    ->press(__('account/profile.button_change_profile'))
                    ->assertRouteIs('account.profile.edit')
                    ->assertSee(__('account/profile.profile_changed_successfully'));
        });
    }
}
