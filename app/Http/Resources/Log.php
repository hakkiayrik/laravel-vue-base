<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class Log extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  [
			'id' => $this->id,
			'user' => $this->user,
			'action' => $this->action,
			'action_model' => $this->action_model,
			'action_id' => $this->action_id,
			'old_data' => $this->old_data,
			'new_data' => $this->new_data,
			'ip' => $this->ip,
			'created_at' => $this->created_at->format('Y-m-d H:i:s'),
		];
    }
}
