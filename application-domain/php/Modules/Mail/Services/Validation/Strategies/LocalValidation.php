<?php
declare(strict_types=1);

namespace Modules\Mail\Services\Validation\Strategies;

use Illuminate\Process\Exceptions\ProcessFailedException;
use Modules\Mail\Services\Validation\Factories\ProcessFactory;
use Modules\Mail\Services\Validation\DTO\EmailValidationResult;
use Modules\Mail\Services\Validation\DTO\Enums\ReachabilityStatus;
use Modules\Mail\Services\Validation\Contracts\ValidationContract;
use RuntimeException;

class LocalValidation implements ValidationContract
{
    private $memory;

    public function __construct(
        private readonly EmailScoreCalculator $scoreCalculator,
        private readonly ProcessFactory $processFactory
    ) {}

    public function handle(string $email, callable $next): mixed
    {
        $this->isValidEmail($email);
        return $next($email);
    }

    public function isValidEmail(string $email): bool
    {
        try {
            $validationResult = $this->runEmailValidation($email);
            return $validationResult->isReachable === ReachabilityStatus::SAFE;
        } catch (ProcessFailedException $e) {
            info($e->getMessage());
            return false;
        }
    }

    public function scoreEmail(string $email): float
    {
        $this->runEmailValidation($email);

        return $this->calculateScore();
    }

    private function runEmailValidation(string $email): EmailValidationResult
    {

        $process = $this->processFactory->create(['check_if_email_exists', $email]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException("Starting email validation failed");
        }

        $result = json_decode($process->getOutput());

        return $this->memory =  EmailValidationResult::from($result);

    }

    private function calculateScore(): float
    {
        $graphData = ['edges' => []];
        return $this->scoreCalculator->calculateTotalScore($this->memory, $graphData);
    }
}
