<?php

namespace Modules\Document\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Document\Models\Document;
use Modules\Document\Services\DocumentProcessor;
use Modules\Document\Enums\DocumentStatus;
use Modules\Document\Repositories\DocumentRepository;
use Yajra\DataTables\DataTables;

class DocumentsController extends BackendBaseController
{
    use Authorizable;

    public function __construct(
        private DocumentProcessor $processor,
        private DocumentRepository $documentRepo
    ) {
        $this->initializeModuleProperties();
    }

    private function initializeModuleProperties(): void
    {
        $this->module_title = 'Documents';
        $this->module_name = 'documents';
        $this->module_path = 'document::backend';
        $this->module_icon = 'fa-solid fa-sun';
        $this->module_model = "Modules\Document\Models\Document";
    }



    public function index_data()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $page_heading = label_case($module_title);

        $title = $page_heading.' '.label_case($module_action);

        $$module_name = $module_model::select('id','title', 'date', 'status', 'updated_at');

        $data = $$module_name;

        return Datatables::of($$module_name)
            ->addColumn('action', function ($data) {
                $module_name = $this->module_name;
                return view('backend.includes.action_column', compact('module_name', 'data'));
            })

            ->rawColumns(['action'])
            ->orderColumns(['id'], '-:column $1')
            ->make(true);
    }


    public function show($id)
    {
        $moduleData = $this->getModuleData('Show');
        $document = $this->module_model::with("entities")->findOrFail($id);


        return view("{$this->module_path}.{$this->module_name}.view-document", compact('document'));
    }

    public function import()
    {
        $moduleData = $this->getModuleData('Import');
        $documents = Document::all();

        return view(
            "{$this->module_path}.{$this->module_name}.import",
            array_merge($moduleData, compact('documents'))
        );
    }

    public function importDocument(Request $request)
    {
        $request->validate([
            'session_id' => 'required',
            'date' => 'required|date',
        ]);

        $this->cacheImportData($request);

        $documents = $this->processDocuments($request);

        flash('The following documents have been scrapped successfully', 'success');

        return view('document::backend.documents.import', compact('documents'));
    }

    private function cacheImportData(Request $request): void
    {
        Cache::put('date', $request->date);
        Cache::put('session_id', $request->session_id);
    }

    private function processDocuments(Request $request)
    {
        $documents = collect($this->processor->getDocuments($request->session_id, $request->date));

        return $documents->map(function ($document) use ($request) {
            return $this->prepareDocumentData($document, $request);
        });
    }

    private function prepareDocumentData(array $document, Request $request): Document
    {
        $documentData = [
            'title' => $document['text'],
            'date' => $request->date,
            'source' => $document['href'],
            'content' => null,
            'status' => DocumentStatus::Unprocessed->value,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
            'deleted_by' => null,
        ];

        return $this->documentRepo->save($documentData);
    }

    private function getModuleData(string $action): array
    {
        return [
            'module_title' => $this->module_title,
            'module_name' => $this->module_name,
            'module_path' => $this->module_path,
            'module_icon' => $this->module_icon,
            'module_name_singular' => Str::singular($this->module_name),
            'module_action' => $action,
        ];
    }

    public function processSelected(Request $request)
    {
       throw new \Exception('Not implemented');
    }
}
