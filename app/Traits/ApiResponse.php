<?php

namespace App\Traits;

trait ApiResponse {

	/**
	 * @param $message
	 * @param $status_code
	 * @param null $data
	 * @param bool $success
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function response($message, $status_code, $data = null, $success = true)
	{
		return response()->json([
			'message' => $message,
			'success' => $success,
			'data' => $data
		], $status_code);
	}

	/**
	 * @param $data
	 * @param $status_code
	 * @param null $message
	 * @param bool $success
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function success($data, $message = null, $status_code = 200)
	{
		return $this->response($message, $status_code, $data);
	}

	/**
	 * @param array $messageArray
	 * @param null $message
	 * @param int $status_code
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function error($messageArray = [], $message = null, $status_code = 500)
	{
		return $this->response($message, $status_code, $messageArray, false);
	}
}
