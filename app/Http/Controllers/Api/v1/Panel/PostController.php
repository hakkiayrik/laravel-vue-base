<?php

namespace App\Http\Controllers\Api\v1\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataTableRequest;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\Post as PostResource;
use App\Http\Resources\PostCollection;
use App\Models\Post;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
	use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(DataTableRequest $request)
    {
        $page = $request->input('page', 1);
        $sortBy = $request->input('sortBy', []);
        $sortDesc = $request->input('sortDesc', []);
        $itemsPerPage = $request->input('itemsPerPage');
        $search = $request->input('search');
        $posts = Post::query();

        if(strlen($search) > 0) {
            $posts->whereTranslationLike("title", "%$search%");
        }

        if(count($sortBy) > 0) {
            foreach ($sortBy as $index => $item) {
                $posts->orderBy($item, $sortDesc[$index] ? 'DESC' : 'ASC');
            }
        }

        $posts = $posts->latest()->paginate($itemsPerPage, ['*'], 'page', $page);

        return $this->success(new PostCollection($posts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PostCreateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PostCreateRequest $request)
    {
    	$data = $request->all();

    	$data["admin_id"] = auth()->user()->id;
		$post = Post::create($data);
        //$post->categories()->save($data['categories']);

		return $this->success($post, __('panel.transaction_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $post = Post::where('id', $id)->firstOrFail();
        return $this->success(new PostResource($post));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PostUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PostUpdateRequest $request, $id)
    {
        $data = $request->all();

        $data["admin_id"] = auth()->user()->id;
        $post = Post::findOrFail($id);
        $post->update($data);
        $post->categories()->sync($data['categories']);

        return $this->success($post, __('panel.transaction_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
