<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Avatar extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		return [
			'id' => $this->id,
			'image_path' =>  asset('assets/images/avatars/' . $this->image_path),
		];
    }
}
