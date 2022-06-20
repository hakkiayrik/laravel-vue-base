<?php

namespace App\Http\Controllers\Api\v1\Panel;

use App\Http\Requests\DataTableRequest;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\CategoryCollection;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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
		$categories = Category::query();

		if(strlen($search) > 0) {
			$categories->whereTranslationLike("name", "%$search%");
		}

		if(count($sortBy) > 0) {
			foreach ($sortBy as $index => $item) {
				$categories->orderBy($item, $sortDesc[$index] ? 'DESC' : 'ASC');
			}
		}

		$categories = $categories->latest()->paginate($itemsPerPage, ['*'], 'page', $page);

		return $this->success(new CategoryCollection($categories));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
		$category = Category::create($request->all());
		return $this->success('', __('panel.transaction_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
		$category = Category::where('id', $id)->firstOrFail();
		return $this->success(new CategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
		$category = Category::findOrFail($id)->update($request->all());

		return $this->success('', __('panel.transaction_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

		return $this->success('', __('panel.transaction_success'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDisplayOrder(Request $request)
    {

        $items = $request->get('items');
        if (count($items)) {
            foreach ($items as $index => $item) {
                Category::find($item)->update(['display_order' => $index]);
            }
            return $this->success([], __('panel.transaction_success'));
        }

        return $this->sendError('', [], 400);
    }
}
