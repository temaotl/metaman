<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ABlockedUserLoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_blocked_user_cannot_login()
    {
        User::factory()->create(['active' => false]);

        $this->browse(function(Browser $browser) {
            $browser->visit('/')
                ->press('Fake Login')
                ->assertSee(__('welcome.blocked_account'))
                ->assertSee(__('welcome.blocked_info'));
        });
    }
}
