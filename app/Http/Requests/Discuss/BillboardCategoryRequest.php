<?php

namespace App\Http\Requests\Discuss;

use App\Http\Requests\Request;

class BillboardCategoryRequest extends Request
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
        $rules['name'] = 'required|max:15';

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required'=>'請輸入類別名稱',
            'name.max'=>'類別名稱至多15個字',
        ];
    }
}
