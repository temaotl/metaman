<?php

namespace App\Console\Commands;

use App\Models\Entity;
use App\Traits\ValidatorTrait;
use Illuminate\Console\Command;

class ValidateMetaConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:val';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    use ValidatorTrait;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach (Entity::select()->get() as $entity)
        {

/*            if($entity->id <= 103)
                continue;*/

            $ent = Entity::where('id', $entity->id)->select()->first();

            $res = json_decode($this->validateMetadata($ent->metadata),true);
           // $res = json_decode($this->validateMetadata($ent->xml_file),true);

            $res['ent_id'] = $ent->id;
            dump($res);


        }
    }
}
