<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_blocked_user_is_shown_a_warning_about_being_blocked()
    {
        $user = User::factory()->create(['active' => false]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->press('Fake Login')
                ->assertSee(__('welcome.blocked_account'))
                ->assertSee(__('welcome.blocked_info'));
        });
    }

    /** @test */
    public function it_is_possible_to_login()
    {
        $user = User::factory()->create(['active' => true]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->press('Fake Login')
                ->assertSee(__('common.dashboard'))
                ->assertSee(__('common.logout'));
        });
    }
}
