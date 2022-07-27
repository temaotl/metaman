<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroup;
use App\Http\Requests\UpdateGroup;
use App\Jobs\GitAddGroup;
use App\Jobs\GitDeleteGroup;
use App\Jobs\GitUpdateGroup;
use App\Models\Category;
use App\Models\Entity;
use App\Models\Group;
use App\Models\User;
use App\Notifications\GroupCreated;
use App\Notifications\GroupDeleted;
use App\Notifications\GroupUpdated;
use App\Traits\GitTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    use GitTrait;

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
        $this->authorize('do-everything');

        return view('groups.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('do-everything');

        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGroup $request)
    {
        $this->authorize('do-everything');

        $validated = $request->validated();
        $id = generateFederationID($validated['name']);

        $group = Group::create(array_merge(
            $validated,
            ['tagfile' => "$id.tag"],
        ));

        GitAddGroup::dispatch($group, Auth::user());

        return redirect('groups')
            ->with('status', __('groups.added', ['name' => $group->name]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        $this->authorize('do-everything');

        return view('groups.show', [
            'group' => $group,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $this->authorize('do-everything');

        return view('groups.edit', [
            'group' => $group,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGroup $request, Group $group)
    {
        $this->authorize('do-everything');

        $old_group = $group->tagfile;
        $validated = $request->validated();
        $group->update($validated);

        if (!$group->wasChanged()) {
            return redirect()
                ->route('groups.show', $group);
        }

        GitUpdateGroup::dispatch($old_group, $group, Auth::user());

        return redirect()
            ->route('groups.show', $group)
            ->with('status', __('groups.updated', ['name' => $old_group]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $this->authorize('do-everything');

        if ($group->entities->count() !== 0) {
            return redirect()
                ->route('groups.show', $group)
                ->with('status', __('groups.delete_empty'))
                ->with('color', 'red');
        }

        $name = $group->tagfile;
        $group->delete();

        GitDeleteGroup::dispatch($name, Auth::user());

        return redirect('groups')
            ->with('status', __('groups.deleted', ['name' => $name]));
    }

    public function unknown()
    {
        $this->authorize('do-everything');

        $this->initializeGit();

        $tagfiles = array();
        foreach (Storage::files() as $file) {
            if (preg_match('/^' . config('git.edugain_tag') . '$/', $file)) continue;
            if (preg_match('/^' . config('git.hfd') . '$/', $file)) continue;
            if (preg_match('/^' . config('git.ec_rs') . '$/', $file)) continue;

            if (preg_match('/\.tag$/', $file)) $tagfiles[] = $file;
        }

        $groups = Group::select('tagfile')->get()->pluck('tagfile')->toArray();
        $categories = Category::select('tagfile')->get()->pluck('tagfile')->toArray();

        $unknown = array();
        foreach ($tagfiles as $tagfile) {
            if (in_array($tagfile, $groups) || in_array($tagfile, $categories)) continue;

            $cfgfile = preg_replace('/\.tag$/', '.cfg', $tagfile);
            if (!Storage::exists($cfgfile)) $unknown[] = $tagfile;
        }

        if (empty($unknown))
            return redirect()
                ->route('groups.index')
                ->with('status', __('groups.nothing_to_import'));

        return view('groups.import', [
            'groups' => $unknown,
        ]);
    }

    public function import(Request $request)
    {
        $this->authorize('do-everything');

        if (empty(request('groups')))
            return back()
                ->with('status', __('groups.empty_import'))
                ->with('color', 'red');

        $imported = 0;
        $names = request('names');
        $descriptions = request('descriptions');
        foreach (request('groups') as $group) {
            if (empty($names[$group]))
                $names[$group] = preg_replace('/\.tag$/', '', $group);

            if (empty($descriptions[$group]))
                $descriptions[$group] = preg_replace('/\.tag$/', '', $group);

            DB::transaction(function () use ($group, $names, $descriptions) {
                Group::create([
                    'name' => $names[$group],
                    'description' => $descriptions[$group],
                    'tagfile' => $group,
                ]);
            });

            $imported++;
        }

        return redirect('groups')
            ->with('status', trans_choice('groups.imported', $imported));
    }

    public function refresh()
    {
        $this->authorize('do-everything');

        $this->initializeGit();

        if (!Group::count())
            return redirect()
                ->route('groups.index')
                ->with('status', __('groups.no_groups'))
                ->with('color', 'red');

        DB::delete('DELETE FROM entity_group');

        foreach (Group::select('id', 'tagfile')->get() as $group) {
            $members = explode("\n", trim(Storage::get($group->tagfile)));

            if (!count($members)) continue;

            foreach ($members as $entityid)
                Entity::whereEntityid($entityid)->first()->groups()->syncWithoutDetaching($group);
        }

        return redirect('groups')
            ->with('status', __('groups.refreshed'));
    }
}
