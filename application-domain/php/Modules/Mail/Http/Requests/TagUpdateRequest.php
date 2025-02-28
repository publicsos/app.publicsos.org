<?php

declare(strict_types=1);

namespace Modules\Mail\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Mail\Facades\LaravelMail;


class TagUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('tags')
                    ->where('workspace_id', 1)
                    ->ignore($this->tag),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => __('The tag name must be unique.'),
        ];
    }
}
