<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'post_code' => ['required', 'regex:/^[0-9]{3}-[0-9]{4}$/'],
            'address' => ['required']
        ];
    }
    public function messages()
    {
        return [
            'post_code.required' => '郵便番号を入力してください',
            'post_code.regex' => '郵便番号はハイフン付きで入力してください',
            'address.required' => '住所を入力してください'
        ];
    }
}
