<?php

namespace Tests\Feature\Helpers;

use App\Helpers\Facades\Infocards;
use App\Models\Infocard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InfocardsTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_infocard()
    {
        $c = Infocard::factory()->create();

        $card = Infocards::getCard($c->language, $c->slug);
        $this->assertEquals($c->title, $card->title);

        $card = Infocards::getCard('xy', $c->slug);
        $this->assertNull($card);
    }

    public function test_get_multiple_infocards()
    {
        $c = Infocard::factory(5)->create();

        $slugs = [
            $c[0]->slug,
            $c[1]->slug,
            $c[2]->slug,
            'some-random-string-xy',
        ];

        $cards = Infocards::getCards($c[0]->language, $slugs);
        $this->assertCount(3, $cards);
        $this->assertEquals($c[0]->title, $cards[$c[0]->slug]->title);
    }

    public function test_infocard_is_closable()
    {
        $user = User::factory()->create();
        $c = Infocard::factory()->create();

        $card = Infocards::getCard($c->language, $c->slug, $user);
        $this->assertNotNull($card);
        $this->assertEquals($c->title, $card->title);

        $ret = Infocards::closeCard($c->language, $c->slug, $user);
        $this->assertTrue($ret);

        $card = Infocards::getCard($c->language, $c->slug, $user);
        $this->assertNull($card);

        $ret = Infocards::closeCard($c->language, $c->slug, $user);
        $this->assertFalse($ret);
    }

    public function test_user_can_close_infocard()
    {
        $user = User::factory()->create();
        $c = Infocard::factory()->create();

        $response = $this->actingAs($user)->postJson(route('infocards.close', ['slug' => $c->slug]));

        $response->assertStatus(200);
        $response->assertJson(['closed' => true]);
    }
}
