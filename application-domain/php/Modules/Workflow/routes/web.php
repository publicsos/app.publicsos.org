<?php

use Illuminate\Support\Facades\Route;
use Modules\Workflow\Http\Controllers\Backend\WorkflowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
*
* Frontend Routes
*
* --------------------------------------------------------------------
*/
Route::group(['namespace' => '\Modules\Workflow\Http\Controllers\Frontend', 'as' => 'frontend.', 'middleware' => 'web', 'prefix' => ''], function () {

    /*
     *
     *  Frontend Workflows Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'workflows';
    $controller_name = 'WorkflowsController';
    Route::get("$module_name", ['as' => "$module_name.index", 'uses' => "$controller_name@index"]);
    Route::get("$module_name/{id}/{slug?}", ['as' => "$module_name.show", 'uses' => "$controller_name@show"]);
});

/*
*
* Backend Routes
*
* --------------------------------------------------------------------
*/
Route::group(['namespace' => '\Modules\Workflow\Http\Controllers\Backend', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend'], 'prefix' => 'admin'], function () {
    /*
    * These routes need view-backend permission
    * (good if you want to allow more than one group in the backend,
    * then limit the backend features by different roles or permissions)
    *
    * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
    */

    /*
     *
     *  Backend Workflows Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'workflows';
    $controller_name = 'WorkflowsController';
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::resource("$module_name", "$controller_name");




    Route::get('/', [WorkflowController::class, 'index'])->name('index');
    Route::get('create', [WorkflowController::class, 'create'])->name('create');
    Route::post('store', [WorkflowController::class, 'store'])->name('store');
    Route::get('{workflow}', [WorkflowController::class, 'show'])->name('show');
    Route::get('{workflow}/edit', [WorkflowController::class, 'edit'])->name('edit');
    Route::get('{workflow}/delete', [WorkflowController::class, 'delete'])->name('delete');
    Route::post('{workflow}/update', [WorkflowController::class, 'update'])->name('update');
    /** Diagram routes */
    Route::post('diagram/{workflow}/addTask', [WorkflowController::class, 'addTask'])->name('addTask');
    Route::post('diagram/{workflow}/addTrigger', [WorkflowController::class, 'addTrigger'])->name('addTrigger');
    Route::post('diagram/{workflow}/addConnection', [WorkflowController::class, 'addConnection'])->name('addConnection');
    Route::post('diagram/{workflow}/removeConnection', [WorkflowController::class, 'removeConnection'])->name('removeConnection');
    Route::post('diagram/{workflow}/removeTask', [WorkflowController::class, 'removeTask'])->name('removeTask');
    Route::post('diagram/{workflow}/updateNodePosition', [WorkflowController::class, 'updateNodePosition'])->name('updateNodePosition');
    /** Settings routes */
    Route::post('settings/{workflow}/changeConditions', [WorkflowController::class, 'changeConditions'])->name('changeConditions');
    Route::post('settings/{workflow}/changeValues', [WorkflowController::class, 'changeValues'])->name('changeValues');
    Route::post('settings/{workflow}/getElementSettings', [WorkflowController::class, 'getElementSettings'])->name('getElementSettings');
    Route::post('settings/{workflow}/getElementConditions', [WorkflowController::class, 'getElementConditions'])->name('getElementConditions');
    Route::post('settings/{workflow}/getElementDelays', [WorkflowController::class, 'getElementDelays'])->name('getElementDelays');
    Route::post('settings/{workflow}/loadResourceIntelligence', [WorkflowController::class, 'loadResourceIntelligence'])->name('loadResourceIntelligence');
    /** Log routes */
    Route::post('logs/reRun/{workflow_log_id}', [WorkflowController::class, 'reRun'])->name('reRun');
    Route::post('logs/reRun/', [WorkflowController::class, 'reRun'])->name('reRunJSHelper');
    Route::post('logs/{workflow}/getLogs', [WorkflowController::class, 'getLogs'])->name('getLogs');
    /** Triggers */
    Route::post('button_trigger/execute/{id}', [WorkflowController::class, 'triggerButton'])->name('triggers.button');

});
