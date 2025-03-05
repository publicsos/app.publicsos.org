<?php

namespace Modules\Document\Saloon\Auth;

use Saloon\Http\PendingRequest;
use Saloon\Contracts\Authenticator;

class MOAuth implements Authenticator
{
    public function __construct(public readonly string $token) {}

    public function set(PendingRequest $pendingRequest): void
    {
        $pendingRequest->headers()->add("cookie",  "PHPSESSID={$this->token};");
    }
}
