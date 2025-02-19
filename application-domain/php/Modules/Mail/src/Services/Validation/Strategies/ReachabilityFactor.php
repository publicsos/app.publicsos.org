<?php

namespace LaravelCompany\Mail\Services\Validation\Strategies;

/**
 * Safe,
 * Risky,
 * Invalid,
 * Unknown,
 */
class ReachabilityFactor extends ScoringFactor
{
    public function calculateScore(array $data): float
    {
        $reachability = $data['is_reachable'] ?? 'unknown';

        info($reachability);

        return match ($reachability) {
            'safe' => 1.0 * $this->weight,
            'risky' => 0.5 * $this->weight,
            'invalid' => 0.3 * $this->weight,
            default => 0.2 * $this->weight,
        };
    }
}
