<?php
declare(strict_types=1);
namespace Modules\Mail\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Mail\Facades\LaravelMail;
use Modules\Mail\Facades\Sendportal;
use Modules\Mail\Repositories\TagTenantRepository;

class CampaignDispatchRequest extends FormRequest
{
    private int $workspaceID = 1;
    public function rules(): array
    {
        /** @var TagTenantRepository $tags */
        $tags = app(TagTenantRepository::class)->pluck(
            $this->workspaceID,
            'id'
        );

        return [
            'tags' => [
                'required_unless:recipients,send_to_all',
                'array',
                Rule::in($tags),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'tags.required_unless' => __('At least one tag must be selected'),
            'tags.in' => __('One or more of the tags is invalid.'),
        ];
    }
}
