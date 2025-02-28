<?php
declare(strict_types=1);
namespace Modules\Mail\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignTemplateUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'template_id' => ['required', 'exists:templates,id'],
        ];
    }
}
