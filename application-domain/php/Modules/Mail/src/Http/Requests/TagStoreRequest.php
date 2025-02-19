<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LaravelCompany\Mail\Facades\LaravelMail;

class TagStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('tags')
                    ->where('workspace_id', LaravelMail::currentWorkspaceId()),
            ],
            'subscribers' => [
                'required',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => __('The tag name must be unique.'),
            'name.max' => __('The tag name cannot exceed 255 characters.'),
            'subscribers.required' => __('The tag subscribers are required.'),
        ];
    }
}
