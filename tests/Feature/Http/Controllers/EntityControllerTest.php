<?php

namespace Tests\Feature\Http\Controllers;

use App\Jobs\GitAddEntity;
use App\Jobs\GitDeleteEntity;
use App\Jobs\GitUpdateEntity;
use App\Models\Entity;
use App\Models\Federation;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class EntityControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function an_anonymouse_user_isnt_shown_an_entities_list()
    {
        $this
            ->followingRedirects()
            ->get(route('federations.index'))
            ->assertSeeText('login');

        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function an_anonymouse_user_isnt_shown_an_entities_details()
    {
        $entity = Entity::factory()->create();

        $this
            ->followingRedirects()
            ->get(route('entities.show', $entity))
            ->assertSeeText('login');

        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function an_anonymouse_user_isnt_shown_a_form_to_add_a_new_entity()
    {
        $this
            ->followingRedirects()
            ->get(route('entities.create'))
            ->assertSeeText('login');

        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function an_anonymouse_user_cannot_add_a_new_entity()
    {
        // metadata URL
        $this
            ->followingRedirects()
            ->post(route('entities.store'), [
                'url' => "https://{$this->faker->domainName()}/{$this->faker->unique()->slug(3)}",
                'federation' => Federation::factory()->create()->id,
                'explanation' => $this->faker->catchPhrase(),
            ])
            ->assertSeeText('login');

        $this->assertEquals(route('login'), url()->current());

        // metadata file
    }

    /** @test */
    public function an_anonymouse_user_cannot_see_entities_edit_page()
    {
        $entity = Entity::factory()->create();

        $this
            ->followingRedirects()
            ->get(route('entities.edit', $entity))
            ->assertSeeText('login');

        $this->assertEquals(1, Entity::count());
        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function an_anonymouse_user_cannot_edit_an_existing_entity()
    {
        $entity = Entity::factory()->create();

        $this
            ->followingRedirects()
            ->patch(route('entities.update', $entity), ['action' => 'update'])
            ->assertSeeText('login');

        $this->assertEquals(1, Entity::count());
        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function an_anonymouse_user_cannot_change_an_existing_entities_status()
    {
        $entity = Entity::factory()->create();

        $this->assertTrue($entity->active);

        $this
            ->followingRedirects()
            ->patch(route('entities.update', $entity), ['action' => 'status'])
            ->assertSeeText('login');

        $this->assertTrue($entity->active);
        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function an_anonymouse_user_cannot_change_an_existing_entities_state()
    {
        $entity = Entity::factory()->create();

        $this->assertFalse($entity->trashed());

        $this
            ->followingRedirects()
            ->patch(route('entities.update', $entity), ['action' => 'state'])
            ->assertSeeText('login');

        $this->assertFalse($entity->trashed());
        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function an_anonymouse_user_cannot_change_an_existing_entities_operators()
    {
        $entity = Entity::factory()->create();
        $entity->operators()->attach(User::factory()->create());
        $this->assertEquals(1, $entity->operators()->count());

        $user = User::factory()->create();

        $this
            ->followingRedirects()
            ->patch(route('entities.update', $entity), [
                'action' => 'add_operators',
                'operators' => [$user->id],
            ])
            ->assertSeeText('login');

        $this->assertEquals(1, $entity->operators()->count());
        $this->assertEquals(route('login'), url()->current());

        $this
            ->followingRedirects()
            ->patch(route('entities.update', $entity), [
                'action' => 'delete_operators',
                'operators' => [User::find(1)->id],
            ])
            ->assertSeeText('login');

        $this->assertEquals(1, $entity->operators()->count());
        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function an_anonymouse_user_cannot_change_an_existing_entities_federation_membership()
    {
        $entity = Entity::factory()->create();

        $this
            ->followingRedirects()
            ->post(route('entities.join', $entity))
            ->assertSeeText('login');


        $this
            ->followingRedirects()
            ->post(route('entities.leave', $entity))
            ->assertSeeText('login');
    }

    /** @test */
    public function an_anonymouse_user_cannot_purge_an_existing_entity()
    {
        $entity = Entity::factory()->create([
            'active' => false,
            'deleted_at' => now(),
        ]);

        $this
            ->followingRedirects()
            ->delete(route('entities.destroy', $entity))
            ->assertSeeText('login');

        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function an_anonymouse_user_cannot_reject_a_new_entity_request()
    {
        $user = User::factory()->create();
        $federation = Federation::factory()->create();
        $entity = Entity::factory()->create(['approved' => false]);
        $entity->federations()->attach($federation, [
            'requested_by' => $user->id,
            'explanation' => $this->faker->catchPhrase(),
        ]);
        $membership = Membership::find(1);

        $this
            ->followingRedirects()
            ->delete(route('memberships.destroy', $membership))
            ->assertSeeText('login');
    }

    /** @test */
    public function an_anonymouse_user_cannot_approve_a_new_entity_request()
    {
        $user = User::factory()->create();
        $federation = Federation::factory()->create();
        $entity = Entity::factory()->create(['approved' => false]);
        $entity->federations()->attach($federation, [
            'requested_by' => $user->id,
            'explanation' => $this->faker->catchPhrase(),
        ]);
        $membership = Membership::find(1);

        $this
            ->followingRedirects()
            ->patch(route('memberships.update', $membership))
            ->assertSeeText('login');
    }

    /** @test */
    public function a_user_is_shown_a_entities_list()
    {
        $this->assertEquals(0, Entity::count());

        $user = User::factory()->create();
        $entity = Entity::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('entities.index'))
            ->assertSeeText($entity->name)
            ->assertSeeText($entity->description)
            ->assertSeeText(__('common.active'));

        $this->assertEquals(1, Entity::count());
        $this->assertEquals(route('entities.index'), url()->current());
    }

    /** @test */
    public function a_user_is_shown_a_entities_details()
    {
        $this->assertEquals(0, Entity::count());

        $user = User::factory()->create();
        $entity = Entity::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('entities.show', $entity))
            ->assertSeeText($entity->name)
            ->assertSeeText($entity->description)
            ->assertSeeText($entity->entityid)
            ->assertSeeText($entity->type);

        $this->assertEquals(1, Entity::count());
        $this->assertEquals(route('entities.show', $entity), url()->current());
    }

    /** @test */
    public function a_user_is_shown_a_form_to_add_a_new_entity()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('entities.create'))
            ->assertSeeText(__('entities.add'));

            $this->assertEquals(route('entities.create'), url()->current());
    }

    /** @test */
    public function a_user_can_add_a_new_entity()
    {
        $user = User::factory()->create();
        $federation = Federation::factory()->create();

        // unreadable URL address with metadata
        $this
            ->followingRedirects()
            ->actingAs($user)
            ->post(route('entities.store', [
                'url' => 'https://ratamahatta.cz/metadata.xml',
                'federation' => $federation->id,
                'explanation' => $this->faker->catchPhrase(),
            ]))
            ->assertSeeText(__('entities.metadata_couldnt_be_read'));

        $this->assertEquals(0, Entity::count());
        $this->assertEquals(route('entities.create'), url()->current());

        // URL address with metadata
        $this
            ->followingRedirects()
            ->actingAs($user)
            ->post(route('entities.store', [
                'url' => 'https://whoami.cesnet.cz/idp/shibboleth',
                'federation' => $federation->id,
                'explanation' => $this->faker->catchPhrase(),
            ]))
            ->assertSeeText(__('entities.entity_requested', ['name' => 'https://whoami.cesnet.cz/idp/shibboleth']));

        $this->assertEquals(1, Entity::count());
        $this->assertEquals(route('entities.index'), url()->current());

        // add already existing entity
        $this
            ->followingRedirects()
            ->actingAs($user)
            ->post(route('entities.store', [
                'url' => 'https://whoami.cesnet.cz/idp/shibboleth',
                'federation' => $federation->id,
                'explanation' => $this->faker->catchPhrase(),
            ]))
            ->assertSeeText(__('entities.existing_already'));

        $this->assertEquals(1, Entity::count());
        $this->assertEquals(route('entities.show', Entity::find(1)), url()->current());
    }

    /** @test */
    public function a_user_with_operator_permission_can_see_entities_edit_page()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()->create();
        $user->entities()->attach($entity);

        $this
            ->actingAs($user)
            ->get(route('entities.edit', $entity))
            ->assertSeeText(__('entities.edit', ['name' => $entity->name_en]))
            ->assertSeeText(__('entities.profile'));

        $this->assertEquals(route('entities.edit', $entity), url()->current());
    }

    /** @test */
    public function a_user_with_operator_permission_can_edit_an_existing_entity()
    {
        Bus::fake();

        $user = User::factory()->create();
        $entity = Entity::factory()->create(['entityid' => 'https://whoami.cesnet.cz/idp/shibboleth']);
        $user->entities()->attach($entity);

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->patch(route('entities.update', $entity), [
                'action' => 'update',
                'url' => 'https://whoami.cesnet.cz/idp/shibboleth',
            ])
            ->assertSeeText(__('entities.entity_updated'));

        $this->assertEquals(route('entities.show', $entity), url()->current());

        Bus::assertDispatched(GitUpdateEntity::class);
    }

    /** @test */
    public function a_user_with_operator_permission_can_change_an_existing_entities_status()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()->create();
        $user->entities()->attach($entity);

        $this->assertTrue($entity->active);

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->patch(route('entities.update', $entity), ['action' => 'status'])
            ->assertSeeText(__('entities.inactive', ['name' => $entity->name_en]));

        $entity->refresh();
        $this->assertFalse($entity->active);
        $this->assertEquals(route('entities.show', $entity), url()->current());

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->patch(route('entities.update', $entity), ['action' => 'status'])
            ->assertSeeText(__('entities.active', ['name' => $entity->name_en]));

        $entity->refresh();
        $this->assertTrue($entity->active);
        $this->assertEquals(route('entities.show', $entity), url()->current());
    }

    /** @test */
    public function a_user_with_operator_permission_can_change_an_existing_entities_state()
    {
        Bus::fake();

        $user = User::factory()->create();
        $entity = Entity::factory()->create();
        $user->entities()->attach($entity);

        $this->assertEquals(1, Entity::count());
        $this->assertFalse($entity->trashed());

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->patch(route('entities.update', $entity), ['action' => 'state'])
            ->assertSeeText(__('entities.deleted', ['name' => $entity->name_en]));

        Bus::assertDispatched(GitDeleteEntity::class);

        $entity->refresh();
        $this->assertTrue($entity->trashed());
        $this->assertEquals(route('entities.show', $entity), url()->current());

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->patch(route('entities.update', $entity), ['action' => 'state'])
            ->assertSeeText(__('entities.restored', ['name' => $entity->name_en]));

        $entity->refresh();
        $this->assertFalse($entity->trashed());
        $this->assertEquals(route('entities.show', $entity), url()->current());

        Bus::assertDispatched(GitAddEntity::class);
    }

    /** @test */
    public function a_user_with_operator_permission_can_change_an_existing_entities_operators()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()->create();
        $user->entities()->attach($entity);
        $new_operator = User::factory()->create();

        $this->assertEquals(1, $entity->operators()->count());

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->patch(route('entities.update', $entity), [
                'action' => 'add_operators',
                'operators' => [$new_operator->id],
            ])
            ->assertSeeText(__('entities.operators_added'));

        $entity->refresh();
        $this->assertEquals(2, $entity->operators()->count());
        $this->assertEquals(route('entities.show', $entity), url()->current());

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->patch(route('entities.update', $entity), [
                'action' => 'delete_operators',
                'operators' => [$new_operator->id],
            ])
            ->assertSeeText(__('entities.operators_deleted'));

        $entity->refresh();
        $this->assertEquals(1, $entity->operators()->count());
        $this->assertEquals(route('entities.show', $entity), url()->current());
    }

    /** @test */
    public function a_user_without_operator_permission_cannot_see_entities_edit_page()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('entities.edit', $entity))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_without_operator_permission_cannot_edit_an_existing_entity()
    {
        $entity = Entity::factory()->create(['entityid' => 'https://whoami.cesnet.cz/idp/shibboleth']);

        $this
            ->followingRedirects()
            ->patch(route('entities.update', $entity), [
                'action' => 'update',
                'url' => 'https://whoami.cesnet.cz/idp/shibboleth',
            ])
            ->assertSeeText('login');
    }

    /** @test */
    public function a_user_without_operator_permission_cannot_change_an_existing_entities_status()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()->create();

        $this->assertEquals(1, Entity::count());

        $this
            ->actingAs($user)
            ->patch(route('entities.update', $entity), ['action' => 'status'])
            ->assertForbidden();
    }

    /** @test */
    public function a_user_without_operator_permission_cannot_change_an_existing_entities_state()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()->create();

        $this->assertEquals(1, Entity::count());

        $this
            ->actingAs($user)
            ->patch(route('entities.update', $entity), ['action' => 'state'])
            ->assertForbidden();
    }

    /** @test */
    public function a_user_without_operator_permission_cannot_change_an_existing_entities_operators()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()->create();
        $new_operator = User::factory()->create();

        $this
            ->actingAs($user)
            ->patch(route('entities.update', $entity), [
                'action' => 'add_operators',
                'operators' => [$new_operator->id],
            ])
            ->assertForbidden();

        $entity->refresh();
        $this->assertEquals(0, $entity->operators()->count());

        $this
            ->actingAs($user)
            ->patch(route('entities.update', $entity), [
                'action' => 'delete_operators',
                'operators' => [$new_operator->id],
            ])
            ->assertForbidden();
    }

    /** @test */
    public function a_user_cannot_purge_an_existing_entity()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()->create([
            'active' => false,
            'deleted_at' => now(),
        ]);

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->delete(route('entities.destroy', $entity))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_cannot_reject_a_new_entity_request()
    {
        $user = User::factory()->create();
        $operator = User::factory()->create();
        $federation = Federation::factory()->create();
        $entity = Entity::factory()->create(['approved' => false]);
        $entity->federations()->attach($federation, [
            'requested_by' => $operator->id,
            'explanation' => $this->faker->catchPhrase(),
        ]);
        $membership = Membership::find(1);

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->delete(route('memberships.destroy', $membership))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_cannot_approve_a_new_entity_request()
    {
        $user = User::factory()->create();
        $operator = User::factory()->create();
        $federation = Federation::factory()->create();
        $entity = Entity::factory()->create(['approved' => false]);
        $operator->entities()->attach($entity);
        $entity->federations()->attach($federation, [
            'requested_by' => $operator->id,
            'explanation' => $this->faker->catchPhrase(),
        ]);
        $membership = Membership::find(1);

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->patch(route('memberships.update', $membership))
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_is_shown_a_entities_list()
    {
        $admin = User::factory()->create(['admin' => true]);
        $entity = Entity::factory()->create();

        $this
            ->actingAs($admin)
            ->get(route('entities.index'))
            ->assertSeeText($entity->name)
            ->assertSeeText($entity->description)
            ->assertSeeText(__('common.active'));

        $this->assertEquals(1, Entity::count());
        $this->assertEquals(route('entities.index'), url()->current());
    }

    /** @test */
    public function an_admin_is_shown_a_entities_details()
    {
        $admin = User::factory()->create(['admin' => true]);
        $entity = Entity::factory()->create();

        $this
            ->actingAs($admin)
            ->get(route('entities.show', $entity))
            ->assertSeeText($entity->name)
            ->assertSeeText($entity->description)
            ->assertSeeText($entity->entityid)
            ->assertSeeText($entity->type)
            ->assertSeeText($entity->file);

        $this->assertEquals(1, Entity::count());
        $this->assertEquals(route('entities.show', $entity), url()->current());
    }

    /** @test */
    public function an_admin_is_shown_a_form_to_add_a_new_entity()
    {
        $admin = User::factory()->create(['admin' => true]);

        $this
            ->actingAs($admin)
            ->get(route('entities.create'))
            ->assertSeeText(__('entities.add'));

        $this->assertEquals(route('entities.create'), url()->current());
    }

    /** @test */
    public function an_admin_can_add_a_new_entity()
    {
        $admin = User::factory()->create(['admin' => true]);
        $federation = Federation::factory()->create();

        // unreadable URL address with metadata
        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->post(route('entities.store', [
                'url' => 'https://ratamahatta.cz/metadata.xml',
                'federation' => $federation->id,
                'explanation' => $this->faker->catchPhrase(),
            ]))
            ->assertSeeText(__('entities.metadata_couldnt_be_read'));

        $this->assertEquals(0, Entity::count());
        $this->assertEquals(route('entities.create'), url()->current());

        // URL address with metadata
        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->post(route('entities.store'), [
                'url' => 'https://whoami.cesnet.cz/idp/shibboleth',
                'federation' => $federation->id,
                'explanation' => $this->faker->catchPhrase(),
            ])
            ->assertSeeText(__('entities.entity_requested', ['name' => 'https://whoami.cesnet.cz/idp/shibboleth']));

        $this->assertEquals(1, Entity::count());
        $this->assertEquals(route('entities.index'), url()->current());

        // add already existing entity
        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->post(route('entities.store'), [
                'url' => 'https://whoami.cesnet.cz/idp/shibboleth',
                'federation' => $federation->id,
                'explanation' => $this->faker->catchPhrase(),
            ])
            ->assertSeeText(__('entities.existing_already'));

        $this->assertEquals(1, Entity::count());
        $this->assertEquals(route('entities.show', Entity::find(1)), url()->current());
    }

    /** @test */
    public function an_admin_can_see_entities_edit_page()
    {
        $admin = User::factory()->create(['admin' => true]);
        $entity = Entity::factory()->create();

        $this
            ->actingAs($admin)
            ->get(route('entities.edit', $entity))
            ->assertSeeText(__('entities.edit', ['name' => $entity->name_en]))
            ->assertSeeText(__('entities.profile'));

        $this->assertEquals(1, Entity::count());
        $this->assertEquals(route('entities.edit', $entity), url()->current());
    }

    /** @test */
    public function an_admin_can_edit_an_existing_entity()
    {
        Bus::fake();

        $admin = User::factory()->create(['admin' => true]);
        $entity = Entity::factory()->create(['entityid' => 'https://whoami.cesnet.cz/idp/shibboleth']);

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->patch(route('entities.update', $entity), [
                'action' => 'update',
                'url' => 'https://whoami.cesnet.cz/idp/shibboleth',
            ])
            ->assertSeeText(__('entities.entity_updated'));

        $this->assertEquals(route('entities.show', $entity), url()->current());
        Bus::assertDispatched(GitUpdateEntity::class);
    }

    /** @test */
    public function an_admin_can_change_an_existing_entities_status()
    {
        $admin = User::factory()->create(['admin' => true]);
        $entity = Entity::factory()->create();

        $this->assertEquals(1, Entity::count());
        $this->assertTrue($entity->active);

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->patch(route('entities.update', $entity), ['action' => 'status'])
            ->assertSeeText(__('entities.inactive', ['name' => $entity->name_en]));

        $entity->refresh();
        $this->assertFalse($entity->active);
        $this->assertEquals(route('entities.show', $entity), url()->current());

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->patch(route('entities.update', $entity), ['action' => 'status'])
            ->assertSeeText(__('entities.active', ['name' => $entity->name_en]));

        $entity->refresh();
        $this->assertTrue($entity->active);
        $this->assertEquals(route('entities.show', $entity), url()->current());
    }

    /** @test */
    public function an_admin_can_change_an_existing_entities_state()
    {
        Bus::fake();

        $admin = User::factory()->create(['admin' => true]);
        $entity = Entity::factory()->create();

        $this->assertEquals(1, Entity::count());
        $this->assertFalse($entity->trashed());

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->patch(route('entities.update', $entity), ['action' => 'state'])
            ->assertSeeText(__('entities.deleted', ['name' => $entity->name_en]));

        Bus::assertDispatched(GitDeleteEntity::class);

        $entity->refresh();
        $this->assertTrue($entity->trashed());
        $this->assertEquals(route('entities.show', $entity), url()->current());

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->patch(route('entities.update', $entity), ['action' => 'state'])
            ->assertSeeText(__('entities.restored', ['name' => $entity->name_en]));

        $entity->refresh();
        $this->assertFalse($entity->trashed());
        $this->assertEquals(route('entities.show', $entity), url()->current());

        Bus::assertDispatched(GitAddEntity::class);
    }

    /** @test */
    public function an_admin_can_change_an_existing_entities_operators()
    {
        $admin = User::factory()->create(['admin' => true]);
        $entity = Entity::factory()->create();
        $new_operator = User::factory()->create();

        $this->assertEquals(1, Entity::count());
        $this->assertEquals(2, User::count());
        $this->assertEquals(0, $entity->operators()->count());

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->patch(route('entities.update', $entity), ['action' => 'add_operators'])
            ->assertSeeText(__('entities.add_empty_operators'));

        $entity->refresh();
        $this->assertEquals(0, $entity->operators()->count());
        $this->assertEquals(route('entities.show', $entity), url()->current());

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->patch(route('entities.update', $entity), [
                'action' => 'add_operators',
                'operators' => [$new_operator->id],
            ])
            ->assertSeeText(__('entities.operators_added'));

        $entity->refresh();
        $this->assertEquals(1, $entity->operators()->count());
        $this->assertEquals(route('entities.show', $entity), url()->current());

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->patch(route('entities.update', $entity), ['action' => 'delete_operators'])
            ->assertSeeText(__('entities.delete_empty_operators'));

        $entity->refresh();
        $this->assertEquals(1, $entity->operators()->count());
        $this->assertEquals(route('entities.show', $entity), url()->current());

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->patch(route('entities.update', $entity), [
                'action' => 'delete_operators',
                'operators' => [$new_operator->id],
            ])
            ->assertSeeText(__('entities.operators_deleted'));

        $entity->refresh();
        $this->assertEquals(0, $entity->operators()->count());
        $this->assertEquals(route('entities.show', $entity), url()->current());
    }

    /** @test */
    public function an_admin_can_purge_an_existing_entity()
    {
        $admin = User::factory()->create(['admin' => true]);
        $entity = Entity::factory()->create([
            'active' => false,
            'deleted_at' => now(),
        ]);
        $name = $entity->name_en;

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->delete(route('entities.destroy', $entity))
            ->assertSeeText(__('entities.destroyed', ['name' => $name]));
    }

    /** @test */
    public function an_admin_can_reject_a_new_entity_request()
    {
        $admin = User::factory()->create(['admin' => true]);
        $federation = Federation::factory()->create();
        $entity = Entity::factory()->create(['approved' => false]);
        $entity->federations()->attach($federation, [
            'requested_by' => $admin->id,
            'explanation' => $this->faker->catchPhrase(),
        ]);
        $membership = Membership::find(1);

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->delete(route('memberships.destroy', $membership))
            ->assertSeeText(__('federations.membership_rejected', ['entity' => $entity->name_en]));
    }

    /** @test */
    public function an_admin_can_approve_a_new_entity_request()
    {
        Bus::fake();

        $admin = User::factory()->create(['admin' => true]);
        $federation = Federation::factory()->create();
        $entity = Entity::factory()->create(['approved' => false]);
        $entity->federations()->attach($federation, [
            'requested_by' => $admin->id,
            'explanation' => $this->faker->catchPhrase(),
        ]);
        $membership = Membership::find(1);

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->patch(route('memberships.update', $membership))
            ->assertSeeText(__('federations.membership_accepted', ['entity' => $entity->entityid]));
        }

    /** @test */
    public function not_even_an_admin_can_run_update_function_without_definig_action()
    {
        $admin = User::factory()->create(['admin' => true]);
        $entity = Entity::factory()->create();

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->put(route('entities.update', $entity));

        $this->assertEquals(route('home'), url()->current());
    }

}
