<?php
declare(strict_types=1);
namespace Modules\Mail\Services\Subscribers;

use Exception;

use Modules\Mail\Jobs\ImportSingleSubscriberJob;
use Modules\Mail\Services\Subscribers\Contracts\ImportSubscriberContract;

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
