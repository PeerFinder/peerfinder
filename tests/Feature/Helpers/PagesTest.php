<?php

namespace Tests\Feature\Helpers;

use App\Helpers\Facades\Pages;
use App\Helpers\Facades\Urler;
use App\Models\Page;
use App\Models\User;
use Tests\TestCase;

class PagesTest extends TestCase
{
    public function test_get_page()
    {
        $p = Page::factory()->create();

        $page = Pages::getPage('de', $p->slug);

        $this->assertEquals($p->title_de, $page['title']);
    }

    public function test_get_wrong_language()
    {
        $p = Page::factory()->create();

        $page = Pages::getPage('xy', $p->slug);

        $this->assertNull($page);
    }    

    public function test_get_missing_page()
    {
        Page::factory()->create();

        $page = Pages::getPage('de', 'something-else');
        
        $this->assertNull($page);
    }
}