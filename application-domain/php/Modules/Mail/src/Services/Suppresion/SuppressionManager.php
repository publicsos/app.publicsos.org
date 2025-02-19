<?php

namespace LaravelCompany\Mail\Services\Suppression;

use GuzzleHttp\Client;
use LaravelCompany\Mail\Services\Suppression\Contracts\SuppressionContract;
use Illuminate\Support\Facades\Http;



class SuppressionManager implements SuppressionContract
{
    public function updateList()
    {
        $remote = "https://raw.githubusercontent.com/disposable/disposable-email-domains/master/domains.txt";

        $data = Http::get($remote);

        $domains = collect(explode("\n", $data))->map(function ($domain) {
            return trim($domain);
        });

        $domains->each(function ($domain) {
            /// to some logic
        });
    }

    public function isSuppressed(string $domain): bool
    {

        return false;
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
