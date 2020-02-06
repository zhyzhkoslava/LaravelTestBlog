<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\BlogPostUpdateRequest;
use App\Models\BlogPost;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use App\Http\Requests\BlogPostCreateRequest;

/**
 * Manage posts of the blog
 *
 * Class PostController
 * @package App\Http\Controllers\Blog\Admin
 */
class PostController extends BaseController
{
    /** @var BlogPostRepository */
    private $blogPostRepository;

    /** @var BlogCategoryRepository */
    private $blogCategoryRepository;

    public function __construct()
    {
        parent::__construct();

        $this->blogPostRepository = app(BlogPostRepository::class); // blogPostRepository = new BlogPostRepository
        $this->blogCategoryRepository = app(BlogCategoryRepository::class); // blogCategoryRepository  = new BlogCategoryRepository
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate();

        return view('blog.admin.posts.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $item = new BlogPost();
        $categoryList = $this->blogCategoryRepository->getForComboBox();

        return view('blog.admin.posts.edit', compact('item', 'categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BlogPostCreateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BlogPostCreateRequest $request)
    {
        $item = new BlogPost();
        $item->fill($request->only($item->getFillable())); // ?????
        $item->save();

        if ($item) {
            return redirect()->route('blog.admin.posts.edit', [$item->id])->with(['success' => 'Saved successfully']);
        } else {
            return back()->withErrors(['msg' => 'Save failing'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd(__METHOD__);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            abort(404);
        }

        $categoryList = $this->blogCategoryRepository->getForComboBox();

        return view('blog.admin.posts.edit', compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(BlogPostUpdateRequest $request, $id)
    {

        $item = $this->blogPostRepository->getEdit($id);

        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Record id=[{$id}] not found"])
                ->withInput();
        }

        $data = $request->all();

//        dd($data);

        //Check logic move to Observers!!!! App\Observers\BlogPostObserver !!!!

        $result = $item->update($data);

        if ($result) {
            return redirect()
                ->route('blog.admin.posts.edit', $item->id)
                ->with(['success' => 'Successfully saved!']);
        } else {
            return back()
                ->withErrors(['msg' => 'Saving fail'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
       $result = BlogPost::destroy($id);

       if ($result) {
           return redirect()
               ->route('blog.admin.posts.index')
               ->with(['success' => "Record id[$id] deleted"]);
       } else {
           return back()->withErrors(['msg' => 'Delete error!']);
       }
    }
}
