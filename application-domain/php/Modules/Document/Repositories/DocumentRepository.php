<?php
namespace Modules\Document\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\Document\Models\Document;

use Modules\Document\Repositories\DocumentRepositoryInterface;

class DocumentRepository implements DocumentRepositoryInterface
{
    /**
     * Find a document by its ID
     */
    public function find(int $id): ?Document
    {
        return Document::find($id);
    }

    /**
     * Save a new document
     */
    public function save(array $documentDetails): Document
    {
        $documentDetails['created_by'] = Auth::id() ?? 1;
        $documentDetails['updated_by'] = Auth::id() ?? 1;

        // Extract entities from the document details
        $entities = $documentDetails['entities'] ?? [];
        unset($documentDetails['entities']);

        // Create the document
        $document = Document::create($documentDetails);

        // Process entities efficiently (check by path)
        $existingPaths = $document->entities()->pluck('value')->toArray();

        foreach ($entities as $entityData) {
            if (!in_array($entityData['value'], $existingPaths)) {
                $document->entities()->create($entityData);
            }
        }

        return $document;
    }

    public function update(Document $document, array $documentDetails): Document
    {
        $documentDetails['updated_by'] = Auth::id() ?? 1;

        // Extract entities from the document details
        $entities = $documentDetails['entities'] ?? [];
        unset($documentDetails['entities']);

        // Update the document
        $document->update($documentDetails);

        // Process entities efficiently (check by path)
        $existingPaths = $document->entities()->pluck('value')->toArray();

        foreach ($entities as $entityData) {
            if (!in_array($entityData['value'], $existingPaths)) {
                $document->entities()->create($entityData);
            }
        }

        return $document;
    }

    /**
     * Get a document by its path
     * Fixed: uses 'path' attribute instead of 'content'
     */
    public function getByPath(string $path): ?Document
    {
        return Document::where('path', $path)->first();
    }

    /**
     * Save or update a document based on name
     * @deprecated Use save() or update() instead
     */
    public function saveOrUpdate(array $documentDetails): Document
    {
        $document = Document::where('title', $documentDetails['title'])->first();

        if ($document) {
            return $this->update($document, $documentDetails);
        }

        return $this->save($documentDetails);
    }
}
