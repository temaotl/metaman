<?php

namespace App\Jobs;

use App\Mail\ExceptionOccured;
use App\Models\Entity;
use App\Models\User;
use App\Traits\GitTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GitUpdateEntity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, GitTrait;

    public $entity;
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Entity $entity, User $user)
    {
        $this->entity = $entity;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $git = $this->initializeGit();

        Storage::put($this->entity->file, $this->entity->metadata);

        if ($git->hasChanges()) {
            $git->add($this->entity->file);

            $git->commit(
                $this->committer() . ": {$this->fqdn($this->entity->entityid)} (update)\n\n"
                    . "Updated by: {$this->user->name} ({$this->user->uniqueid})"
            );

            $git->push();
        }
    }

    public function failed(Throwable $exception)
    {
        Log::critical("Exception occured in {$exception->getFile()} on line {$exception->getLine()}: {$exception->getMessage()}");
        Log::channel('slack')->critical("Exception occured in {$exception->getFile()} on line {$exception->getLine()}: {$exception->getMessage()}");

        Mail::to(config('mail.admin.address'))->send(new ExceptionOccured([
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]));
    }
}
