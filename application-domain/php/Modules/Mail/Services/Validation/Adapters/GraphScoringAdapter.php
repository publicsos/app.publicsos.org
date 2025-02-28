<?php
declare(strict_types=1);
namespace Modules\Mail\Services\Validation\Adapters;

use Modules\Mail\Services\Validation\Contracts\GraphScoreInterface;

class GraphScoringAdapter implements GraphScoreInterface
{
    private float $weight;

    public function __construct(float $weight = 1.0)
    {
        $this->weight = $weight;
    }

    public function calculateScore(array $graphData, string $targetNode): float
    {
        $score = 0;
        $connections = 0;

        // Calculate connections for target node (email address)
        foreach ($graphData['edges'] as $edge) {
            if ($edge['source'] === $targetNode || $edge['target'] === $targetNode) {
                $connections++;
            }
        }

        // Scoring based on connection density
        $score += $connections * $this->weight;

        return $score;
    }
}
