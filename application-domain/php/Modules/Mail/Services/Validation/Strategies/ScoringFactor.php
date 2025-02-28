<?php
declare(strict_types=1);
namespace Modules\Mail\Services\Validation\Strategies;
use Modules\Mail\Services\Validation\Contracts\ScoreStrategyInterface;

abstract class ScoringFactor implements ScoreStrategyInterface
{
    protected string $weight;

    public function __construct(string $weight)
    {
        $this->weight = $weight;
    }

    public function getWeight(): string
    {
        return $this->weight;
    }
}
