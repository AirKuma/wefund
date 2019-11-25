<?php

namespace App\Http\Requests\Discuss;

use App\Http\Requests\Request;

class PostRequest extends Request
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
        $rules['billboard_id'] = 'required|integer';
        $rules['category_id'] = 'integer';
        $rules['title'] = 'required|max:100';
        if($this->anonymous!=null)
            $rules['anonymous'] = 'required|integer|min:0|max:1';

        if($this->type == 'link'){
            $rules['link'] = 'required|url';
            $rules['content'] = 'max:255';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'content.required'=>'請輸入內容',
            'content.max'=>'內容不能超過255個字',
            'billboard_id.required'=>'請選擇討論版',
            'billboard_id.integer'=>'請選擇討論版',
            'category_id.integer'=>'請選擇分類',
            'title.required'=>'請輸入標題',
            'title.max'=>'標題至多100個字',
            'link.required'=>'請輸入連結',
            'link.url'=>'連結必須符合url格式',
            'anonymous.required'=>'請選擇是某匿名',
            'anonymous.integer'=>'只能選擇匿名或不匿名',
            'anonymous.min'=>'只能選擇匿名或不匿名',
            'anonymous.max'=>'只能選擇匿名或不匿名',
        ];
    }
}
