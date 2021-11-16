<?php

namespace App\Http\Controllers\Views\Backend;

use Auth;
use App\Models\Page;
use App\Traits\SlugTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    use SlugTrait;

    /**
     * list pages
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        try
        {
            $status = null;
            if ( $request->has('status') && $request->status != '' ) {
                if ( in_array($request->status, ['published', 'unpublished']) ) {
                    $status = $request->status;
                }
            }

            $keywords = $request->keywords;

            $pages = Page::when($keywords, function($query) use ($keywords) {
                return $query->where('title', 'like', '%' . $keywords . '%');
            })
            ->when($status, function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('id', 'desc')
            ->paginate(50);

            return view('backend.pages.index', compact('pages', 'keywords'));
        }
        catch (Exception $e) {
            return redirect()->back()->withErrors($e);
        }
    }



    /**
     * Create new page
     *
     * @return view()
     */
    public function create()
    {
        return view('backend.pages.create');
    }


    /**
     * Store a new page
     *
     * @param  Request $request
     *
     * @return redirect()
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'slug'  => 'required'
            ]);

            if($validator->fails())
                return redirect()->back()->withErrors(['validator' => 'Title & slug are required']);

            //Check if the slug exists using slug trait
            $slug = $this->getUniqueSlug($request->slug, 'pages');

            $page = Page::create([
                'title'             => $request->title,
                'slug'              => $slug,
                'tags'              => $request->tags,
                'image'             => $request->image,
                'template'          => 'default',
                'excerpt'           => $request->excerpt,
                'content'           => $request->content,
                'status'            => $request->status,
                'last_updated_by'   => Auth::user()->id
            ]);

            return redirect()->route('admin.pages.edit', $page->id)
                    ->with('message', 'La page a bien été crée !');

        } catch (\Exception $e) {
            return redirect()->back()
            ->withInputs($request->all())
            ->withErrors($e->getMessage());
        }

    }


    /**
     * Display page for editing
     *
     * @param  integer $id page's id
     *
     * @return view()
     */
    public function edit($id)
    {
        $page = Page::find($id);
        if ( !$page ) return redirect()->route('pages.index');
        return view('backend.pages.edit', compact('page'));
    }



    /**
     * Update a page
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'title' => 'required'
            ]);

            if($validator->fails())
                return redirect()->back()->withErrors(['validator' => 'Title is required']);


            $page = Page::find($id);
            if ( !$page ) return redirect()->back();

            $page->tags             = $request->tags ? $request->tags : $page->tags;
            $page->title            = $request->title ? $request->title : $page->title;
            $page->image            = $request->image ? $request->image : $page->image;
            $page->status           = $request->status ? $request->status : $page->status;
            $page->template         = $request->template ? $request->template : $page->template;
            $page->excerpt          = $request->excerpt ? $request->excerpt : $page->excerpt;
            $page->content          = $request->content ? $request->content : $page->content;
            $page->last_updated_by  = Auth::user()->id;
            $page->save();

            return redirect()->route('admin.pages.edit', $page->id)
                ->with('message', 'La page a bien été mis a jour !');
        }
        catch (Exception $e) {
            return redirect()->back()->withErrors($e);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $page = Page::find($id);

            $delete = $page->delete();
            if ($delete) {
               return redirect()->route('admin.pages.index')
                   ->with('message', 'La page a bien été supprimer !');
            }
        }catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['message'=>'internal server error'],500);
        }
    }

}
