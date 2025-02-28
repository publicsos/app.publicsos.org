<?php

declare(strict_types=1);

namespace Modules\Mail\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Mail\Facades\LaravelMail;
use Modules\Mail\Models\Tag;

class CanAccessTag implements Rule
{
    public function passes($attribute, $value): bool
    {
        $tag = Tag::find($value);

        if (! $tag) {
            return false;
        }

        return $tag->workspace_id == LaravelMail::currentWorkspaceId();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Tag ID :input does not exist.';
    }
}
