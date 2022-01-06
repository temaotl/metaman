<?php

namespace Tests\Feature\Mail;

use App\Mail\AskRs;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AskRsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function email_is_queued()
    {
        Mail::fake();

        $user = User::factory()->create();
        $entity = Entity::factory()->create();
        $user->entities()->attach($entity);

        $this
            ->actingAs($user)
            ->post(route('entities.rs', $entity));

        Mail::assertQueued(AskRs::class, function ($email) use ($entity) {
            return $email->hasTo(config('mail.admin.address')) &&
                $email->entity->entityid === $entity->entityid;
        });
    }
}
