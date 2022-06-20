<?php

namespace App\Http\Resources;

use App\Models\Avatar;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $author = $this->admin()->first()->toArray();
        $author["avatar"] = Avatar::findOrFail($author["avatar_id"]);
        $author["avatar"]["image_path"] = asset('assets/images/avatars/' . $author["avatar"]["image_path"]);
        unset($author["avatar_id"]);

        $data = [
            'id' => $this->id,
            'author' => $author,
            'published_date' => $this->published_date,
            'type' => $this->type,
            'like' => $this->like,
            'dislike' => $this->dislike,
            'visibility' => $this->visibility,
            'video_url' => $this->video_url,
            'status' => $this->status,
            'categories' => $this->categories()->pluck('id'),
            'created_at_string' => Carbon::parse($this->created_at)->diffForHumans(now()),
            'updated_at_string' => Carbon::parse($this->updated_at)->diffForHumans(now())
        ];

        $data = array_merge($data, $this->getTranslationsArray());

        return $data;
    }
}
