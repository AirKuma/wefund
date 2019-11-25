<?php

namespace App\Http\Requests\Auth\API;

use App\Http\Requests\Request;


class PasswordRequest extends Request
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
        $rules['password'] = 'required|confirmed|min:6';
       
        return $rules;
    }

    public function messages()
    {
        return [
            'password.min'=>'密碼不能小於6個字',
            'password.required'=>'請輸入新密碼',
            'password.confirmed'=>'兩次輸入的新密碼不一致',
        ];
    }
}
