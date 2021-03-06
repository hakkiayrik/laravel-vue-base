<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		$defaultLanguage = env('MIX_DEFAULT_LANGUAGE', 'en');
        return [
			$defaultLanguage . '.name' => 'required|max:25|min:2',
			$defaultLanguage . '.slug' => 'required|max:50|min:3|unique:categories',
			'display_order' => 'required|email|unique:admins',
        ];
    }
}
