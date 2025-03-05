<?php

use Illuminate\Support\Facades\Route;
use Modules\Mail\Http\Controllers\DashboardController;
use Modules\Mail\Http\Controllers\Campaigns\CampaignsController;
use Modules\Mail\Http\Controllers\Campaigns\CampaignDispatchController;
use Modules\Mail\Http\Controllers\Campaigns\CampaignTestController;
use Modules\Mail\Http\Controllers\Campaigns\CampaignDeleteController;
use Modules\Mail\Http\Controllers\Campaigns\CampaignDuplicateController;
use Modules\Mail\Http\Controllers\Campaigns\CampaignCancellationController;
use Modules\Mail\Http\Controllers\Campaigns\CampaignReportsController;
use Modules\Mail\Http\Controllers\MessagesController;
use Modules\Mail\Http\Controllers\EmailServices\EmailServicesController;
use Modules\Mail\Http\Controllers\EmailServices\TestEmailServiceController;
use Modules\Mail\Http\Controllers\Tags\TagsController;
use Modules\Mail\Http\Controllers\TemplatesController;
use Modules\Mail\Http\Controllers\WorkflowController;
use Modules\Mail\Http\Controllers\Inbox\InboxController;
use Modules\Mail\Http\Controllers\Subscribers\SubscribersController;
use Modules\Mail\Http\Controllers\Subscribers\SubscribersImportController;
use Illuminate\Routing\Router;

/*
 *
 * Backend Routes
 *
 * --------------------------------------------------------------------
 */
Route::group(['namespace' => '\Modules\Mail\Http\Controllers', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend', 'can:mail'], 'prefix' => 'admin'], function () {
    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     */

    // Campaigns
    Route::resource('campaigns', CampaignsController::class)->except(['show', 'destroy']);
    Route::prefix('campaigns')->name('campaigns.')->group(function () {
        Route::get('sent', [CampaignsController::class, 'sent'])->name('sent');
        Route::get('generate', [CampaignsController::class, 'generate'])->name('generate');
        Route::get('{id}', [CampaignsController::class, 'show'])->name('show');
        Route::get('{id}/preview', [CampaignsController::class, 'preview'])->name('preview');
        Route::put('{id}/send', [CampaignDispatchController::class, 'send'])->name('send');
        Route::get('{id}/status', [CampaignsController::class, 'status'])->name('status');
        Route::post('{id}/test', [CampaignTestController::class, 'handle'])->name('test');
        Route::post('rewrite', [CampaignsController::class, 'rewrite'])->name('rewrite');
        Route::get('{id}/confirm-delete', [CampaignDeleteController::class, 'confirm'])->name('destroy.confirm');
        Route::delete('', [CampaignDeleteController::class, 'destroy'])->name('destroy');
        Route::get('{id}/duplicate', [CampaignDuplicateController::class, 'duplicate'])->name('duplicate');
        Route::get('{id}/confirm-cancel', [CampaignCancellationController::class, 'confirm'])->name('confirm-cancel');
        Route::post('{id}/cancel', [CampaignCancellationController::class, 'cancel'])->name('cancel');
        Route::prefix('{id}/report')->name('reports.')->group(function () {
            Route::get('/', [CampaignReportsController::class, 'index'])->name('index');
            Route::get('recipients', [CampaignReportsController::class, 'recipients'])->name('recipients');
            Route::get('opens', [CampaignReportsController::class, 'opens'])->name('opens');
            Route::get('clicks', [CampaignReportsController::class, 'clicks'])->name('clicks');
            Route::get('unsubscribes', [CampaignReportsController::class, 'unsubscribes'])->name('unsubscribes');
            Route::get('bounces', [CampaignReportsController::class, 'bounces'])->name('bounces');
        });
    });

    // Messages
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessagesController::class, 'index'])->name('index');
        Route::get('draft', [MessagesController::class, 'draft'])->name('draft');
        Route::get('{id}/show', [MessagesController::class, 'show'])->name('show');
        Route::post('send', [MessagesController::class, 'send'])->name('send');
        Route::delete('{id}/delete', [MessagesController::class, 'delete'])->name('delete');
        Route::post('send-selected', [MessagesController::class, 'sendSelected'])->name('send-selected');
    });

    // Email Services
    Route::resource('email-services', EmailServicesController::class);
    Route::get('type/{id}', [EmailServicesController::class, 'emailServicesTypeAjax'])->name('email-services.ajax');
    Route::get('email-services/{id}/test', [TestEmailServiceController::class, 'create'])->name('email-services.test.create');
    Route::post('email-services/{id}/test', [TestEmailServiceController::class, 'store'])->name('email-services.test.store');
    Route::post('smtp-verify', [EmailServicesController::class, 'postVerify'])->name('email-services.postVerify');

    // Tags
    Route::resource('tags', TagsController::class)->except(['show']);
    Route::resource('templates', TemplatesController::class);


    // Subscribers
    Route::resource('subscribers', SubscribersController::class);
    Route::get('subscribers/export', [SubscribersController::class, 'export'])->name('subscribers.export');
    Route::get('subscribers/unsubscribe/{subscriberId}', [SubscribersController::class, 'unsubscribe'])->name('subscribers.unsubscribe');
    Route::get('subscribers/import', [SubscribersImportController::class, 'show'])->name('subscribers.import');
    Route::post('subscribers/import', [SubscribersImportController::class, 'store'])->name('subscribers.import.store');
    Route::get('subscribers/enrich/{subscriberId}', [SubscribersImportController::class, 'enrich'])->name('subscribers.enrich');


});
