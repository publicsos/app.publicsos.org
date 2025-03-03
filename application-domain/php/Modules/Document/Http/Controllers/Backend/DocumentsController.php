<?php

namespace Modules\Document\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
class DocumentsController extends BackendBaseController
{
    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Documents';

        // module name
        $this->module_name = 'documents';

        // directory path of the module
        $this->module_path = 'document::backend';

        // module icon
        $this->module_icon = 'fa-solid fa-sun';

        // module model name, path
        $this->module_model = "Modules\Document\Models\Document";
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Show';

        $$module_name_singular = $module_model::with("entities")->findOrFail($id);

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return view(
            "{$module_path}.{$module_name}.show",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_name_singular', 'module_action', "{$module_name_singular}")
        );
    }


    public function import()
    {

        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_action = 'Import';

        return view(
            "{$module_path}.{$module_name}.import",
            compact('module_name',  'module_path')
        );
    }



    public function importDocument(Request $request)
    {
        $options = [
            '--session_id' => $request->input('session_id'),
            '--document_id' => $request->input('document_id'),
            '--document_type' => $request->input('document_type', 'pdf'),
            '--document_path' => $request->input('document_path'),
            '--total_pages' => $request->input('total_pages'),
            '--upload_endpoint' => $request->input('upload_endpoint'),
            '--skip_upload' => $request->has('skip_upload'),
        ];

        // Filter out null values to avoid passing empty options to Artisan
        $filteredOptions = array_filter($options, function ($value) {
            return $value !== null;
        });

        // Execute the Artisan command
        Artisan::call('document:import', $filteredOptions);

        // Get the output of the Artisan command
        $output = Artisan::output();

        // Redirect back or show a success message
        return redirect()->back()->with('success', $output);
    }


}
