<?php

namespace App\Http\Controllers\Api\v1\Panel;

use App\Http\Controllers\BaseController;
use App\Http\Resources\AvatarCollection;
use App\Models\Avatar;
use App\Http\Resources\Avatar as AvatarResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AvatarController extends BaseController
{
	use ApiResponse;

	/**
	 * Get logs
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */

	public function index(Request $request)
	{
		$avatars = Avatar::all();

		return $this->success(new AvatarCollection($avatars));
	}
}
