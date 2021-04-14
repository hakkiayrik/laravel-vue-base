<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Avatar as AvatarResource;
use Illuminate\Support\Facades\DB;

class Admin extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		$avatar = DB::table("avatars")->where('id', $this->avatar_id)->first();

		return [
			'id' => $this->id,
			'first_name' => $this->first_name,
			'last_name' => $this->last_name,
			'username' => $this->username,
			'email' => $this->email,
			'role' => new Role( $this->roles->first() ),
			'status' => $this->status,
			'avatar' => new AvatarResource($avatar),
			'created_at' => $this->created_at->format('Y-m-d H:i:s')
		];
    }
}
