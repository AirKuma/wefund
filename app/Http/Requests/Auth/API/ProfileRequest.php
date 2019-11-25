<?php

namespace App\Http\Requests\Auth\API;

use App\Http\Requests\Request;


class ProfileRequest extends Request
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

        $rules['firstname'] = 'required|max:10';
        $rules['lastname'] = 'required|max:10';
        $rules['major_id'] = 'required|integer';
        $rules['birthday'] = 'required|max:255|date';
        $rules['other_email'] = 'email';
        // if($this->username != '')
        //     $rules['username'] = 'unique:users|regex:/^[a-zA-Z0-9\s-]+$/|max:20';
        
        return $rules;
    }

    public function messages()
    {
        return [
            'firstname.required'=>'請輸入名字',
            'firstname.max'=>'名字至多10個字',
            'lastname.required'=>'請輸入姓氏',
            'lastname.max'=>'姓氏至多10個字',
            'major_id.required'=>'請選擇科系',
            'major_id.integer'=>'請選擇科系',
            'username.unique'=>'該使用者名稱已有人使用',
            'username.max'=>'使用者名稱至多20個字',
            'username.regex'=>'使用者名稱不能是中文',
            'other_email.email'=>'請輸入E-mail',
            'birthday.required'=>'請輸入生日',
            'birthday.date'=>'生日必須符合日期格式',
            'birthday.max'=>'生日至多255個字',
        ];
    }
}
