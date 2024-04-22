<?php

namespace App\Jobs;

use App\Models\Entity;
use App\Models\Federation;
use App\Traits\FederationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SaveMetadataToFolders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected  int $entity_id;
    protected  int $federation_id;

    public function __construct($entity_id, $federation_id)
    {
        $this->entity_id = $entity_id;
        $this->federation_id = $federation_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $entity = Entity::find($this->entity_id);
        $federation = Federation::find($this->federation_id);

        if(!$entity || !$federation){
            return;
        }
        $folderName = $federation->name;
        $fileName = $entity->file;
        if(!Storage::disk('metadata')->exists($folderName))
        {
            Storage::disk('metadata')->makeDirectory($folderName);
        }
        $filePath = $folderName . '/' . $fileName . 'xml';
        $content = $entity->xml_file;
        Storage::disk('metadata')->put($filePath, $content);

    }
}
