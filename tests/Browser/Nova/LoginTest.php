<?php

namespace Tests\Browser\Nova;

use App\Models\Admin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_can_render_admin_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(config('nova.path'))
                    ->assertSee('Welcome Back!');
        });
    }

    public function test_admin_can_login()
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin)->browse(function (Browser $browser) use ($admin) {
            $browser->visit(config('nova.path'))
                    ->type('email', $admin->email)
                    ->type('password', 'password')
                    ->screenshot('entered_data')
                    ->press('Login')
                    ->assertSee('Dashboard');
        });
    }    
}
