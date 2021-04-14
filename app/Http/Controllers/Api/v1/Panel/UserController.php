<?php

namespace App\Http\Controllers\Api\v1\Panel;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\Avatar;
use App\Models\UserLog;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserLog as UserLogResource;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
	use ApiResponse;

    /**
     * Display a listing of the resource.
	 *
	 * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    		'sortBy' => 'array',
			'sortDesc' => 'array',
			'page' => 'required|integer',
			'itemsPerPage' => 'required|max:250|integer',
		]);

		if ($validator->fails()) {
			return $this->sendResponse([]);
		}

		$sortBy = $request->input('sortBy', []);
		$sortDesc = $request->input('sortDesc', []);
		$itemPerPage = $request->input('itemPerPage');
		$search = $request->input('search');
		$users = User::query();

		if(strlen($search) > 0) {
			$users->where('username', 'LIKE', "%$search%")
				->orWhere('first_name', 'LIKE', "%$search%")
				->orWhere('last_name', 'LIKE', "%$search%")
				->orWhere('email', 'LIKE', "%$search%");
		}

		if(count($sortBy) > 0) {
			foreach ($sortBy as $index => $item) {
				$users->orderBy($item, $sortDesc[$index] ? 'DESC' : 'ASC');
			}
		}

		$users = $users->latest()->paginate($itemPerPage);

		return $this->success(new UserCollection($users));
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::where('id', $id)->firstOrFail();
		return $this->success(new UserResource($user));
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
			"first_name" => "required|max:50|min:2",
			"last_name" => "required|max:50|min:2",
			"status" => "required",
			"password" => "min:6|max:25|regex:/^(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]*$/"
		]);

		if($validator->fails()) {
			$this->error($validator->errors(), '', 400);
		}

		$user = User::findOrFail($id);

		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->status = $request->status;
		if(!empty($request->password)) {
			$user->password = bcrypt($request->password);
		}
		$user->save();

		//create user log
		$logData["user_id"] = $id;
		$logData["type"] = "update";
		$logData["message_lang_code"] = "user_update_message";
		$logData["created_by"] = auth()->user()->id;
		$logData["data"] = json_encode($request->all());
		UserLog::create($logData);


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
		$user = User::findOrFail($id);
		$user->delete();
		return $this->success('', __('panel.transaction_success'));
    }


    public function userLogs($id)
	{
		$userLogs = UserLog::where('user_id', $id)->orderBy('id', 'desc')->get();

		return $this->success(UserLogResource::collection($userLogs));
	}

	public function getAvatars()
	{
		$userLogs = Avatar::all();
	}
}
