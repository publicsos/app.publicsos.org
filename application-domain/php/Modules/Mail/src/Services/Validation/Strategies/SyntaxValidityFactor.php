<?php

namespace LaravelCompany\Mail\Services\Validation\Strategies;

class SyntaxValidityFactor extends ScoringFactor
{
    public function calculateScore(array $data): float
    {
        return !empty($data['syntax']['is_valid_syntax']) ? 1.0 * $this->weight : 0;
    }
}
