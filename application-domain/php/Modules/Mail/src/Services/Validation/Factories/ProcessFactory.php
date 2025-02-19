<?php
declare(strict_types=1);

namespace LaravelCompany\Mail\Services\Validation\Factories;

use Symfony\Component\Process\Process;

class ProcessFactory
{
    public function create(array $command): Process
    {
        return new Process($command);
    }
}