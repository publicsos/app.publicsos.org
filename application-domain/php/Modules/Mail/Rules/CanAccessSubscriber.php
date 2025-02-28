<?php

declare(strict_types=1);

namespace Modules\Mail\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Mail\Facades\LaravelMail;
use Modules\Mail\Models\Subscriber;

class CanAccessSubscriber implements Rule
{
    public function passes($attribute, $value): bool
    {
        $subscriber = Subscriber::find($value);

        if (! $subscriber) {
            return false;
        }

        return $subscriber->workspace_id == LaravelMail::currentWorkspaceId();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
