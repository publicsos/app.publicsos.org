<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\Services\Suppression\Repositories;

class SuppressionRepository
{

    protected string $dbName = "suppression.sqlite";


    protected function createDatabase():void
    {

    }


    public function __construct()
    {
    }


    public function insert(string $domain): bool
    {
        return false;
    }

    public function delete(string $domain): bool
    {
        return false;
    }


}



