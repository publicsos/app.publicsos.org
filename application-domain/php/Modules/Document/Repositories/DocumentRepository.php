<?php
namespace Modules\Document\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\Document\Models\Document;

class DocumentRepository
{
    public function find(int $id): ?Document
    {
        return Document::find($id);
    }

    public function saveOrUpdate(array $documentDetails): Document
    {

        $documentDetails['created_by'] = Auth::loginUsingId(1);
        $documentDetails['updated_by'] = Auth::loginUsingId(1);

        return Document::updateOrCreate(
            ['name' => $documentDetails['name']],
            $documentDetails
        );
    }

    public function getByPath(string $path): ?Document
    {
        return Document::where('content', $path)->first();
    }
}
