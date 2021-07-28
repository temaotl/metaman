<?php

namespace App\Jobs;

use App\Mail\ExceptionOccured;
use App\Models\Federation;
use App\Models\User;
use App\Notifications\FederationUpdated;
use App\Traits\GitTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GitUpdateFederation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, GitTrait;

    public $federation;
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Federation $federation, User $user)
    {
        $this->federation = $federation;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $content  = "[{$this->federation->xml_id}]\n";
        $content .= "filters = {$this->federation->filters}\n";
        $content .= "name = {$this->federation->xml_name}";

        $git = $this->initializeGit();

        Storage::put($this->federation->cfgfile, $content);

        if($git->hasChanges())
        {
            $git->add($this->federation->cfgfile);

            $git->commit(
                $this->committer() . ": {$this->federation->cfgfile} (update)\n\n"
                . "Updated by: {$this->user->name} ({$this->user->uniqueid})\n"
            );

            $git->push();

            Notification::send($this->federation->operators, new FederationUpdated($this->federation));
            Notification::send(User::activeAdmins()->select('id', 'email')->get(), new FederationUpdated($this->federation));
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
