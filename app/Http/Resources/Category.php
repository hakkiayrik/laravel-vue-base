<?php

namespace App\Http\Resources;

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
			'description' => $this->description,
			'slug' => $this->slug,
			'order_by' => $this->order_by,
			'created_at' => $this->created_at->format('Y-m-d H:i:s')
		];

    	$data = array_merge($data, $this->getTranslationsArray());

		return $data;
    }
}
