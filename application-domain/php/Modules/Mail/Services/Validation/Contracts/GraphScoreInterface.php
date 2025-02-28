<?php
declare(strict_types=1);
namespace Modules\Mail\Services\Validation\Contracts;

interface GraphScoreInterface
{
    public function calculateScore(array $graphData, string $targetNode): float;

}
