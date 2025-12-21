<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'avatar' => ['nullable', 'mimes:jpeg,png'],
            'name' => ['required', 'max:20'],
            'post_code' => ['required', 'regex:/^[0-9]{3}-[0-9]{4}$/'],
            'address' => ['required']
        ];
    }
    public function messages()
    {
        return [
            'avatar.mimes' => '.jpegもしくは.png形式のファイルを選択してください',
            'name.required' => '名前を入力してください',
            'name.max' => '名前は20文字以内で入力してください',
            'post_code.required' => '郵便番号を入力してください',
            'post_code.regex' => '郵便番号はハイフン付きで入力してください',
            'address.required' => '住所を入力してください'
        ];
    }
}
