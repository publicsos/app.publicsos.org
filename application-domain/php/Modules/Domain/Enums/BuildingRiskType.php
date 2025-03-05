<?php

namespace Modules\Domain\Enums;


enum BuildingRiskType: string
{
    case R1 = 'R1';
    case R2 = 'R2';
    case R3 = 'R3';
    case R4 = 'R4';
    case UNKNOWN = 'UNKNOWN';
}
