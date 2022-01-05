<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategory;
use App\Http\Requests\UpdateCategory;
use App\Jobs\GitAddCategory;
use App\Jobs\GitDeleteCategory;
use App\Jobs\GitUpdateCategory;
use App\Models\Category;
use App\Models\Entity;
use App\Models\Group;
use App\Models\User;
use App\Notifications\CategoryCreated;
use App\Notifications\CategoryDeleted;
use App\Notifications\CategoryUpdated;
use App\Traits\GitTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
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

        $categories = Category::query()
            ->search(request('search'))
            ->orderBy('name')
            ->paginate();

        return view('categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('do-everything');

        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategory $request)
    {
        $this->authorize('do-everything');

        $validated = $request->validated();
        $id = generateFederationID($validated['name']);

        $category = Category::create(array_merge(
            $validated,
            ['tagfile' => "$id.tag"],
        ));

        GitAddCategory::dispatch($category, Auth::user());

        return redirect('categories')
            ->with('status', __('categories.added', ['name' => $category->name]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $this->authorize('do-everything');

        return view('categories.show', [
            'category' => $category,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $this->authorize('do-everything');

        return view('categories.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategory $request, Category $category)
    {
        $this->authorize('do-everything');

        $old_category = $category->tagfile;
        $validated = $request->validated();
        $category->update($validated);

        if(!$category->wasChanged())
        {
            return redirect()
                ->route('categories.show', $category);
        }

        GitUpdateCategory::dispatch($old_category, $category, Auth::user());

        return redirect()
            ->route('categories.show', $category)
            ->with('status', __('categories.updated', ['name' => $old_category]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->authorize('do-everything');

        if($category->entities->count() !== 0)
        {
            return redirect()
                ->route('categories.show', $category)
                ->with('status', __('categories.delete_empty'))
                ->with('color', 'red');
        }

        $name = $category->tagfile;
        $category->delete();

        GitDeleteCategory::dispatch($name, Auth::user());

        return redirect('categories')
            ->with('status', __('categories.deleted', ['name' => $name]));
    }

    public function unknown()
    {
        $this->authorize('do-everything');

        $this->initializeGit();

        $tagfiles = array();
        foreach(Storage::files() as $file)
        {
            if(preg_match('/^' . config('git.edugain_tag') . '$/', $file))
            {
                continue;
            }

            if(preg_match('/^' . config('git.hfd') . '$/', $file))
            {
                continue;
            }

            if(preg_match('/^' . config('git.ec_rs') . '$/', $file))
            {
                continue;
            }

            if(preg_match('/\.tag$/', $file))
            {
                $tagfiles[] = $file;
            }
        }

        $categories = Category::select('tagfile')->get()->pluck('tagfile')->toArray();
        $groups = Group::select('tagfile')->get()->pluck('tagfile')->toArray();

        $unknown = array();
        foreach($tagfiles as $tagfile)
        {
            if(!in_array($tagfile, $categories) && !in_array($tagfile, $groups))
            {
                $cfgfile = preg_replace('/\.tag$/', '.cfg', $tagfile);
                if(!Storage::exists($cfgfile))
                {
                    $unknown[] = $tagfile;
                }
            }
        }

        return view('categories.import', [
            'categories' => $unknown,
        ]);
    }

    public function import(Request $request)
    {
        $this->authorize('do-everything');

        if(empty(request('categories')))
        {
            return back()
                ->with('status', __('categories.empty_import'))
                ->with('color', 'red');
        }

        $imported = 0;
        $names = request('names');
        $descriptions = request('descriptions');
        foreach(request('categories') as $category)
        {
            $content = Storage::get($category);

            if(empty($names[$category]))
            {
                $names[$category] = preg_replace('/\.tag/', '', $category);
            }

            if(empty($descriptions[$category]))
            {
                $descriptions[$category] = preg_replace('/\.tag/', '', $category);
            }

            DB::transaction(function() use($category, $names, $descriptions) {
                Category::create([
                    'name' => $names[$category],
                    'description' => $descriptions[$category],
                    'tagfile' => $category,
                ]);
            });

            $imported++;
        }

        return redirect('categories')
            ->with('status', trans_choice('categories.imported', $imported));
    }

    public function refresh()
    {
        $this->authorize('do-everything');

        $this->initializeGit();

        $categories = Category::select('tagfile')->get()->pluck('tagfile')->toArray();

        foreach($categories as $category)
        {
            $content = trim(Storage::get($category));
            $content = explode("\n", $content);

            $category = Category::whereTagfile($category)->first();

            if(count($content) >= 1)
            {
                foreach($content as $entityid)
                {
                    $entity = Entity::whereEntityid($entityid)->first();
                    $entity->category()->associate($category);
                    $entity->save();
                }
            }
        }

        return redirect('categories')
            ->with('status', __('categories.refreshed'));
    }
}
