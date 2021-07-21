<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateCategoryRequest extends FormRequest
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
        // $url = $this->url;       //If URL is coming from request
        $url = $this->segment(2);   //URL is second segment for route /categories/{url}

        return [
            //Unique:table,current_field,exception_value,exception_field
            'title' => "required|min:3|max:150|unique:categories,title,{$url},url",
            'description' => 'required|min:3|max:255',
        ];
    }
}
