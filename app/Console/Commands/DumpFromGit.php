<?php

namespace App\Console\Commands;


use App\Facades\EntityFacade;
use App\Models\User;
use App\Traits\DumpFromGit\CreateCategoriesAndGroupsTrait;
use App\Traits\DumpFromGit\CreateEntitiesTrait;
use App\Traits\DumpFromGit\CreateFederationTrait;
use App\Traits\DumpFromGit\EntitiesHelp\UpdateEntity;
use App\Traits\FederationTrait;
use App\Traits\GitTrait;
use Illuminate\Console\Command;
use App\Traits\ValidatorTrait;
use Illuminate\Support\Facades\Artisan;


class DumpFromGit extends Command
{
    use GitTrait, ValidatorTrait;
    use CreateFederationTrait,CreateEntitiesTrait,CreateCategoriesAndGroupsTrait;
    use UpdateEntity,FederationTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dump-from-git';

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
        $firstAdminId = User::where('admin', 1)->first()->id;
        $this->initializeGit();
        $this->createFederations();
        $this->createEntities($firstAdminId);
        $this->createCategoriesAndGroups();
        $this->updateGroupsAndCategories();
        $this->updateEntitiesXml();
        $this->updateFederationFolders();
        Artisan::call('app:load-metadata');
    }
}
