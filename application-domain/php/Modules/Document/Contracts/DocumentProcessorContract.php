<?php

namespace Modules\Document\Contracts;

use Modules\Document\Models\Document;

interface DocumentProcessorContract
{
    public function getDocuments(string $sessionID, string $date);
    public function download(string $sessionID, string $documentID);
    public function process(Document $document);
}
