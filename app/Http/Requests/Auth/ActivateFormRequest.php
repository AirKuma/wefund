<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class ActivateFormRequest extends Request
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
        $rules['email'] = 'required|email';

        return $rules;
    }

    public function messages()
    {
        return [
            'email.required' => '請輸入E-mail',
            'email.email' => '請輸入正確格式的E-mail',
        ];
    }
}
