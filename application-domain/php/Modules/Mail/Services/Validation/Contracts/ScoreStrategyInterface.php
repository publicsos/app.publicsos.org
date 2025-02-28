<?php
declare(strict_types=1);
namespace Modules\Mail\Services\Validation\Contracts;

interface ScoreStrategyInterface
{
    public function calculateScore(array $data): float;
    public function getWeight(): string;
}
