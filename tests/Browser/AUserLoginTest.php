<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AUserLoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_login()
    {
        User::factory()->create(['active' => true]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->press('Fake Login')
                ->assertSee(config('app.name'))
                ->assertSee(__('common.federations'))
                ->assertSee(__('common.entities'))
                ->assertDontSee(__('common.categories'))
                ->assertDontSee(__('common.groups'))
                ->assertSee(__('common.my_profile'))
                ->assertSee(__('common.logout'))
                ->assertSee(__('common.dashboard'));
        });
    }
}
