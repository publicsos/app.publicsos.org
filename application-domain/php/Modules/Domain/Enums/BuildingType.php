<?php

namespace Modules\Domain\Enums;


enum BuildingType: string
{
    case APARTMENTS = 'apartments';
    case COMMERCIAL = 'commercial';
    case HOUSE = 'house';
    case INDUSTRIAL = 'industrial';
    case RETAIL = 'retail';
    case CIVIC = 'civic';
    case RELIGIOUS = 'religious';
    case SCHOOL = 'school';
    case SPORTS = 'sports';
    case TRANSPORTATION = 'transportation';
    case WAREHOUSE = 'warehouse';
    case OTHER = 'other';
}
