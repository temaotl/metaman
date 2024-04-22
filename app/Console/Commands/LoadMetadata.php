<?php

namespace App\Console\Commands;

use App\Facades\EntityFacade;
use App\Jobs\SaveMetadataToFolders;
use App\Models\Entity;
use App\Models\Membership;
use App\Traits\FederationTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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

    use FederationTrait;


    /**
     * Execute the console command.
     */

    public function handle()
    {
        Log::info("start Metadata");
        $this->updateFederationFolders();
        $membership = Membership::select('entity_id','federation_id')->whereApproved(1)->get();
        foreach ($membership as $member) {
            EntityFacade::SaveEntityMetadataToFile($member->entity_id, $member->federation_id);
         //  SaveMetadataToFolders::dispatch($member->entity_id, $member->federation_id);
        }

    }
}
