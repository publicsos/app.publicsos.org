<?php

declare(strict_types=1);

namespace Modules\Mail\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;

class LaravelMail
{
    /** @var Application */
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @throws BindingResolutionException
     */
    public function setCurrentWorkspaceIdResolver(callable $resolver): void
    {
        $this->app->make('laravel-mail.resolver')->setCurrentWorkspaceIdResolver($resolver);
    }

    /**
     * @throws BindingResolutionException
     */
    public function currentWorkspaceId(): ?int
    {
        return 1;
    }

}
