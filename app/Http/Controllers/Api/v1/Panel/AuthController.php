<?php

namespace App\Http\Controllers\Api\v1\Panel;

use App\Models\Admin;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Admin as AdminResource;
use App\Http\Controllers\Controller;
use App\Models\UserLog;
use App\Notifications\PasswordReset;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends BaseController
{

	use ApiResponse;

	public function login(Request $request)
	{
		$validator = Validator::make($request->all(), [
			"username" => "required|max:25",
			"password" => "required"
		]);

		if($validator->fails())
		{
			return $this->error($validator->errors(), '', 400);
		}

		$requestData = $request->only(['username', 'password']);
		if(!$token = auth()->guard('panel')->attempt($requestData)) {
            return $this->error(['username' => [__('auth.failed')]], '' , 401);
        }

		$success['token'] = $token;

		return $this->success($success, '');
	}

	public function forgotPassword(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'required|email'
		]);

		if($validator->fails()) {
			return $this->error($validator->errors(), '', 400);
		}

		$user = Admin::where('email', $request->input('email'))->first();

		if(empty($user)) {
			return $this->error(["email" => [__('password.admin')]], '', 400);
		}

		$resetToken = Str::random(60);
		$passwordReset = DB::table(config('auth.passwords.admins.table'))->updateOrInsert(['email' => $user->email],[
			'email' => $user->email,
			'token' => $resetToken,
			'created_at' => date('Y-m-d H:i:s')
		]);

		if($user && $passwordReset) {
			$user->notify(new PasswordReset($resetToken));
		}

		return $this->success('Ok','');
	}

	public function resetPassword(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'required|string|email',
			'password' => 'required|min:6|max:25|regex:/^(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]*$/',
			'token'=> 'required|string'
		]);

		if($validator->fails()) {
			return $this->error( $validator->errors(), '', 400);
		}

		$passwordReset = DB::table(config('auth.passwords.admins.table'))->where([
			['email' , $request->email],
			['token' , $request->token]
		])->first();

		if(!$passwordReset) {
			return $this->error('', __('passwords.token'), 404);
		}

		$user = Admin::where('email', $passwordReset->email)->first();
		if(empty($user)) {
			return $this->error('', __('passwords.token'), 404);
		}

		$user->password = Hash::make($request->password);
		$user->save();

		DB::table(config('auth.passwords.admins.table'))->where([
			['email' , $request->email],
			['token' , $request->token]
		])->delete();


		return $this->success('ok');

	}

	public function findToken($token)
	{
		$passwordReset = DB::table(config('auth.passwords.admins.table'))->where('token', $token)->first();

		if(!$passwordReset) {
			return $this->error('', __('passwords.token'), 404);
		}

		if(Carbon::parse($passwordReset->created_at)->addMinutes(720)->isPast()) {
			DB::table(config('auth.passwords.admins.table'))->where('token', $token)->delete();
			return $this->error('', __('passwords.token'), 404);
		}

		return $this->success($passwordReset, 'ok');
	}

	public function updateUser(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|string|max:100|min:2',
		]);

		if($validator->fails()){
			return $this->error($validator->errors(), '', 400);
		}

		$user = Admin::findOrFail(auth()->user()->id);

		$requestAvatar = $request->avatar;

		if($user->avatar_id != $requestAvatar["id"]) {
			$user->avatar_id = $requestAvatar["id"];
		}

		$user->name = $request->name;

		$user->save();

		return $this->success('', __('panel.transaction_success'));
	}

	public function updatePassword(Request  $request)
	{
		$validator = Validator::make($request->all(), [
			'old_password' => 'required_with:new_password',
			'new_password' => 'required|min:6|max:25|regex:/^(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]*$/'
		]);

		if($validator->fails()){
			return $this->error($validator->errors(), '', 400);
		}

		$user = Admin::findOrFail(auth()->user()->id);
		if(!Hash::check($request->old_password, $user->password)) {
			return $this->error(['old_password' => [__('passwords.old_password_not_match')]], '', 400);
		}

		//update password

		$user->password = bcrypt($request->new_password);
		$user->save();

		return $this->success('', __('panel.transaction_success'));
	}

	public function getUser()
	{
		 $success['user'] = new AdminResource(auth()->guard('panel')->user());
		 return $this->success($success, '');
	}

	public function logout(Request $request)
	{
		auth()->guard('panel')->logout();

		return $this->success([]);
	}
}
