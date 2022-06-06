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
}
