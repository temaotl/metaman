<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoinFederation;
use App\Http\Requests\StoreEntity;
use App\Jobs\GitAddEntity;
use App\Jobs\GitAddMember;
use App\Jobs\GitAddToCategory;
use App\Jobs\GitAddToEdugain;
use App\Jobs\GitAddToHfd;
use App\Jobs\GitAddToRs;
use App\Jobs\GitDeleteEntity;
use App\Jobs\GitDeleteFromCategory;
use App\Jobs\GitDeleteFromEdugain;
use App\Jobs\GitDeleteFromFederation;
use App\Jobs\GitDeleteFromHfd;
use App\Jobs\GitDeleteFromRs;
use App\Jobs\GitUpdateEntity;
use App\Mail\AskRs;
use App\Models\Category;
use App\Models\Entity;
use App\Models\Federation;
use App\Models\User;
use App\Notifications\EntityDestroyed;
use App\Notifications\EntityEdugainStatusChanged;
use App\Notifications\EntityOperatorsChanged;
use App\Notifications\EntityRequested;
use App\Notifications\EntityStateChanged;
use App\Notifications\EntityStatusChanged;
use App\Notifications\EntityUpdated;
use App\Notifications\FederationMemberChanged;
use App\Notifications\IdpCategoryChanged;
use App\Notifications\YourEntityRightsChanged;
use App\Traits\GitTrait;
use App\Traits\ValidatorTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class EntityController extends Controller
{
    use ValidatorTrait, GitTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Entity::class);

        $entities = Entity::query()
            ->visibleTo(Auth::user())
            ->search(request('search'))
            ->orderByDesc('active')
            ->orderByDesc('approved')
            ->orderBy('name_en')
            ->paginate();

        return view('entities.index', [
            'entities' => $entities,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Entity::class);

        return view('entities.create', [
            'federations' => Federation::active()->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEntity $request)
    {
        $this->authorize('create', Entity::class);

        $validated = $request->validated();

        $metadata = $this->getMetadata($request);
        if(!$metadata)
        {
            return redirect()
                ->route('entities.create')
                ->with('status', __('entities.metadata_couldnt_be_read'))
                ->with('color', 'red');
        }

        $result = json_decode($this->validateMetadata($metadata), true);
        $new_entity = json_decode($this->parseMetadata($metadata), true);

        if(array_key_exists('result', $new_entity) && !is_null($new_entity['result']))
        {
            return redirect()
                ->back()
                ->with('status', __('entities.no_metadata'))
                ->with('color', 'red');
        }

        $existing = Entity::whereEntityid($new_entity['entityid'])->first();
        if($existing)
        {
            return redirect()
                ->route('entities.show', $existing)
                ->with('status', __('entities.existing_already'))
                ->with('color', 'yellow');
        }

        switch($result['code'])
        {
            case '0':
                $federation = Federation::findOrFail($validated['federation']);
                $entity = DB::transaction(function() use($new_entity, $federation, $request) {
                    if($new_entity['type'] === 'idp')
                    {
                        $new_entity = array_merge($new_entity, ['hfd' => true]);
                    }
                    $entity = Entity::create($new_entity);
                    $entity->operators()->attach(Auth::id());
                    $entity->federations()->attach($federation, [
                        'explanation' => request('explanation'),
                        'requested_by' => Auth::id(),
                    ]);
                    return $entity;
                });

                $admins = User::activeAdmins()->select('id', 'email')->get();
                Notification::send($admins, new EntityRequested($entity, $federation));

                return redirect('entities')
                    ->with('status', __('entities.entity_requested', ['name' => $entity->entityid]) . ' ' . $result['message']);

                break;

            case '1':
                return redirect()
                    ->back()
                    ->with('status', "{$result['error']} {$result['message']}")
                    ->with('color', 'red');

                break;

            default:
                return back()
                    ->with('status', __('entities.unknown_error_while_registration'))
                    ->with('color', 'red');
                break;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\Response
     */
    public function show(Entity $entity)
    {
        $this->authorize('view', $entity);

        return view('entities.show', [
            'entity' => $entity,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function operators(Entity $entity)
    {
        $this->authorize('view', $entity);

        $operators = $entity->operators()->paginate(10, ['*'], 'operatorsPage');
        $ops = $entity->operators->pluck('id');
        $users = User::orderBy('name')
            ->whereNotIn('id', $ops)
            ->search(request('search'))
            ->paginate(10, ['*'], 'usersPage');

        return view('entities.operators', [
            'entity' => $entity,
            'operators' => $operators,
            'users' => $users,
        ]);
    }

    public function federations(Entity $entity)
    {
        $this->authorize('view', $entity);

        $federations = $entity->federations;
        $requested = $entity->federationsRequested;
        $collection = $federations->concat($requested);
        $joinable = Federation::orderBy('name')
            ->whereNotIn('id', $collection->pluck('id'))
            ->get();

        return view('entities.federations', [
            'entity' => $entity,
            'federations' => $federations,
            'joinable' => $joinable,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\Response
     */
    public function edit(Entity $entity)
    {
        $this->authorize('update', $entity);

        return view('entities.edit', [
            'entity' => $entity,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entity $entity)
    {
        switch(request('action'))
        {
            case 'update':
                $this->authorize('update', $entity);

                $validated = $request->validate([
                    'url' => 'nullable|url',
                    'file' => 'required_without:url|file',
                ]);

                $metadata = $this->getMetadata($request);
                if(!$metadata)
                {
                    return redirect()
                        ->back()
                        ->with('status', __('entities.metadata_couldnt_be_read'))
                        ->with('color', 'red');
                }

                $result = json_decode($this->validateMetadata($metadata), true);
                $updated_entity = json_decode($this->parseMetadata($metadata), true);

                if(array_key_exists('result', $updated_entity) && !is_null($updated_entity['result']))
                {
                    return redirect()
                        ->back()
                        ->with('status', __('entities.no_metadata'))
                        ->with('color', 'red');
                }

                if($entity->entityid !== $updated_entity['entityid'])
                {
                    return redirect()
                        ->back()
                        ->with('status', __('entities.different_entityid'))
                        ->with('color', 'red');
                }

                switch($result['code'])
                {
                    case '0':

                        $entity->update([
                            'name_en' => $updated_entity['name_en'],
                            'name_cs' => $updated_entity['name_cs'],
                            'description_en' => $updated_entity['description_en'],
                            'description_cs' => $updated_entity['description_cs'],
                            'metadata' => $updated_entity['metadata'],
                        ]);

                        if(!$entity->wasChanged())
                        {
                            return redirect()
                                ->back()
                                ->with('status', __('entities.not_changed'));
                        }

                        Bus::chain([
                            new GitUpdateEntity($entity, Auth::user()),
                            function() use($entity) {
                                $admins = User::activeAdmins()->select('id', 'email')->get();
                                Notification::send($entity->operators, new EntityUpdated($entity));
                                Notification::send($admins, new EntityUpdated($entity));
                            },
                        ])->dispatch();

                        return redirect()
                            ->route('entities.show', $entity)
                            ->with('status', __('entities.entity_updated'));

                        break;

                    case '1':
                        return redirect()
                            ->back()
                            ->with('status', "{$result['error']} {$result['message']}")
                            ->with('color', 'red');

                        break;

                    default:
                        return redirect()
                            ->back()
                            ->with('status', __('entities.unknown_error_while_registration'))
                            ->with('color', 'red');

                        break;
                }

                break;

            case 'status':
                $this->authorize('update', $entity);

                $entity->active = $entity->active ? false : true;
                $entity->update();

                $status = $entity->active ? 'active' : 'inactive';
                $color = $entity->active ? 'green' : 'red';

                $admins = User::activeAdmins()->select('id', 'email')->get();
                Notification::send($entity->operators, new EntityStatusChanged($entity));
                Notification::send($admins, new EntityStatusChanged($entity));

                return redirect()
                    ->route('entities.show', $entity)
                    ->with('status', __("entities.$status", ['name' => $entity->name_en]))
                    ->with('color', $color);

                break;

            case 'state':
                $this->authorize('delete', $entity);

                if($entity->trashed())
                {
                    $entity->restore();

                    Bus::chain([
                        new GitAddEntity($entity, Auth::user()),
                        new GitAddToHfd($entity, Auth::user()),
                        function() use($entity) {
                            $admins = User::activeAdmins()->select('id', 'email')->get();
                            Notification::send($entity->operators, new EntityStateChanged($entity));
                            Notification::send($admins, new EntityStateChanged($entity));
                        },
                    ])->dispatch();

                    foreach($entity->federations as $federation)
                    {
                        Bus::chain([
                            new GitAddMember($federation, $entity, Auth::user()),
                            function() use($federation, $entity) {
                                $admins = User::activeAdmins()->select('id', 'email')->get();
                                Notification::send($federation->operators, new FederationMemberChanged($federation, $entity, 'added'));
                                Notification::send($admins, new FederationMemberChanged($federation, $entity, 'added'));
                            },
                        ])->dispatch();
                    }
                }
                else
                {
                    $entity->delete();

                    Bus::chain([
                        new GitDeleteEntity($entity, Auth::user()),
                        new GitDeleteFromHfd($entity, Auth::user()),
                        function() use($entity) {
                            $admins = User::activeAdmins()->select('id', 'email')->get();
                            Notification::send($entity->operators, new EntityStateChanged($entity));
                            Notification::send($admins, new EntityStateChanged($entity));
                        },
                    ])->dispatch();
                }

                $state = $entity->trashed() ? 'deleted' : 'restored';
                $color = $entity->trashed() ? 'red' : 'green';

                return redirect()
                    ->route('entities.show', $entity)
                    ->with('status', __("entities.$state", ['name' => $entity->name_en]))
                    ->with('color', $color);

                break;

            case 'add_operators':
                $this->authorize('update', $entity);

                if(!request('operators'))
                {
                    return redirect()
                        ->route('entities.show', $entity)
                        ->with('status', __('entities.add_empty_operators'))
                        ->with('color', 'red');
                }

                $old_operators = $entity->operators;
                $new_operators = User::whereIn('id', request('operators'))->get();
                $entity->operators()->attach(request('operators'));

                $admins = User::activeAdmins()->select('id', 'email')->get();
                Notification::send($new_operators, new YourEntityRightsChanged($entity, 'added'));
                Notification::send($old_operators, new EntityOperatorsChanged($entity, $new_operators, 'added'));
                Notification::send($admins, new EntityOperatorsChanged($entity, $new_operators, 'added'));

                return redirect()
                    ->route('entities.show', $entity)
                    ->with('status', __('entities.operators_added'));

                break;

            case 'delete_operators':
                $this->authorize('update', $entity);

                if(!request('operators'))
                {
                    return redirect()
                        ->back()
                        ->with('status', __('entities.delete_empty_operators'))
                        ->with('color', 'red');
                }

                $old_operators = User::whereIn('id', request('operators'))->get();
                $entity->operators()->detach(request('operators'));
                $new_operators = $entity->operators;

                $admins = User::activeAdmins()->select('id', 'email')->get();
                Notification::send($old_operators, new YourEntityRightsChanged($entity, 'deleted'));
                Notification::send($new_operators, new EntityOperatorsChanged($entity, $old_operators, 'deleted'));
                Notification::send($admins, new EntityOperatorsChanged($entity, $old_operators, 'deleted'));

                return redirect()
                    ->route('entities.show', $entity)
                    ->with('status', __('entities.operators_deleted'));

                break;

            case 'edugain':
                $this->authorize('update', $entity);

                $entity->edugain = $entity->edugain ? false : true;
                $entity->update();

                $status = $entity->edugain ? 'edugain' : 'no_edugain';
                $color = $entity->edugain ? 'green' : 'red';

                if($entity->edugain)
                {
                    Bus::chain([
                        new GitAddToEdugain($entity, Auth::user()),
                        function() use($entity) {
                            $admins = User::activeAdmins()->select('id', 'email')->get();
                            Notification::send($entity->operators, new EntityEdugainStatusChanged($entity));
                            Notification::send($admins, new EntityEdugainStatusChanged($entity));
                        },
                    ])->dispatch();
                }
                else
                {
                    Bus::chain([
                        new GitDeleteFromEdugain($entity, Auth::user()),
                        function() use($entity) {
                            $admins = User::activeAdmins()->select('id', 'email')->get();
                            Notification::send($entity->operators, new EntityEdugainStatusChanged($entity));
                            Notification::send($admins, new EntityEdugainStatusChanged($entity));
                        },
                    ])->dispatch();
                }

                return redirect()
                    ->back()
                    ->with('status', __("entities.$status"))
                    ->with('color', $color);

                break;

            case 'rs':
                $this->authorize('do-everything');

                if($entity->type !== 'sp')
                {
                    return redirect()
                        ->back()
                        ->with('status', __('categories.rs_controlled_for_sps_only'));
                }

                $entity->rs = $entity->rs ? false : true;
                $entity->update();

                $status = $entity->rs ? 'rs' : 'no_rs';
                $color = $entity->rs ? 'green' : 'red';

                if($entity->rs)
                {
                    GitAddToRs::dispatch($entity, Auth::user());
                }
                else
                {
                    GitDeleteFromRs::dispatch($entity, Auth::user());
                }

                return redirect()
                    ->back()
                    ->with('status', __("entities.$status"))
                    ->with('color', $color);

                break;

            case 'category':
                $this->authorize('do-everything');

                if(empty(request('category')))
                {
                    return redirect()
                        ->back()
                        ->with('status', __('categories.no_category_selected'))
                        ->with('color', 'red');
                }

                $old_category = $entity->category ?? null;
                $category = Category::findOrFail(request('category'));
                $entity->category()->associate($category);
                $entity->save();

                Bus::chain([
                    new GitDeleteFromCategory($old_category, $entity, Auth::user()),
                    new GitAddToCategory($category, $entity, Auth::user()),
                    function() use($entity, $category) {
                        $admins = User::activeAdmins()->select('id', 'email')->get();
                        Notification::send($admins, new IdpCategoryChanged($entity, $category));
                    },
                ])->dispatch();

                if(!$entity->wasChanged())
                {
                    return redirect()
                        ->back();
                }

                return redirect()
                    ->route('entities.show', $entity)
                    ->with('status', __('entities.category_updated'));

                break;

            case 'hfd':
                $this->authorize('do-everything');

                if($entity->type !== 'idp')
                {
                    return redirect()
                        ->back()
                        ->with('status', __('categories.hfd_controlled_for_idps_only'));
                }

                $entity->hfd = $entity->hfd ? false : true;
                $entity->update();

                $status = $entity->hfd ? 'hfd' : 'no_hfd';
                $color = $entity->hfd ? 'red' : 'green';

                if($entity->hfd)
                {
                    GitAddToHfd::dispatch($entity, Auth::user());
                }
                else
                {
                    GitDeleteFromHfd::dispatch($entity, Auth::user());
                }

                return redirect()
                    ->route('entities.show', $entity)
                    ->with('status', __("entities.$status"))
                    ->with('color', $color);

                break;

            default:
                return redirect()->back();
        }
    }

    public function join(JoinFederation $request, Entity $entity)
    {
        $this->authorize('update', $entity);

        if(empty(request('federation')))
        {
            return back()
                ->with('status', __('entities.join_empty_federations'))
                ->with('color', 'red');
        }

        $entity
            ->federations()
            ->attach($request->input('federation'), [
                'requested_by' => Auth::id(),
                'explanation' => $request->input('explanation'),
            ]);

        return redirect()
            ->back()
            ->with('status', __('entities.join_requested', [
                'name' => Federation::findOrFail($request->input('federation'))->name,
            ]));
    }

    public function leave(Request $request, Entity $entity)
    {
        $this->authorize('update', $entity);

        if(empty(request('federations')))
        {
            return back()
                ->with('status', __('entities.leave_empty_federations'))
                ->with('color', 'red');
        }

        $entity
            ->federations()
            ->detach($request->input('federations'));

        foreach(request('federations') as $f)
        {
            $federation = Federation::find($f);
            GitDeleteFromFederation::dispatch($entity, $federation, Auth::user());
        }

        return redirect()
            ->back()
            ->with('status', __('entities.federations_left'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entity $entity)
    {
        $this->authorize('forceDelete', $entity);

        $name = $entity->name_en ?? $entity->entityid;
        $entity->forceDelete();

        $admins = User::activeAdmins()->select('id', 'email')->get();
        Notification::send($admins, new EntityDestroyed($name));

        return redirect('entities')
            ->with('status', __('entities.destroyed', ['name' => $name]));
    }

    public function unknown()
    {
        $this->authorize('do-everything');

        $this->initializeGit();

        $xmlfiles = array();
        $tagfiles = array();
        $missing_federations = array();
        foreach(Storage::files() as $file)
        {
            if(preg_match('/\.xml$/', $file))
            {
                $xmlfiles[] = $file;
            }

            if(preg_match('/\.tag$/', $file))
            {
                if(preg_match('/^' . config('git.edugain_tag') . '$/', $file))
                {
                    continue;
                }

                $federation = Federation::whereTagfile($file)->first();
                if($federation === null)
                {
                    if(Storage::exists(preg_replace('/\.tag/', '.cfg', $file)))
                    {
                        $missing_federations[] = $file;
                    }
                }
                else
                {
                    $tagfiles[] = $file;
                }
            }
        }

        $tagfiles[] = config('git.edugain_tag');

        $entities = Entity::select('file')->get()->pluck('file')->toArray();

        $unknown = array();
        foreach($xmlfiles as $xmlfile)
        {
            if(!in_array($xmlfile, $entities))
            {
                $metadata = Storage::get($xmlfile);
                $metadata = $this->parseMetadata($metadata);
                $entity = json_decode($metadata, true);

                $unknown[$xmlfile]['type'] = $entity['type'];
                $unknown[$xmlfile]['entityid'] = $entity['entityid'];
                $unknown[$xmlfile]['file'] = $xmlfile;
                $unknown[$xmlfile]['name_en'] = $entity['name_en'];
                $unknown[$xmlfile]['name_cs'] = $entity['name_cs'];
                $unknown[$xmlfile]['description_en'] = $entity['description_en'];
                $unknown[$xmlfile]['description_cs'] = $entity['description_cs'];

                foreach($tagfiles as $tagfile)
                {
                    $content = Storage::get($tagfile);
                    $pattern = preg_quote($entity['entityid'], '/');
                    $pattern = "/^$pattern\$/m";

                    if(preg_match_all($pattern, $content))
                    {
                        if(strcmp($tagfile, config('git.edugain_tag')) === 0)
                        {
                            $unknown[$xmlfile]['federations'][] = 'eduGAIN';
                            continue;
                        }

                        $federation = Federation::whereTagfile($tagfile)->first();
                        $unknown[$xmlfile]['federations'][] = $federation->name ?? null;
                    }
                }
            }
        }

        return view('entities.import', [
            'entities' => $unknown,
            'missing_federations' => $missing_federations,
        ]);
    }

    public function import(Request $request)
    {
        $this->authorize('do-everything');

        if(empty(request('entities')))
        {
            return back()
                ->with('status', __('entities.empty_import'))
                ->with('color', 'red');
        }

        $tagfiles = array();
        foreach(Storage::files() as $file)
        {
            if(preg_match('/\.tag$/', $file))
            {
                $tagfiles[] = $file;
            }
        }

        $imported = 0;
        foreach(request('entities') as $xmlfile)
        {
            $metadata = Storage::get($xmlfile);
            $metadata = $this->parseMetadata($metadata);
            $entity = json_decode($metadata, JSON_FORCE_OBJECT);

            $edugain = false;
            $hfd = false;
            $rs = $entity['rs'];
            foreach($tagfiles as $tagfile)
            {
                $content = Storage::get($tagfile);
                $pattern = preg_quote($entity['entityid'], '/');
                $pattern = "/^$pattern\$/m";

                if(preg_match_all($pattern, $content))
                {
                    if(strcmp($tagfile, config('git.edugain_tag')) === 0)
                    {
                        $edugain = true;
                    }

                    if(strcmp($tagfile, config('git.hfd')) === 0)
                    {
                        $hfd = true;
                    }

                    if(strcmp($tagfile, config('git.ec_rs')) === 0)
                    {
                        $rs = true;
                    }

                    $federation = Federation::whereTagfile($tagfile)->first();

                    DB::transaction(function() use($entity, $federation, $edugain, $hfd, $rs) {
                        $entity = Entity::updateOrCreate($entity, [
                            'edugain' => $edugain,
                            'hfd' => $hfd,
                            'rs' => $rs,
                        ]);
                        $entity->federations()->attach($federation, [
                            'requested_by' => Auth::id(),
                            'approved_by' => Auth::id(),
                            'approved' => true,
                            'explanation' => 'Imported from Git repository.',
                        ]);

                        $entity->approved = true;
                        $entity->active = false;
                        $entity->update();
                    });
                }
            }

            $imported++;
        }

        return redirect('entities')
            ->with('status', trans_choice('entities.imported', $imported));
    }

    public function refresh()
    {
        $this->authorize('do-everything');

        $this->initializeGit();

        $xmlfiles = array();
        foreach(Storage::files() as $file)
        {
            if(preg_match('/\.xml/', $file))
            {
                $xmlfiles[] = $file;
            }
        }

        $entities = Entity::select('file')->get()->pluck('file')->toArray();

        $refreshed = 0;
        foreach($xmlfiles as $xmlfile)
        {
            if(in_array($xmlfile, $entities))
            {
                $metadata = Storage::get($xmlfile);
                $refreshed_entity = json_decode($this->parseMetadata($metadata), true);

                $entity = Entity::whereFile($xmlfile)->first();
                $entity->update($refreshed_entity);

                $edugain = Storage::get(config('git.edugain_tag'));
                $pattern = preg_quote($refreshed_entity['entityid'], '/');
                $pattern = "/^$pattern\$/m";
                if(preg_match_all($pattern, $edugain))
                {
                    $entity->update(['edugain' => true]);
                }
                else
                {
                    $entity->update(['edugain' => false]);
                }

                if($entity->type === 'idp')
                {
                    $hfd = Storage::get(config('git.hfd'));
                    $pattern = preg_quote($refreshed_entity['entityid'], '/');
                    $pattern = "/^$pattern\$/m";
                    if(preg_match_all($pattern, $hfd))
                    {
                        $entity->update(['hfd' => true]);
                    }
                    else
                    {
                        $entity->update(['hfd' => false]);
                    }
                }

                if($entity->type === 'sp')
                {
                    $rs = Storage::get(config('git.ec_rs'));
                    $pattern = preg_quote($refreshed_entity['entityid'], '/');
                    $pattern = "/^$pattern\$/m";
                    if(preg_match_all($pattern, $rs))
                    {
                        $entity->update(['rs' => true]);
                    }
                    else
                    {
                        $entity->update(['rs' => false]);
                    }
                }

                if($entity->wasChanged())
                {
                    $refreshed++;
                }
            }
        }

        return redirect('entities')
            ->with('status', trans_choice('entities.refreshed', $refreshed));
    }

    public function rs(Entity $entity)
    {
        $this->authorize('update', $entity);

        abort_unless($entity->federations()->where('xml_name', 'https://eduid.cz/metadata')->count(), 403, __('entities.rs_only_for_eduidcz_members'));

        Mail::to(config('mail.admin.address'))
            ->send(new AskRs($entity));

        return redirect()
            ->back()
            ->with('status', __('entities.rs_asked'));
    }
}
