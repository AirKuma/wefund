<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use Auth;


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
        if(Request::input('password') != null && Request::input('email') != null){
            $rules['firstname'] = '';
            $rules['lastname'] = '';
            $rules['email'] = 'regex:/^[a-z0-9._%+-]+@mail.fju.edu.tw$/|required|email|max:255|unique:users';
            $rules['password'] = 'required|confirmed|min:6';
        }elseif(Request::input('password') != null && Request::input('email') == null){
            $rules['firstname'] = '';
            $rules['lastname'] = '';
            $rules['password'] = 'required|confirmed|min:6';

        }elseif(Request::input('gender') != null){
            $rules['firstname'] = 'required|max:10';
            $rules['lastname'] = 'required|max:10';
            $rules['gender'] = 'required|integer|min:0|max:1';
            $rules['major_id'] = 'required|integer';
            $rules['birthday'] = 'required|max:255|date';
            $rules['other_email'] = 'email';
            $rules['username'] = 'unique:users|regex:/^[a-zA-Z0-9\s-]+$/|max:20';
            if($this->user_name == 'none')
                $rules['username'] = '';
        }elseif(Auth::user()->email != null){
            $rules['password'] = 'required|confirmed|min:6';
        }else{
            $rules['email'] = 'regex:/^[a-z0-9._%+-]+@mail.fju.edu.tw$/|required|email|max:255|unique:users';
            $rules['password'] = 'required|confirmed|min:6';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'email.regex'=>'E-mail必須符合學校格式',
            'email.required'=>'請輸入E-mail',
            'email.email'=>'E-mail必須符合E-mail格式',
            'email.max'=>'E-mail最多不能超過255個字',
            'email.unique'=>'該帳號已有人使用',
            'password.min'=>'密碼不能小於6個字',
            'password.required'=>'請輸入新密碼',
            'password.confirmed'=>'兩次輸入的新密碼不一致',
            'firstname.required'=>'請輸入名字',
            'firstname.max'=>'名字至多10個字',
            'lastname.required'=>'請輸入姓氏',
            'lastname.max'=>'姓氏至多10個字',
            'major_id.required'=>'請選擇科系',
            'major_id.integer'=>'請選擇科系',
            'username.unique'=>'該使用者名稱已有人使用',
            'username.max'=>'使用者名稱至多20個字',
            'username.regex'=>'使用者名稱不能是中文',
            'gender.required'=>'請選擇性別',
            'gender.integer'=>'只能選擇選擇男或女',
            'gender.max'=>'只能選擇選擇男或女',
            'gender.min'=>'只能選擇選擇男或女',
            'other_email.email'=>'請輸入E-mail',
            'birthday.required'=>'請輸入生日',
            'birthday.date'=>'生日必須符合日期格式',
            'birthday.max'=>'生日至多255個字',
        ];
    }
}
