<?php

namespace App\Http\Requests\Discuss;

use App\Http\Requests\Request;

class BillboardRequest extends Request
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
        $rules['name'] = 'required|max:20';
        $rules['description'] = 'required';
        $rules['domain'] = 'required|unique:billboards|alpha|max:20|different:all';
        $rules['type'] = 'required|integer|min:0|max:1';
        $rules['target'] = 'required|integer|min:0|max:2';
        $rules['anonymous'] = 'required|integer|min:0|max:2';
        $rules['adult'] = 'integer|min:0|max:1';
        $rules['limit_college'] = 'integer|min:0|max:1';

        if($this->edit == 'edit'){
            $rules['name'] = '';
            $rules['domain'] = 'alpha';
        }    

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required'=>'請輸入討論版名稱',
            'name.max'=>'討論版名稱至多20個字',
            'description.required'=>'請輸入描述',
            'domain.required'=>'請輸入domain',
            'domain.unique'=>'該domain已有人使用',
            'domain.alpha'=>'domain只能是英文',
            'domain.max'=>'domain至多20個字',
            'domain.different'=>'domain不能是all',
            'type.required'=>'請選擇討論版類型',
            'type.integer'=>'討論版類型只能是整數',
            'type.min'=>'討論版類型只能選擇公開或非公開',
            'type.max'=>'討論版類型只能選擇公開或非公開',
            'target.required'=>'請選擇指定性別',
            'target.integer'=>'指定性別只能是整數',
            'target.min'=>'指定性別只能男、女或全部',
            'target.max'=>'指定性別只能男、女或全部',
            'anonymous.required'=>'請選擇匿名設定',
            'anonymous.integer'=>'匿名設定只能是整數',
            'anonymous.min'=>'匿名設定只能選擇性匿名、匿名或不匿名',
            'anonymous.max'=>'匿名設定只能選擇性匿名、匿名或不匿名',
            'adult.integer'=>'討論版為18禁只能是整數',
            'adult.min'=>'只能選擇是否討論版為18禁 ',
            'adult.max'=>'只能選擇是否討論版為18禁',
            'limit_college.integer'=>'指定學校發言只能是整數',
            'limit_college.min'=>'只能選擇是否指定學校發言 ',
            'limit_college.max'=>'只能選擇是否指定學校發言',
        ];
    }
}
