<?php

use App\Http\Middleware\AddTenantIdToLogs;
use Illuminate\Support\Facades\Route;
use Suhailparad\LogViewer\Http\Controllers\IndexController;
use Suhailparad\LogViewer\Http\Middleware\LogViewerMiddleware;


Route::middleware([
        LogViewerMiddleware::class
    ])
    ->prefix(config("log-viewer.route_prefix"))
    ->group(function(){
        Route::get('/{file_id?}', [IndexController::class,'index']);
    });
