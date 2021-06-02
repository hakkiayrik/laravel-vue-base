<?php

namespace App\Http\Controllers\Api\v1\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileUploadRequest;
use App\Http\Resources\ImageCollection;
use App\Models\Image;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

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

		echo "<pre>";
		var_dump($request->input('files'));
		echo "</pre>";

		die;

    	$fileData = array();
    	if($request->hasFile('files')) {
			$image = $request->file("files");
			$path = config('variables.path.media');
			$extension = $image->extension();
			$fileName =  create_file_name($path, $extension);
			$image->storeAs($path, $fileName);

			$fileData[] = $fileName;
		} else if(is_array($request->input('files')) && count($request->input('files')) > 0) {
    		$files = $request->input('files');
			foreach ($files as $file) {
				$image = $file;
				$path = config('variables.path.media');
				$extension = $image->extension();
				$fileName = create_file_name($path, $extension);

				$fileData[] = $fileName;
			}
		}

		echo "<pre>";
		print_r($fileData);
		echo "</pre>";

		die;
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
        //
    }
}
