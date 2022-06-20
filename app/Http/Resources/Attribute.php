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
			'html_name' => $this->name,
			'type' => $this->type,
            'display_order' => $this->display_order,
			'options' => $this->options,
		];
    }
}
