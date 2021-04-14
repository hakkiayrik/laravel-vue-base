<?php

namespace App\Http\Controllers\Api\v1\Panel;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Role as RoleResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends BaseController
{
	use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->sendResponse(RoleResource::collection(Role::get()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
        	"name" => "required|max:25"
		]);

        if($validator->fails()) {
        	$this->error($validator->errors(), '', 400);
		}

		$role = Role::create([ 'name' => $request->input('name') ]);

        $permissions = $request->input('permissions');

        $permissionList = [];
        foreach($permissions as $permission) {
			$permissionList[] = Permission::findOrCreate($permission);
		}
		$role->syncPermissions($permissionList);

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
		$role = Role::where('id', $id)->firstOrFail();
		return $this->success(new RoleResource($role));
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
		$validator = Validator::make($request->all(), [
			"name" => "required|max:25"
		]);

		if($validator->fails()) {
			$this->error($validator->errors(),'', 400);
		}

		$role = Role::findOrFail($id);

		$role->update(['name' => $request->input('name')]);

		$permissions = $request->input('permissions');

		$permissionList = [];
		foreach($permissions as $permission) {
			$permissionList[] = Permission::findOrCreate($permission);
		}
		$role->syncPermissions($permissionList);

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
        Role::destroy($id);
        return $this->success('', __('panel.transaction_success'));
    }
}
