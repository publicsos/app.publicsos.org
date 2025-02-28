<?php
declare(strict_types=1);
namespace Modules\Mail\Services\Validation\Strategies;

use Modules\Mail\Services\Validation\Adapters\GraphScoringAdapter;
use Modules\Mail\Services\Validation\Contracts\ValidationContract;
use Modules\Mail\Services\Validation\Saloon\Connectors\ValidationConnector;
use Modules\Mail\Services\Validation\Saloon\Requests\GetValidationResults;

use Modules\Mail\Models\Campaign;
use GuzzleHttp\Client;


class RemoteValidation implements ValidationContract
{
    protected Client $client;

    private string $is_reacheable = "safe";

    public function __construct()
    {
        $this->client = new Client();
    }


      /**
     * Mark the campaign as started in the database
     *
     * @param Campaign $campaign
     * @param $next
     * @return Campaign
     */
    public function handle(string $email, $next)
    {
        $this->isValidEmail($email);

        return $next($email);
    }



    public function isValidEmail(string $email): bool
    {
        $valConnector = new ValidationConnector;

        $request = new GetValidationResults($email);

        $response = $valConnector->send($request);

        $json = $response->dto();

        if($json->is_reachable === $this->is_reacheable) {
            return true;
        }

        return false;

    }


    /**
     * @todo refactor mixed to ScoreResult
     */
    public function scoreEmail(string $email): mixed
    {

        $valConnector = new ValidationConnector;
        $request = new GetValidationResults($email);

        $response = $valConnector->send($request);


        $reachabilityFactor = new ReachabilityFactor("25");
        $smtpDeliverabilityFactor = new SmtpDeliverabilityFactor("50");
        $syntaxValidityFactor = new SyntaxValidityFactor("25");

        $graphAdapter = new GraphScoringAdapter(10);
        $calculator = new EmailScoreCalculator($graphAdapter);
        $calculator->addFactor($reachabilityFactor);
        $calculator->addFactor($smtpDeliverabilityFactor);
        $calculator->addFactor($syntaxValidityFactor);


        $emailData = $response->json();

        $graphData = [
            "edges" => []
        ];

        $totalScore = $calculator->calculateTotalScore($emailData, $graphData);

        return $totalScore;
    }
}
