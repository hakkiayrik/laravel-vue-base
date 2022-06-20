<?php

namespace App\Http\Controllers\Api\v1\Panel;

use App\Http\Controllers\BaseController;
use App\Http\Requests\AttributeCreateRequest;
use App\Http\Requests\AttributeGroupCreateRequest;
use App\Http\Requests\AttributeUpdateRequest;
use App\Http\Requests\DataTableRequest;
use App\Http\Resources\AttributeGroup as AttributeGroupResource;
use App\Http\Resources\Attribute as AttributeResource;
use App\Http\Resources\AttributeGroupCollection;
use App\Http\Resources\AttributeCollection;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AttributeController extends BaseController
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @param  DataTableRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(DataTableRequest $request)
    {
        $page = $request->input('page', 1);
        $sortBy = $request->input('sortBy', []);
        $sortDesc = $request->input('sortDesc', []);
        $itemsPerPage = $request->input('itemsPerPage');
        $search = $request->input('search');
        $attributeGroups = AttributeGroup::query();

        if(strlen($search) > 0) {
            $attributeGroups->where("name", 'LIKE', "%$search%");
        }

        if(count($sortBy) > 0) {
            foreach ($sortBy as $index => $item) {
                $attributeGroups->orderBy($item, $sortDesc[$index] ? 'DESC' : 'ASC');
            }
        }

        $attributeGroups = $attributeGroups->latest()->paginate($itemsPerPage, ['*'], 'page', $page);

        return $this->success(new AttributeGroupCollection($attributeGroups));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AttributeGroupCreateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AttributeGroupCreateRequest $request)
    {
        $attributeGroup = AttributeGroup::create($request->all());
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
        $attributeGroup = AttributeGroup::where('id', $id)->firstOrFail();
        return $this->success(new AttributeGroupResource($attributeGroup));
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
        dd($request->all());
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttributes()
    {
        $attributes = Attribute::all();

        return $this->success(new AttributeCollection($attributes));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttribute($id)
    {
        $attribute = Attribute::where('id', $id)->firstOrFail();

        return $this->success(new AttributeResource($attribute));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  AttributeCreateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAttribute(AttributeCreateRequest $request)
    {
        $data = $request->all();
        $data["html_name"] = createSlugOrHtmlName($data["label"], "_");

        $attribute = Attribute::create($data);
        return $this->success('', __('panel.transaction_success'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AttributeUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function editAttribute(AttributeUpdateRequest $request, $id)
    {
        $data = $request->all();
        $data["html_name"] = createSlugOrHtmlName($data["label"], "_");

        $attribute = Attribute::findOrFail($id)->update($data);
        return $this->success('', __('panel.transaction_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAttribute($id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();

        return $this->success('', __('panel.transaction_success'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  AttributeCreateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAttributeToGroup(Request $request, $id)
    {
        $attributeGroup = AttributeGroup::findOrFail($id);
        $attributeGroup->attributes()->sync($request->all());

        return $this->success('', __('panel.transaction_success'));
    }
}
