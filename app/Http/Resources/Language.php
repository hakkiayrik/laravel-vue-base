<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Language extends JsonResource
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
            'name' => $this->name,
            'lang_code' => $this->lang_code,
            'is_rtl' => $this->is_rtl,
            'active' => $this->active,
        ];
    }
}
