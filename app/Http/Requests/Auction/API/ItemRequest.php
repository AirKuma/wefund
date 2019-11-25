<?php

namespace App\Http\Requests\Auction\API;

use App\Http\Requests\Request;

class ItemRequest extends Request
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
        $rules['name'] = 'required|max:100';
        $rules['new'] = 'boolean';
        $rules['free'] = 'boolean';
        if($this->free==1 || $this->edit!=null)
            $rules['price'] = '';
        else
            $rules['price'] = 'required|integer|min:0';
        $rules['description'] = 'required';
        $rules['category_id'] = 'required|integer';
        $rules['target'] = 'required|integer|min:0|max:2';


        return $rules;
    }

    public function messages()
    {
        return [
            'name.required'=>'請輸入名稱',
            'name.max'=>'名稱至多100個字',
            'new.integer'=>'只能選擇是否全新',
            'new.min'=>'只能選擇是否全新',
            'new.max'=>'只能選擇是否全新',
            'free.integer'=>'只能選擇是否免費',
            'free.min'=>'只能選擇是否免費',
            'free.max'=>'只能選擇是否免費',
            'price.required'=>'請輸入價錢',
            'price.integer'=>'價錢只能是整數',
            'price.min'=>'價錢不能為負數',
            'description.required'=>'請輸入描述',
            'category_id.required'=>'請選擇類別',
            'category_id.integer'=>'請選擇類別',
            'target.required'=>'請選擇指派',
            'target.integer'=>'只能選擇指派性別',
            'target.min'=>'只能選擇指派性別',
            'target.max'=>'只能選擇指派性別',
            
        ];
    }
}
