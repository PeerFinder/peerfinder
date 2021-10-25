<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Matcher\Models\Peergroup;
use Tests\TestCase;
use Illuminate\Support\Str;
use Matcher\Facades\Matcher;
use Matcher\Models\Language;
use Matcher\Events\PeergroupCreated;
use Matcher\Events\PeergroupDeleted;

/**
 * @group Peergroup
 */
class ImageTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_owner_can_render_group_image_view()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $response = $this->actingAs($user)->get(route('matcher.image.edit', ['pg' => $pg->groupname]));

        $response->assertStatus(200);

        $response->assertViewIs('matcher::image.edit');
    }

    public function test_owner_can_upload_group_image()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
    
        Storage::fake('local');
    
        $min_upload_width = config('matcher.image.min_upload_width');
        $min_upload_height = config('matcher.image.min_upload_height');

        $response = $this->actingAs($user)->put(route('matcher.image.update', ['pg' => $pg->groupname]), [
            'image' => UploadedFile::fake()->image('image.png', $min_upload_width, $min_upload_height),
        ]);

        $response->assertStatus(302);
    
        $pg->refresh();

        $this->assertNotNull($pg->image);

        Storage::disk('local')->assertExists('matcher/images/' . $pg->image);
    }

    public function test_owner_can_override_group_image()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create([
            'image' => 'test.jpg',
        ]);
    
        Storage::fake('local');
    
        $min_upload_width = config('matcher.image.min_upload_width');
        $min_upload_height = config('matcher.image.min_upload_height');

        $response = $this->actingAs($user)->put(route('matcher.image.update', ['pg' => $pg->groupname]), [
            'image' => UploadedFile::fake()->image('image.png', $min_upload_width, $min_upload_height),
        ]);

        $response->assertStatus(302);
    
        $pg->refresh();

        $this->assertNotNull($pg->image);

        Storage::disk('local')->assertExists('matcher/images/' . $pg->image);
    }

    public function test_owner_can_remove_group_image()
    {
        $user = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user)->create([
            'image' => 'test.jpg'
        ]);

        Storage::fake('local');

        Storage::disk('local')->put('matcher/images/test.jpg', "dummy-data");

        $response = $this->actingAs($user)->delete(route('matcher.image.destroy', ['pg' => $pg->groupname]));

        $response->assertStatus(302);

        $response->assertSessionHasNoErrors();

        $pg->refresh();

        $this->assertNull($pg->image);

        Storage::disk('local')->assertMissing('matcher/images/test.jpg');
    }    
}
