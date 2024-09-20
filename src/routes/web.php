<?php

use Illuminate\Support\Facades\Route;
use Vietstars\LogsViewer\LogsViewerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('logs', [LogsViewerController::class, 'index']);