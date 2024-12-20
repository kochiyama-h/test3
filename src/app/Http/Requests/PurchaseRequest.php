<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'payment' => 'required',
            'postal_code' => 'required',
            'address' => 'required',
            'building' => 'required',
        ];
    }



    public function messages()
    {
        return [                        
            'payment.required' => '支払方法を選択してください',
            'postal_code.required' => '郵便番号を入力してください',
            'address.required'=>'住所を入力してください',
            'building.required'=>'建物名を入力してください',           

        ];
    }
}