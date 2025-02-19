<?php
declare(strict_types=1);

namespace LaravelCompany\Mail\Services\Validation\Contracts;

interface ValidationContract
{
    public function isValidEmail(string $email):bool;

    public function scoreEmail(string $email):mixed;
    
}
