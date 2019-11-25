<?php

namespace App\Http\Requests\Image;

use App\Http\Requests\Request;

class ImageRequest extends Request
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
        $rules = $this->rules; 
        $rules['file'] = 'required|image';
        return $rules;
    }

    public function messages()
    {
        return [
            'file.required'=>'請輸入內容',
        ];
    }
}
