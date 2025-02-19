<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\Services\Validation\Strategies;

class SmtpDeliverabilityFactor extends ScoringFactor
{
    public function calculateScore(array $data): float
    {
        return !empty($data['smtp']['is_deliverable']) ? 1.0 * $this->weight : 0;
    }
}
