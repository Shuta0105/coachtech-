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
            'name' => ['required'],
            'detail' => ['required', 'max:255'],
            'img' => ['required', 'file', 'mimes:jpeg,png'],
            'category_ids' => ['required', 'array', 'min:1'],
            'condition_id' => ['required'],
            'price' => ['required', 'numeric', 'min:0']
        ];
    }
    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'detail.required' => '商品説明を入力してください',
            'detail.max' => '商品説明は255文字以内で入力してください',
            'img.required' => '商品画像を選択してください',
            'img.mimes' => '.jpegもしくは.png形式のファイルを選択してください',
            'category_ids.required' => '商品のカテゴリーを選択してください',
            'category_ids.min' => '商品のカテゴリーを選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'price.required' => '商品価格を入力してください',
            'price.numeric' => '商品価格は数値で入力してください',
            'price.min' => '商品価格は0円以上で入力してください'
        ];
    }
}
