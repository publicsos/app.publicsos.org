<?php
declare(strict_types=1);
namespace Modules\Mail\Services\Subscribers\Contracts;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

interface ImportSubscriberContract
{

    public function import(int $workspaceId, array $data): void;
}
