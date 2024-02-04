<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdatePostRequest extends FormRequest
{
    public function validationData()
    {
        $data = parent::validationData();

        if( $data["edit_slug"] == true && !!$data["slug"] )
        {
            return array_merge($data, [
                "slug" => Str::slug($data["slug"], "-"),
            ]);
        }
        else {

            return $data;
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !!auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "title" => "required|string|max:255",
            "edit_slug" => "required",
            "slug" => "nullable|unique:posts,slug," . $this->post->id,
            "content" => "required|string",
            "status" => "required",
            "date" => "required|date_format:Y-m-d",
            "author" => "nullable|string",
            "show_author" => "required",
            "seo_title" => "nullable|string",
            "seo_description" => "nullable|string",
            "seo_keyphrase" => "nullable|string",
        ];
    }
}
