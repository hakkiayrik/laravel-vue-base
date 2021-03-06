<?php

namespace App\Http\Controllers\Api\v1\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileUploadRequest;
use App\Http\Resources\ImageCollection;
use App\Models\Image;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
	use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
    	$images = Image::all();

		return $this->success(new ImageCollection($images));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FileUploadRequest $request)
    {
		$image = $request->file("files");
		$path = config('variables.path.media');

		try {
			$extension = $image->extension();
			$fileName = create_file_name($path, $extension);
			$image->storeAs($path, $fileName);

			$imageData['url'] = $path . $fileName;
			$imageData['name'] = $fileName;
			$image = Image::create($imageData);
			$uploadRequest[] = $image;
			$uploadRequest['success'] = $image->name . __('panel.file_uploaded');
		} catch (\Exception $e) {
			$uploadRequest['error'] = $image->name . __('panel.file_uploaded') . "(" . $e->getMessage() . ")";
		}

		return $this->success($uploadRequest);
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        $path = config('variables.path.media');
        $fileName = $image->name;
        $image->delete();

        //Delete file
        Storage::delete($path . $fileName);


        return $this->success('', __('panel.transaction_success'));
    }
}
