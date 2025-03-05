<?php


namespace Modules\Document\Enums;;




enum EntityType: string
{
    case ORG = 'ORG';
    case PERSON = 'PERSON';
    case GPE = 'GPE';
    case LOC = 'LOC';
    case DATE = 'DATE';
    case OTHER = 'OTHER';
}
