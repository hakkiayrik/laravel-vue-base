<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $defaultLanguage = env("MIX_DEFAULT_LANGUAGE", "en");
        return [
            'categories' => 'required|array',
            'type' => 'required',
            'status' => 'required|integer',
            'visibility' => 'required',
            'published_date' => 'required',
            $defaultLanguage . '.title' => 'required|max:150',
            $defaultLanguage . '.slug' => 'required|max:175',
            $defaultLanguage . '.content' => 'required',
            $defaultLanguage . '.meta_keywords' => 'required|max:150',
            $defaultLanguage . '.meta_description' => 'required|max:150',

        ];
    }
}
