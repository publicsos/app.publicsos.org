<?php
declare(strict_types=1);

namespace Modules\Mail\Services\Validation\Factories;

use Modules\Mail\Services\Validation\Strategies\EmailScoreCalculator;
use Modules\Mail\Services\Validation\Strategies\ReachabilityFactor;
use Modules\Mail\Services\Validation\Strategies\SmtpDeliverabilityFactor;
use Modules\Mail\Services\Validation\Strategies\SyntaxValidityFactor;
use Modules\Mail\Services\Validation\Adapters\GraphScoringAdapter;

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
