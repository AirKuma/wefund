<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\Request;

class CommentRequest extends Request
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
        $rules['content'] = 'required';
        if($this->anonymous!=null)
            $rules['anonymous'] = 'required|integer|max:1|min:0';

        return $rules;
    }

    public function messages()
    {
        return [
            'content.required'=>'請輸入內容',
            'anonymous.required'=>'請選擇是否要匿名',
            'anonymous.integer'=>'只能選擇匿名或不匿名',
            'anonymous.max'=>'只能選擇匿名或不匿名',
            'anonymous.min'=>'只能選擇匿名或不匿名',
        ];
    }
}
