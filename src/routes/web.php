<?php

use Illuminate\Support\Facades\Route;
use Suhailparad\LogViewer\Http\Controllers\IndexController;

Route::get('logs/{file_id?}', [IndexController::class,'index']);
