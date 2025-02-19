<?php
declare(strict_types=1);

namespace LaravelCompany\Mail\Services\Validation\Factories;

use LaravelCompany\Mail\Services\Validation\Strategies\EmailScoreCalculator;
use LaravelCompany\Mail\Services\Validation\Strategies\ReachabilityFactor;
use LaravelCompany\Mail\Services\Validation\Strategies\SmtpDeliverabilityFactor;
use LaravelCompany\Mail\Services\Validation\Strategies\SyntaxValidityFactor;
use LaravelCompany\Mail\Services\Validation\Adapters\GraphScoringAdapter;

class ScoreCalculatorFactory
{
    public function create(): EmailScoreCalculator
    {
        $calculator = new EmailScoreCalculator(
            new GraphScoringAdapter(10)
        );

        $calculator->addFactor(new ReachabilityFactor("50"));
        $calculator->addFactor(new SmtpDeliverabilityFactor("25"));
        $calculator->addFactor(new SyntaxValidityFactor("25"));

        return $calculator;
    }
}