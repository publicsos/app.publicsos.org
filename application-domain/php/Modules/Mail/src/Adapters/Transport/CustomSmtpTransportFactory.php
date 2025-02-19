<?php


declare(strict_types=1);

namespace LaravelCompany\Mail\Adapters\Transport;

use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

use Symfony\Component\Mailer\Transport\TransportInterface;

class CustomSmtpTransportFactory
{
    private EsmtpTransportFactory $esmtpTransportFactory;

    public function __construct()
    {
        $this->esmtpTransportFactory = new EsmtpTransportFactory();
    }

    public function create(Dsn $dsn): TransportInterface
    {

        $autoTls = '' === $dsn->getOption('auto_tls') || filter_var($dsn->getOption('auto_tls', true), \FILTER_VALIDATE_BOOL);
        $tls = 'smtps' === $dsn->getScheme() ? true : ($autoTls ? null : false);
        $port = $dsn->getPort(0);
        $host = $dsn->getHost();

        $transport = new CustomEsmtpTransport($host, $port, $tls);
        $transport->setAutoTls($autoTls);

        /** @var SocketStream $stream */
        $stream = $transport->getStream();
        $streamOptions = $stream->getStreamOptions();

        if ('' !== $dsn->getOption('verify_peer') && !filter_var($dsn->getOption('verify_peer', true), \FILTER_VALIDATE_BOOL)) {
            $streamOptions['ssl']['verify_peer'] = false;
            $streamOptions['ssl']['verify_peer_name'] = false;
        }

        if (null !== $peerFingerprint = $dsn->getOption('peer_fingerprint')) {
            $streamOptions['ssl']['peer_fingerprint'] = $peerFingerprint;
        }

        $stream->setStreamOptions($streamOptions);

        if ($user = $dsn->getUser()) {
            $transport->setUsername($user);
        }

        if ($password = $dsn->getPassword()) {
            $transport->setPassword($password);
        }

        if (null !== ($localDomain = $dsn->getOption('local_domain'))) {
            $transport->setLocalDomain($localDomain);
        }

        if (null !== ($maxPerSecond = $dsn->getOption('max_per_second'))) {
            $transport->setMaxPerSecond((float) $maxPerSecond);
        }

        if (null !== ($restartThreshold = $dsn->getOption('restart_threshold'))) {
            $transport->setRestartThreshold((int) $restartThreshold, (int) $dsn->getOption('restart_threshold_sleep', 0));
        }

        if (null !== ($pingThreshold = $dsn->getOption('ping_threshold'))) {
            $transport->setPingThreshold((int) $pingThreshold);
        }

        return $transport;
    }
}
