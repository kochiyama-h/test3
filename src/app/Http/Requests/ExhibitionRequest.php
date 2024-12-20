<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
        return [
            'name' => 'required',
            'description' => 'required | max:255',           
            'img' => 'required | mimes:png,jpeg',  
            'category_id' => 'required',         
            'condition' => 'required',
            'price' => 'required | integer | min:0 ',
            
        ];
    }

    public function messages()
    {
        return [                        
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '255文字以内で入力してください',
            'img.required'=>'商品画像を登録してください',
            'img.mimes'=>'「.png」または「.jpeg」形式でアップロードしてください',
            'category_id.required' => 'カテゴリーを選択してください',
            'condition.required' => '商品の状態を選択してください',
            'price.required'=>'価格を入力してください',
            'price.integer' => '数値で入力してください',            
            'price.min'=>'0円以上で入力してください',        
        ];
    }
}
