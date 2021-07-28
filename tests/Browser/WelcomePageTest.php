<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WelcomePageTest extends DuskTestCase
{
    /** @test */
    public function welcome_page_is_shown()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee(config('app.name'))
                ->assertSee(__('common.login'));
        });
    }
}
