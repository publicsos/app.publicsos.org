<?php

declare(strict_types=1);

namespace Modules\Mail\Services;

use Illuminate\Support\Arr;

class ResolverService
{
    /** @var array */
    private array $resolvers = [];


    public function setCurrentWorkspaceIdResolver(callable $callable): void
    {
        $this->setResolver('workspace', $callable);
    }

    public function resolveCurrentWorkspaceId(): ?int
    {
        $resolver = $this->getResolver('workspace');

        return $resolver() ?? 1;
    }

    private function getResolver(string $resolverName): ?callable
    {
        return Arr::get($this->resolvers, $resolverName);
    }

    private function setResolver(string $resolverName, callable $callable): void
    {
        $this->resolvers[$resolverName] = $callable;
    }
}
