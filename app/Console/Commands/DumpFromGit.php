<?php

namespace App\Console\Commands;


use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryManagementController;
use App\Http\Controllers\GroupController;
use App\Models\Category;
use App\Models\Entity;
use App\Models\Group;
use App\Models\User;
use App\Traits\DumpFromGit\CreateCategoriesAndGroupsTrait;
use App\Traits\DumpFromGit\CreateEntitiesTrait;
use App\Traits\DumpFromGit\CreateFederationTrait;
use App\Traits\GitTrait;
use Illuminate\Console\Command;
use App\Traits\ValidatorTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DumpFromGit extends Command
{
    use GitTrait, ValidatorTrait;
    use CreateFederationTrait,CreateEntitiesTrait,CreateCategoriesAndGroupsTrait;

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
        $this->createCategoriesAndGroups();
        $this->createFederations();
        $this->createEntities($firstAdminId);
        $this->updateGroupsAndCategories();

    }
}
