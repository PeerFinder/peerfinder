<?php

namespace Tests\Feature\Account;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * @group account
 */
class AvatarTest extends TestCase
{
    public function test_user_can_upload_new_avatar()
    {
        $user = User::factory()->create();

        Storage::fake('local');

        $min_upload_size = config('user.avatar.min_upload_size');

        $response = $this->actingAs($user)->put(route('account.avatar.update'), [
            'avatar' => UploadedFile::fake()->image('avatar.jpeg', $min_upload_size, $min_upload_size),
        ]);

        $this->assertNotNull($user->avatar);
        Storage::disk('local')->assertExists('avatars/' . $user->avatar);
    }

    public function test_user_cannot_upload_wrong_file_format()
    {
        $user = User::factory()->create();

        Storage::fake('local');

        $response = $this->actingAs($user)->put(route('account.avatar.update'), [
            'avatar' => UploadedFile::fake()->create('document.pdf', 256),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $this->assertNull($user->avatar);
    }

    public function test_user_cannot_upload_wrong_image_size()
    {
        $user = User::factory()->create();

        $max_upload_size = config('user.avatar.max_upload_size');

        Storage::fake('local');

        $response = $this->actingAs($user)->put(route('account.avatar.update'), [
            'avatar' => UploadedFile::fake()->image('avatar.jpeg', 1, 1),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $this->assertNull($user->avatar);

        $response = $this->actingAs($user)->put(route('account.avatar.update'), [
            'avatar' => UploadedFile::fake()->image('avatar.jpeg', $max_upload_size + 100, $max_upload_size + 100),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $this->assertNull($user->avatar);
    }    

    public function test_user_can_remove_avatar()
    {
        $user = User::factory()->create([
            'avatar' => 'test.jpg'
        ]);
        
        Storage::fake('local');

        Storage::disk('local')->put('avatars/test.jpg', "dummy-data");

        $response = $this->actingAs($user)->delete(route('account.avatar.destroy'));

        $response->assertSessionHasNoErrors();
        $this->assertNull($user->avatar);
        Storage::disk('local')->assertMissing('avatars/test.jpg');
    }

    public function test_avatar_is_removed_with_user_account()
    {
        $user = User::factory()->create([
            'avatar' => 'test.jpg'
        ]);
        
        Storage::fake('local');

        Storage::disk('local')->put('avatars/test.jpg', "dummy-data");

        $response = $this->actingAs($user)->delete(route('account.account.destroy'), [
            'password' => 'password'
        ]);

        $response->assertSessionHasNoErrors();
        Storage::disk('local')->assertMissing('avatars/test.jpg');
    }
}
