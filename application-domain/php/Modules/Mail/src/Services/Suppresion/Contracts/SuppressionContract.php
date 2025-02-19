<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\Services\Suppression\Contracts;


interface SuppressionContract
{
    public function updateList();

    public function isSuppressed(string $domain): bool;

    public function insert(string $domain): bool;

    public function delete(string $domain): bool;
}
