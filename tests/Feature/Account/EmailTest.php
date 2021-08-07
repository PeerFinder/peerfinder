<?php

namespace Tests\Feature\Account;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * @group account
 */
class EmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_email_not_available_for_guests()
    {
        $response = $this->get(route('account.email.edit'));
        $response->assertRedirect(route('login'));
    }

    public function test_account_email_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('account.email.edit'));
        $response->assertStatus(200);
    }
 
    public function test_user_can_change_email()
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('account.email.update'), [
            'email' => 'mail@new.com'
        ]);

        Notification::assertSentTo($user, VerifyEmail::class);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('mail@new.com', $user->email);
        $this->assertFalse($user->hasVerifiedEmail());
    }

    public function test_old_email_keeps_verified_status()
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('account.email.update'), [
            'email' => $user->email
        ]);

        Notification::assertNothingSent();
        $response->assertSessionHasNoErrors();
        $this->assertTrue($user->hasVerifiedEmail());
    }    
}
