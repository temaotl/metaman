<?php

namespace Tests\Feature\Mail;

use App\Mail\AskRs;
use App\Models\Entity;
use App\Models\Federation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AskRsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function email_is_queued_for_eduidcz_member()
    {
        Mail::fake();

        $user = User::factory()->create();
        $entity = Entity::factory()->create();
        $user->entities()->attach($entity);
        $federation = Federation::factory()->create(['xml_name' => 'https://eduid.cz/metadata']);
        $federation->entities()->attach($entity, [
            'requested_by' => $user->id,
            'approved_by' => $user->id,
            'approved' => true,
            'explanation' => 'Test'
        ]);

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->post(route('entities.rs', $entity))
            ->assertStatus(200);

        Mail::assertQueued(AskRs::class, function ($email) use ($entity) {
            return $email->hasTo(config('mail.admin.address')) &&
                $email->entity->entityid === $entity->entityid;
        });
    }

    /** @test */
    public function email_isnt_queued_for_entity_outside_eduidcz()
    {
        Mail::fake();

        $user = User::factory()->create();
        $entity = Entity::factory()->create();
        $user->entities()->attach($entity);

        $this
            ->actingAs($user)
            ->post(route('entities.rs', $entity))
            ->assertStatus(403);

        Mail::assertNotQueued(AskRs::class);
    }
}
