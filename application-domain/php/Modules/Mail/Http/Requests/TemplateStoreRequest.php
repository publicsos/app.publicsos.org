<?php

namespace Modules\Mail\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Mail\Facades\LaravelMail;

class TemplateStoreRequest extends FormRequest
{
    protected int $workspaceId = 1;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('templates')
                    ->where('workspace_id', $this->workspaceId),
            ],
            'content' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => __('The template name must be unique.'),
        ];
    }
}
