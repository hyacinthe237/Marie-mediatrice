<?php

namespace App\Http\Controllers\Views\Backend;

use App\Traits\SlugTrait;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProjectCategoryController extends Controller
{
    use SlugTrait;

    /**
     * list projects
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        try
        {
            $keywords = $request->keywords;

            $categories = ProjectCategory::when($keywords, function($query) use ($keywords) {
                return $query->where('name', 'like', '%' . $keywords . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(50);

            return view('backend.projects.categories.index', compact('categories', 'keywords'));
        }
        catch (Exception $e) {
            return redirect()->back()->withErrors($e);
        }
    }

    /**
     * Create new project
     *
     * @return view()
     */
    public function create()
    {
        return view('backend.projects.categories.create');
    }

    /**
     * Store a new project
     *
     * @param  Request $request
     *
     * @return redirect()
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required'
        ]);

        if($validator->fails())
            return redirect()->back()->withErrors(['validator' => 'name & slug are required']);

        //Check if the slug exists using slug trait
        $slug = $this->getUniqueSlug($request->slug, 'project_categories');

        $category = ProjectCategory::create([
            "name"        => $request->name,
            "slug"         => $slug
        ]);

        return redirect()->route('admin.categories.edit', ['slug' => $category->slug]);
    }

    /**
     * Display project for editing
     *
     * @param  integer $id project's id
     *
     * @return view()
     */
    public function edit($slug)
    {
        $category = ProjectCategory::whereSlug($slug)->firstOrFail();

        return view('backend.projects.categories.edit', ['category' => $category]);
    }

    /**
     * Update a project
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $slug)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'slug' => 'required'
            ]);

            if($validator->fails())
                return redirect()->back()->withErrors(['validator' => 'name & slug are required']);

            $category = ProjectCategory::whereSlug($slug)->firstOrFail();

            $slug = $this->getUniqueSlug($request->slug, 'project_categories');

            $category->name        = $request->has('name') ? $request->name : $category->name;
            $category->slug         = $request->has('slug') ? $slug : $category->slug;
            $category->save();

            return redirect()->back()->with('message', 'Project category successfully update!');
        }
        catch (Exception $e) {
            return redirect()->back()->withErrors($e);
        }

    }

    /**
     * [Delete ProjectCategory]
     * @param  [type] $slug [description]
     * @return [type]              [description]
     */
    public function destroy($slug)
    {
        try {
            $category = ProjectCategory::whereSlug($slug)->first();

            if (!$category)
               return redirect()->back()->withErrors(['message' => 'Project category not found']);

            $project = Project::whereCategoryId($category->id)->first();
            if ($project)
               return redirect()->back()->withErrors(['message' => 'Impossible de supprimer cette catégorie, un projet y est associé']);

            $category->delete();

            return redirect()->route('admin.categories')
                ->withSuccess('La catégorie a été supprimée avec success !');

        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
