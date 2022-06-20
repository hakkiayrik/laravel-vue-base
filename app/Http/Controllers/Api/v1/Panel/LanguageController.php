<?php

namespace App\Http\Controllers\Api\v1\Panel;

use App\Http\Requests\DataTableRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageCollection;
use App\Models\Language;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
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
        $itemsPerPage = $request->input('itemsPerPage', 25);
        $search = $request->input('search');
        $active = $request->input('active', false);
        $languages = Language::query();

        if(strlen($search) > 0) {
            $languages->where('name', 'LIKE', "%$search%")
                ->orWhere('lang_code', 'LIKE', "%$search%");
        }

        if(count($sortBy) > 0) {
            foreach ($sortBy as $index => $item) {
                $languages->orderBy($item, $sortDesc[$index] ? 'DESC' : 'ASC');
            }
        }

        if($active) {
            $languages->active();
        }

        $languages = $languages->paginate($itemsPerPage, ['*'], 'page', $page);

        return $this->success(new LanguageCollection($languages));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateActive(Request $request, $id)
    {
        Language::findOrFail($id)->update($request->all());

        return $this->success('', __('panel.transaction_success'));
    }
}
