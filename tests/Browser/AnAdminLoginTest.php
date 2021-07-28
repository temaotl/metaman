<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AnAdminLoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_admin_can_login()
    {
        User::factory()->create(['active' => true, 'admin' => true]);

        $this->browse(function(Browser $browser) {
            $browser->visit('/')
                ->press('Fake Login')
                ->assertSee(config('app.name'))
                ->assertSee(__('common.federations'))
                ->assertSee(__('common.entities'))
                ->assertSee(__('common.categories'))
                ->assertSee(__('common.groups'))
                ->assertSee(__('common.my_profile'))
                ->assertSee(__('common.users'))
                ->assertSee(__('common.logout'))
                ->assertSee(__('common.dashboard'));
        });
    }
}
