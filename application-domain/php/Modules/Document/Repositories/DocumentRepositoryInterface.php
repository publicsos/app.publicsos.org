<?php


namespace Modules\Document\Repositories;



use Modules\Document\Models\Document;

interface DocumentRepositoryInterface
{
    public function find(int $id): ?Document;
    public function save(array $documentDetails): Document;
    public function update(Document $document, array $documentDetails): Document;
    public function getByPath(string $path): ?Document;
}
