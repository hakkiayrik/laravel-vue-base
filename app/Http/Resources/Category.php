<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
    	$data = [
			'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
			'display_order' => $this->display_order,
			'status' => $this->status,
			'created_at' => Carbon::parse($this->created_at)->diffForHumans(now())
		];

    	$data = array_merge($data, $this->getTranslationsArray());

		return $data;
    }
}
