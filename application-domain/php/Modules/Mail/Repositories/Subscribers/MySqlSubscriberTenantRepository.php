<?php
declare(strict_types=1);
namespace Modules\Mail\Repositories\Subscribers;

use Carbon\CarbonPeriod;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class MySqlSubscriberTenantRepository extends BaseSubscriberTenantRepository
{

         /**
     * Get growth chart data with database-specific adaptations.
     */
    public function getGrowthChartData(CarbonPeriod $period, int $workspaceId): array
    {

        $connection = DB::getDriverName();

        // Define the date formatting logic based on the connection type
        $dateFormat = match ($connection) {
            'sqlite' => "strftime('%Y-%m-%d', created_at)",
            'mysql', 'mariadb', 'pgsql' => "DATE_FORMAT(created_at, '%Y-%m-%d')",
            default => throw new \RuntimeException("Unsupported database connection: $connection"),
        };

        $startingValue = DB::table('subscribers')
            ->where('workspace_id', $workspaceId)
            ->where(function ($q) use ($period) {
                $q->where('unsubscribed_at', '>=', $period->getStartDate())
                    ->orWhereNull('unsubscribed_at');
            })
            ->where('created_at', '<', $period->getStartDate())
            ->count();

        $runningTotal = DB::table('subscribers')
            ->selectRaw("$dateFormat AS date, COUNT(*) as total")
            ->where('workspace_id', $workspaceId)
            ->whereBetween('created_at', [$period->getStartDate(), $period->getEndDate()])
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $unsubscribers = DB::table('subscribers')
            ->selectRaw("$dateFormat AS date, COUNT(*) as total")
            ->where('workspace_id', $workspaceId)
            ->whereBetween('unsubscribed_at', [$period->getStartDate(), $period->getEndDate()])
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        return [
            'startingValue' => $startingValue,
            'runningTotal' => $runningTotal,
            'unsubscribers' => $unsubscribers,
        ];
    }
}
