<?php




namespace Modules\Document\Enums;;




enum DocumentStatus: string
{
    case Downloaded = 'Downloaded';
    case Unprocessed = 'Unprocessed';
    case Processed = 'Processed';
    case Failed = 'Failed';

}
