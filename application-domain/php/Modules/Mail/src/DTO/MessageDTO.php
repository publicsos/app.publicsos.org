<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\DTO;

class MessageDTO
{
    public string $subject;
    public string $from;
    public string $to;
    public string $date;
    public string $body;
    public array $attachments;

    public function __construct(string $subject, string $from, string $to, string $date, string $body, array $attachments = [])
    {
        $this->subject = $subject;
        $this->from = $from;
        $this->to = $to;
        $this->date = $date;
        $this->body = $body;
        $this->attachments = $attachments;
    }
}
