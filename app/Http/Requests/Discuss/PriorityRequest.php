<?php

namespace App\Http\Requests\Discuss;

use App\Http\Requests\Request;

class PriorityRequest extends Request
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
        $rules['priority'] = 'required|integer|min:0|max:3';

        return $rules;
    }

    public function messages()
    {
        return [
            'priority.required'=>'請選擇頂置項目',
            'priority.integer'=>'請選擇頂置項目',
            'priority.min'=>'請選擇頂置項目',
            'priority.max'=>'請選擇頂置項目',
        ];
    }
}
