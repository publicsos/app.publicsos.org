<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\Services\Validation\Strategies;

use LaravelCompany\Mail\Services\Validation\Contracts\GraphScoreInterface;
use LaravelCompany\Mail\Services\Validation\Contracts\ScoreStrategyInterface;

class EmailScoreCalculator
{
    private array $factors = [];

    private GraphScoreInterface $graphAdapter;

    public function __construct(GraphScoreInterface $graphAdapter)
    {
        $this->graphAdapter = $graphAdapter;
    }

    public function addFactor(ScoreStrategyInterface $factor): void
    {
        $this->factors[] = $factor;
    }

    public function calculateTotalScore(array $emailData, array $graphData): float
    {
        $totalScore = 0;

        foreach ($this->factors as $factor) {
            $totalScore += $factor->calculateScore($emailData);
        }

        // Add graph score based on connections
        $email = $emailData['input'];
        $totalScore += $this->graphAdapter->calculateScore($graphData, $email);

        return $totalScore;
    }
}
