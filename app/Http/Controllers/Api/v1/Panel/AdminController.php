<?php

namespace App\Http\Controllers\Api\v1\Panel;

use App\Http\Requests\DataTableRequest;
use App\Models\Admin;
use App\Helpers\Logger\DbLog;
use App\Helpers\Logger\Logger;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminCollection;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Admin as AdminResource;
use Illuminate\Support\Facades\Validator;

class AdminController extends BaseController
{
	use ApiResponse;

    /**
     * Display a listing of the resource.
     *
	 * @param DataTableRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(DataTableRequest $request)
    {
		$sortBy = $request->input('sortBy', []);
		$sortDesc = $request->input('sortDesc', []);
		$itemPerPage = $request->input('itemPerPage');
		$search = $request->input('search');
		$admins = Admin::query();

		if(strlen($search) > 0) {
			$admins->where('username', 'LIKE', "%$search%")
				->orWhere('name', 'LIKE', "%$search%")
				->orWhere('email', 'LIKE', "%$search%");
		}

		if(count($sortBy) > 0) {
			foreach ($sortBy as $index => $item) {
				$admins->orderBy($item, $sortDesc[$index] ? 'DESC' : 'ASC');
			}
		}

		$admins = $admins->latest()->paginate($itemPerPage);

		return $this->success(new AdminCollection($admins));
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
			"first_name" => "required|max:25|min:2",
			"last_name" => "required|max:25|min:2",
			"username" => "required|max:25|min:3|unique:admins",
			"email" => "required|email|unique:admins",
			"password" => "required|min:6|max:25|regex:/^(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]*$/",
			"password_confirm" => "required_with:password|min:6|max:25",
			'role' => 'required'
		]);

		if($validator->fails()) {
			$this->error($validator->errors(), '',400);
		}

		$admin = new Admin();

		$admin->first_name = $request->first_name;
		$admin->last_name = $request->last_name;
		$admin->username = $request->username;
		$admin->email = $request->email;
		$admin->password = $request->password;
		if(!empty($request->avatar_id)) {
			$admin->avatar_id = $request->avatar_id;
		}
		$admin->save();

		$admin->assignRole($request->input('role'));

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
        $admin = Admin::where('id', $id)->firstOrFail();
		return $this->success(new AdminResource($admin));
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
			"first_name" => "required|max:25|min:2",
			"last_name" => "required|max:25|min:2",
			"status" => "required",
			"password" => "min:6|max:25|regex:/^(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]*$/"
		]);

		if($validator->fails()) {
			$this->error($validator->errors(), '',400);
		}

		$admin = Admin::findOrFail($id);

		$admin->first_name = $request->first_name;
		$admin->last_name = $request->last_name;
		$admin->status = $request->status;
		if(!empty($request->password)) {
			$admin->password = bcrypt($request->password);
		}
		$admin->save();

		$admin->syncRoles([$request->input('role')]);

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
		$admin = Admin::findOrFail($id);
		$admin->delete();
		return $this->sendResponse('', __('panel.transaction_success'));
    }
}
