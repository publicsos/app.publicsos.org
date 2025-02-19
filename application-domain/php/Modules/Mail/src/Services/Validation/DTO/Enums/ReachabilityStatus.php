<?php


namespace LaravelCompany\Mail\Services\Validation\DTO\Enums;

enum ReachabilityStatus: string {
    case SAFE = 'safe';
    case RISKY = "risky";
    case INVALID = 'invalid';
    case UNKNOWN = 'unknown';
};