<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListVideosRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'country' => [
                'required',
                Rule::in(array_keys(config('countries')))
            ],
            'offset' => 'integer',
            'page' => 'integer',
        ];
    }
}
