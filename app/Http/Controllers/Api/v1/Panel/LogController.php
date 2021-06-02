<?php

namespace App\Http\Controllers\Api\v1\Panel;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataTableRequest;
use App\Models\Log;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\LogCollection;

class LogController extends BaseController
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
		$logs = Log::query();

		if(count($sortBy) > 0) {
			foreach ($sortBy as $index => $item) {
				$logs->orderBy($item, $sortDesc[$index] ? 'DESC' : 'ASC');
			}
		}

		$logs = $logs->latest()->paginate($itemPerPage);

		return $this->success(new LogCollection($logs));
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
}
