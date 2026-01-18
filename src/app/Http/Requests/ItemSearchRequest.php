<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemSearchRequest extends FormRequest
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
            'tab' => ['nullable', 'in:mylist'],
            'select' => ['nullable', 'in:price,category,name'],
            'keyword' => ['nullable'],
        ];
    }
}
