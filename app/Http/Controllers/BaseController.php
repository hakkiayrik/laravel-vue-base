<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
	/**
	 * Success response
	 * @param $result
	 * @param $message
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function sendResponse($result, $message = '')
	{
		$response = [
			'success' => true,
			'data'    => $result,
			'message' => $message,
		];


		return response()->json($response, 200);
	}

	/**
	 * Error response
	 * @param $error
	 * @param array $errorMessages
	 * @param int $code
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function sendError($error, $errorMessages = [], $code = 200)
	{
		$response = [
			'success' => false,
			'message' => $error,
		];


		if(!empty($errorMessages)){
			$response['data'] = $errorMessages;
		}


		return response()->json($response, $code);
	}
}
