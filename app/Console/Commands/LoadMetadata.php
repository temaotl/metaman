<?php

namespace App\Console\Commands;

use App\Models\Entity;
use App\Models\Membership;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class LoadMetadata extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:load-metadata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';




    /**
     * Execute the console command.
     */
    public function handle()
    {
        $membership = Membership::select('entity_id','federation_id')->whereApproved(1)->get();
        foreach ($membership as $member) {
            dump($member->entity_id);
        }


    }
}
