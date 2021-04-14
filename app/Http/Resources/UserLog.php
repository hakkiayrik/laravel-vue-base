<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserLog extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		$errorTypes = array('delete');
		$createType = array('create');

		if(in_array($this->type, $errorTypes)) {
			$logData['color'] = 'danger';
			$logData['icon'] = 'error';
		} else if(in_array($this->type, $createType)) {
			$logData['color'] = 'success';
			$logData['icon'] = 'add_circle';
		} else {
			$logData['color'] = 'primary';
			$logData['icon'] = 'update';
		}

    	$logData['id'] = $this->id;
    	$logData['user_id'] = $this->user_id;
    	$logData['type'] = $this->type;
		$logData['message'] = __("user." . $this->message_lang_code );
		$logData['data'] = $this->data;
		$logData['created_at'] = $this->created_at->format('Y-m-d H:i:s');

        return $logData;
    }
}
