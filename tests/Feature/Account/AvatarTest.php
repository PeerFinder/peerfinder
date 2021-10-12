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
    use RefreshDatabase;

    public function test_user_can_render_avatar_view()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('account.avatar.edit'));
        $response->assertStatus(200);
        $response->assertViewIs('frontend.account.avatar.edit');
    }

    public function test_user_has_to_select_a_file()
    {
        $user = User::factory()->create([
            'avatar' => 'test.jpg'
        ]);

        $response = $this->actingAs($user)->put(route('account.avatar.update'), [
            'bla' => null
        ]);

        $response->assertSessionHasErrors();
        $this->assertEquals('test.jpg', $user->avatar);
    }

    public function test_user_can_upload_new_avatar()
    {
        $user = User::factory()->create();

        Storage::fake('local');

        $min_upload_size = config('user.avatar.min_upload_size');

        $response = $this->actingAs($user)->put(route('account.avatar.update'), [
            'avatar' => UploadedFile::fake()->image('avatar.png', $min_upload_size, $min_upload_size),
        ]);

        $this->assertNotNull($user->avatar);
        Storage::disk('local')->assertExists('avatars/' . $user->avatar);
    }

    public function test_user_can_change_avatar()
    {
        $user = User::factory()->create([
            'avatar' => 'test.jpg'
        ]);

        Storage::fake('local');

        $min_upload_size = config('user.avatar.min_upload_size');

        $response = $this->actingAs($user)->put(route('account.avatar.update'), [
            'avatar' => UploadedFile::fake()->image('avatar.jpeg', $min_upload_size, $min_upload_size),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('test.jpg', $user->avatar);
    }

    public function test_user_cannot_upload_wrong_file_format()
    {
        $user = User::factory()->create();

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

    public function test_can_download_user_avatar()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        Storage::fake('local');

        $min_upload_size = config('user.avatar.min_upload_size');

        $response = $this->actingAs($user)->put(route('account.avatar.update'), [
            'avatar' => UploadedFile::fake()->image('avatar.png', $min_upload_size, $min_upload_size),
        ]);

        $response->assertSessionHasNoErrors();

        $response = $this->actingAs($user2)->get(route('media.avatar', [
            'user' => $user->username,
            'size' => 100
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/jpg');

        $response = $this->actingAs($user2)->get(route('media.avatar', [
            'user' => $user2->username,
            'size' => 100,
        ]));

        $response->assertStatus(404);
    }

    public function test_cannot_download_missing_avatar()
    {
        $user = User::factory()->create([
            'avatar' => 'image.jpg'
        ]);

        $response = $this->actingAs($user)->get(route('media.avatar', [
            'user' => $user->username,
            'size' => 100
        ]));

        $response->assertStatus(404);
    }

    public function test_guest_can_download_avatar_placeholder()
    {
        $user = User::factory()->create();

        $response = $this->get(route('media.avatar', [
            'user' => $user->username,
            'size' => 100
        ]));

        $response->assertStatus(200);
        $response->assertHeader('filename', 'placeholder.svg');
    }
}
