<?php

declare(strict_types=1);

namespace Modules\Mail\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;




class VerifySmtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private string $server,
        private int $port,
        private string $email,
        private string $password,
        private int $workspaceId,
        private string $jobId
    ) {}

    public function handle(): void
    {
        $redisKey = "smtp_verification_jobs:workspace:{$this->workspaceId}";

        try {
            // Perform SMTP verification
            $result = $this->verifySmtp();

            // Determine the status based on the result
            $status = $this->determineStatus($result);

            // Update Redis with the job status
            $this->updateRedis($status, $result);

            // Store the result in the database if successful
            if ($status === 'completed') {

                //$this->storeInDatabase($result);
            }
        } catch (\Exception $e) {
            // Handle exceptions and update Redis
            $this->handleException($e);
        }
    }

    /**
     * Verify SMTP credentials using an external API.
     *
     * @return array
     * @throws GuzzleException
     */
    private function verifySmtp(): array
    {
        $client = new Client(['base_uri' => 'http://localhost:13005']);

        $payload = [
            'server' => $this->server,
            'port' => (string) $this->port, // Cast port to string
            'email' => $this->email,
            'password' => $this->password,
        ];

        // Log the payload for debugging
        Log::info('Sending payload to Go API:', $payload);

        $response = $client->post('/verify-smtp', [
            'json' => $payload,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Determine the status based on the API response.
     *
     * @param array $result
     * @return string
     */
    private function determineStatus(array $result): string
    {
        return (isset($result['message']) && str_contains($result['message'], 'SMTP Verified'))
            ? 'completed'
            : 'failed';
    }


    /**
     * Update Redis with the job status.
     *
     * @param string $status
     * @param array $result
     */
    private function updateRedis(string $status, array $result): void
    {
        $data = [
            'server' => $this->server,
            'port' => $this->port,
            'email' => $this->email,
            'password' => $this->password,
            'status' => $status,
            'workspace_id' => $this->workspaceId,
        ];

        if ($status === 'failed') {
            $data['error'] = $result['message'] ?? 'Unknown error';
        }

        Redis::hset("smtp_verification_jobs:workspace:{$this->workspaceId}", $this->jobId, json_encode($data));
    }

    /**
     * Handle exceptions during the SMTP verification process.
     *
     * @param \Exception $e
     */
    private function handleException(\Exception $e): void
    {
        Log::error('SMTP Verification Error', [
            'server' => $this->server,
            'email' => $this->email,
            'error' => $e->getMessage(),
        ]);

        Redis::hset("smtp_verification_jobs:workspace:{$this->workspaceId}", $this->jobId, json_encode([
            'server' => $this->server,
            'port' => $this->port,
            'email' => $this->email,
            'password' => $this->password,
            'status' => 'failed',
            'error' => $e->getMessage(),
            'workspace_id' => $this->workspaceId,
        ]));
    }
}
