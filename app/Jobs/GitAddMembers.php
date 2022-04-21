<?php

namespace App\Jobs;

use App\Mail\ExceptionOccured;
use App\Models\Federation;
use App\Models\User;
use App\Notifications\FederationMembersChanged;
use App\Traits\GitTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GitAddMembers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, GitTrait;

    public $federation;
    public $entities;
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Federation $federation, Collection $entities, User $user)
    {
        $this->federation = $federation;
        $this->entities = $entities;
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

        foreach ($this->entities as $entity) {
            Storage::append($this->federation->tagfile, $entity->entityid);
        }

        $this->trimWhiteSpaces($this->federation->tagfile);

        if ($git->hasChanges()) {
            $git->add($this->federation->tagfile);

            $git->commit(
                $this->committer() . ": {$this->federation->tagfile} (update)\n\n"
                    . "Updated by: {$this->user->name} ({$this->user->uniqueid})\n"
            );

            $git->push();

            Notification::send($this->federation->operators, new FederationMembersChanged($this->federation, $this->entities, 'added'));
            Notification::send(User::activeAdmins()->select('id', 'emails')->get(), new FederationMembersChanged($this->federation, $this->entities, 'added'));
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
