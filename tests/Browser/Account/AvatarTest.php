<?php

namespace Tests\Browser\Account;

use App\Helpers\Facades\Avatar;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Str;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * @group account
 */
class AvatarTest extends DuskTestCase
{
    use DatabaseMigrations;
    use WithFaker;
    
    public function test_user_can_upload_avatar()
    {
        $user = User::factory()->create([
            'avatar' => 'dusk_avatar_test.jpg'
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                    ->visitRoute('account.avatar.edit')
                    ->attach('avatar', __DIR__ . '/../../resources/test_avatar.jpeg')
                    ->press(__('account/avatar.button_upload_avatar'))
                    ->assertRouteIs('account.avatar.edit')
                    ->assertSee(__('account/avatar.avatar_changed_successfully'));
        });

        $user->refresh();
        $this->assertNotNull($user->avatar);
    }
}
