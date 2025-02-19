<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\Services\Subscribers;

use Exception;

//todo move to
use LaravelCompany\Mail\Jobs\ImportSingleSubscriberJob;
use LaravelCompany\Mail\Services\Subscribers\Contracts\ImportSubscriberContract;

class ImportSubscriberService implements ImportSubscriberContract
{
    /**
     * @throws Exception
     */
    public function import(int $workspaceId, array $data): void
    {
        dispatch(new ImportSingleSubscriberJob($workspaceId,$data));
    }
}
