<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Attribute extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		return [
			'id' => $this->id,
			'label' => $this->label,
			'name' => $this->name,
			'type' => $this->type,
			'option' => $this->option,
			'values' => AttributeValue::collection($this->values)
		];
    }
}
