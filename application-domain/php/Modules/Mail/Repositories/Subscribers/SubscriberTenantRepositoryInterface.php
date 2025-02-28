<?php

declare(strict_types=1);

namespace Modules\Mail\Repositories\Subscribers;

use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Modules\Mail\Interfaces\BaseTenantInterface;
use Modules\Mail\Models\Subscriber;

interface SubscriberTenantRepositoryInterface extends BaseTenantInterface
{
    public function syncTags(Subscriber $subscriber, array $tags = []);

    public function countActive($workspaceId): int;

    public function getRecentSubscribers(int $workspaceId): Collection;

    public function getGrowthChartData(CarbonPeriod $period, int $workspaceId): array;
}
