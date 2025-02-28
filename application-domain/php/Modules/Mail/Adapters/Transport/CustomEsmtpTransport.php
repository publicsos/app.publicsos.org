<?php

declare(strict_types=1);

namespace Modules\Mail\Adapters\Transport;


use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream;
use Illuminate\Support\Facades\Log;

class CustomEsmtpTransport extends EsmtpTransport
{
    /**
     * @param string $command
     * @param array  $codes
     * @return string
     */
    public function executeCommand(string $command, array $codes): string
    {
        $command = match (true) {
            str_starts_with($command, 'MAIL FROM:') && isset($this->getCapabilities()['DSN']) => substr_replace($command, ' RET=HDRS', -2, 0),
            str_starts_with($command, 'RCPT TO:') && isset($this->getCapabilities()['DSN']) => substr_replace($command, ' NOTIFY=FAILURE', -2, 0),
            default => $command,
        };

        $response = parent::executeCommand($command, $codes);

        if (str_starts_with($command, 'EHLO ')) {
            $response .= "250 DSN\r\n";
        }

        return $response;
    }
}
